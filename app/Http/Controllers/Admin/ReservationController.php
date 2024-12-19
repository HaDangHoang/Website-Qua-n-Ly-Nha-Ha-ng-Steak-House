<?php

namespace App\Http\Controllers\Admin;

use App\Events\MessageSent;
use App\Events\MessageSentt;
use App\Events\NotifyUserEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReservationRquest;
use App\Models\Coupon;
use App\Models\Dishes;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Reservation;
use App\Models\Table;
use App\Models\OrdersTable;
use App\Models\User;
use App\Traits\TraitCRUD;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use App\Models\Feedback;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Whoops\Exception\Formatter;
use Illuminate\Support\Str;






class ReservationController extends Controller
{

    use TraitCRUD;

    public function __construct()
    {
        // Gán middleware cho các phương thức
        $this->middleware('permission:Xem đặt bàn', ['only' => ['index']]);
        $this->middleware('permission:Tạo mới đặt bàn', ['only' => ['create']]);
        $this->middleware('permission:Sửa đặt bàn', ['only' => ['edit']]);
        $this->middleware('permission:Xóa đặt bàn', ['only' => ['destroy']]);
    }
    protected $model = Reservation::class;
    protected $viewPath = 'admin.reservation';
    protected $routePath = 'admin.reservation';

    public function index(Request $request)
    {
        $title = 'Đặt Bàn';
        $query = Reservation::query();

        // Lọc theo tên khách hàng
        if ($request->filled('customer_name')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->customer_name . '%');
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Lọc theo ngày
        if ($request->filled('date')) {
            $query->whereDate('reservation_time', $request->date);
        }

        // Lọc theo thông báo
        if ($request->filled('notification_type')) {
            $now = Carbon::now();
            switch ($request->notification_type) {
                case 'upcoming': // Sắp đến hạn 30 phút
                    $query->whereBetween('reservation_time', [
                        $now->toDateTimeString(),
                        $now->addMinutes(30)->toDateTimeString()
                    ])->where('status', 'Pending');
                    break;

                case 'waiting': // Chờ khách đến trong vòng 15 phút
                    $query->whereBetween('reservation_time', [
                        $now->subMinutes(15)->toDateTimeString(),
                        $now->toDateTimeString()
                    ])->where('status', 'Pending');
                    break;

                case 'overdue': // Quá hạn và bị hủy
                    $query->where('reservation_time', '<', $now->subMinutes(15)->toDateTimeString())
                        ->where('status', 'Cancelled');
                    break;
            }
        }

        // Lấy tham số sort và direction từ request
        $sort = $request->input('sort', 'id'); // Mặc định sắp xếp theo 'id'
        $direction = $request->input('direction', 'asc'); // Mặc định sắp xếp tăng dần

        // Xác nhận cột sắp xếp hợp lệ
        $allowedSorts = ['id', 'guest_count', 'deposit_amount']; // Các cột được phép sắp xếp
        $sort = in_array($sort, $allowedSorts) ? $sort : 'id';

        // Xác nhận thứ tự sắp xếp hợp lệ
        $direction = in_array($direction, ['asc', 'desc']) ? $direction : 'asc';

        // Áp dụng sắp xếp và phân trang vào truy vấn
        $reservations = $query->orderBy($sort, $direction)->paginate(10);

        // Truyền các biến tới view
        return view('admin.reservation.index', [
            'upcomingReservations' => $this->getUpcomingReservations(),
            'waitingReservations' => $this->getWaitingReservations(),
            'overdueReservations' => $this->getOverdueReservations(),
            'reservations' => $reservations,
            'title' => $title,
        ]);
    }

    public function apiIndex()
    {
        return response()->json(Reservation::all());
    }


    // Cập nhật trạng thái đặt bàn quá hạn

    private function updateOverdueReservations(Request $request)
    {
        $reservations = Reservation::with('customer')
            ->when($request->customer_name, function ($query) use ($request) {
                $query->whereHas('customer', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->customer_name . '%');
                });
            })
            ->paginate(10);

        $now = Carbon::now();

        // Cập nhật các đơn quá hạn thành 'Cancelled'
        Reservation::where('reservation_date', '=', $now->toDateString())
            ->where('reservation_time', '<', $now->copy()->subMinutes(15)->toTimeString())
            ->where('status', 'Pending')
            ->update(['status' => 'Cancelled']);

        // Đơn sắp đến hạn trong vòng 30 phút tới
        $upcomingReservations = Reservation::where('reservation_date', '=', $now->toDateString())
            ->where('reservation_time', '>=', $now->toTimeString())
            ->where('reservation_time', '<=', $now->copy()->addMinutes(30)->toTimeString())
            ->where('status', 'Pending')
            ->get();

        // Đơn đang chờ khách trong 15 phút
        $waitingReservations = Reservation::where('reservation_date', '=', $now->toDateString())
            ->where('reservation_time', '<', $now->toTimeString())
            ->where('reservation_time', '>=', $now->copy()->subMinutes(15)->toTimeString())
            ->where('status', 'Pending')
            ->get();

        // Đơn đã quá hạn
        $overdueReservations = Reservation::where('reservation_date', '=', $now->toDateString())
            ->where('reservation_time', '<', $now->copy()->subMinutes(15)->toTimeString())
            ->where('status', 'Cancelled')
            ->get();

        // Lấy tất cả danh sách đặt bàn
        $reservations = Reservation::all();
        $reservations = Reservation::paginate(10);

        // Truyền các biến tới view
        return view('admin.reservation.index', compact('upcomingReservations', 'waitingReservations', 'overdueReservations', 'reservations'));
    }

    public function checkUpcomingAndOverdueReservations()
    {
        $now = Carbon::now();

        Reservation::where('reservation_date', '=', $now->toDateString())
            ->where('reservation_time', '<', $now->copy()->subMinutes(15)->toTimeString())
            ->where('status', 'Pending')
            ->update(['status' => 'Cancelled']);
    }


    // Lấy danh sách đặt bàn sắp đến hạn
    private function getUpcomingReservations()
    {
        $now = Carbon::now();
        return Reservation::where('reservation_date', '=', $now->toDateString())
            ->where('reservation_time', '>=', $now->toTimeString())
            ->where('reservation_time', '<=', $now->copy()->addMinutes(30)->toTimeString())
            ->where('status', 'Pending')

            ->get();
    }


    // Lấy danh sách đặt bàn đang chờ
    private function getWaitingReservations()
    {
        $now = Carbon::now();

        return Reservation::where('reservation_date', '=', $now->toDateString())
            ->where('reservation_time', '<', $now->toTimeString())
            ->where('reservation_time', '>=', $now->copy()->subMinutes(15)->toTimeString())
            ->where('status', 'Pending')
            ->get();
    }

    // Lấy danh sách đặt bàn đã quá hạn
    private function getOverdueReservations()
    {
        $now = Carbon::now();

        return Reservation::where('reservation_date', '=', $now->toDateString())
            ->where('reservation_time', '<', $now->copy()->subMinutes(15)->toTimeString())
            ->where('status', 'Cancelled')
            ->get();
        // ->update(['status' => 'Cancelled']); // Cập nhật trạng thái thành 'Hủy'

        return view('admin.reservation.check', compact('upcomingReservations', 'waitingReservations', 'overdueReservations'));
    }



    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'customer_id' => 'nullable|integer|exists:customers,id',
                'customer_name' => 'required|string|max:255',
                'user_phone' => 'required|string|max:20',
                'reservation_time' => 'required|date_format:H:i',
                'reservation_date' => 'required|date',
                'guest_count' => 'required|integer|min:1',
                'status' => 'nullable|in:Confirmed,Pending,checked-in,Cancelled',
                'note' => 'nullable|string|max:255',
            ]);

            $reservation = Reservation::create([
                'customer_id' => $validatedData['customer_id'] ?? 1,
                'user_name' => $validatedData['customer_name'],
                'user_phone' => $validatedData['user_phone'],
                'reservation_date' => $validatedData['reservation_date'],
                'reservation_time' => $validatedData['reservation_time'],
                'guest_count' => $validatedData['guest_count'],
                'status' => $validatedData['status'] ?? 'Pending',
                'note' => $validatedData['note'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Reservation created successfully',
                'data' => $reservation,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }







    public function edit($id)
    {
        $title = 'Chỉnh Sửa Đặt Bàn';
        $reservation = Reservation::findOrFail($id);
        $customers = User::all();
        $coupons = Coupon::all();

        // Tách ngày và giờ từ reservation_time
        $reservationDate = \Carbon\Carbon::parse($reservation->reservation_date)->format('Y-m-d');
        $reservationTime = \Carbon\Carbon::parse($reservation->reservation_time)->format('H:i');

        return view('admin.reservation.edit', compact('reservation', 'customers', 'coupons', 'reservationDate', 'reservationTime', 'title'));
    }




    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            // Lấy thông tin đặt bàn hiện tại
            $reservation = Reservation::findOrFail($id);
            $currentStatus = $reservation->status;

            // Xác nhận dữ liệu đầu vào
            $validated = $request->validate([
                'customer_name' => 'required|string|max:255',
                'reservation_date' => 'required|date',
                'reservation_time' => 'required|date_format:H:i',
                'guest_count' => 'required|integer|min:1',
                'note' => 'nullable|string',
                'status' => 'required|in:Confirmed,Pending,Cancelled',
                'cancelled_reason' => 'nullable|string|max:255',
            ]);

            // Kết hợp ngày và giờ thành một giá trị duy nhất
            $reservationTime = Carbon::createFromFormat('Y-m-d', $request->reservation_date)
                ->setTimeFromTimeString($request->reservation_time)
                ->format('Y-m-d H:i:s');

            // Kiểm tra nếu thời gian đã chọn là quá khứ
            if (Carbon::parse($reservationTime)->isPast()) {
                return back()->withErrors(['reservation_time' => 'Thời gian đặt bàn không được là quá khứ. Vui lòng chọn lại thời gian hợp lệ.']);
            }

            // Nếu trạng thái mới giống trạng thái hiện tại, bỏ qua kiểm tra trạng thái
            if ($request->status !== $currentStatus) {
                // Logic kiểm tra trạng thái
                if ($currentStatus === 'Cancelled' && in_array($request->status, ['Pending', 'Confirmed'])) {
                    return back()->withErrors(['status' => 'Không thể thay đổi trạng thái bàn này từ Đã hủy về Chờ xử lý hoặc Đã xác nhận.']);
                }

                if ($currentStatus === 'Confirmed' && !in_array($request->status, ['Cancelled', 'Pending'])) {
                    return back()->withErrors(['status' => 'Không thể thay đổi trạng thái bàn này theo cách này.']);
                }

                if ($currentStatus === 'Pending' && $request->status === 'Confirmed') {
                    return back()->withErrors(['status' => 'Chờ xử lý không thể chuyển sang trạng thái Đã xác nhận.']);
                }
            }

            // Cập nhật tiền cọc
            $validated['reservation_time'] = $reservationTime;
            $validated['deposit_amount'] = $request->input('guest_count') >= 6
                ? $request->input('guest_count') * 100000
                : 0;

            // Cập nhật dữ liệu
            $reservation->update($validated);

            DB::commit();
            return redirect()->route('admin.reservation.index')->with('success', 'Đặt bàn được cập nhật thành công!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return back()->withErrors($e->errors());
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => "Đã xảy ra lỗi khi cập nhật đặt bàn: " . $e->getMessage()]);
        }
    }

    public function cancel(Request $request, $id)
    {
        // Tìm đơn đặt chỗ
        $reservation = Reservation::findOrFail($id);

        // Cập nhật trạng thái và lý do hủy
        $reservation->status = 'Cancelled';
        $reservation->cancelled_reason = $request->input('cancelled_reason');
        $reservation->save();

        return redirect()->route('admin.reservation.index')->with('success', 'Đơn đặt bàn đã được hủy thành công.');
    }




    public function showTime()
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh')->copy()->addHours(2);

        Carbon::setLocale('vi');
        $today = Carbon::today();
        $days = [];
        for ($i = 0; $i < 3; $i++) {
            $days[] = $today->copy()->addDays($i);
        }
        $timeSlots = [];
        $startHour = 11;
        $endHour = 21;
        for ($hour = $startHour; $hour < $endHour; $hour++) {
            $timeSlots[] = Carbon::createFromTime($hour, 0)->format('H:i:s');
            $timeSlots[] = Carbon::createFromTime($hour, 30)->format('H:i:s');
        }
    
        // Mảng giới hạn linh hoạt cho từng khung giờ
        $slotLimits = [
            '18:00:00' => 150,
            '19:00:00' => 150, 
            '19:30:00' => 150,  
            
        ];
    
        $defaultMaxCapacity = 150; // Giá trị mặc định
    
        // Initialize disabled slots array
        $disabledSlots = [];
    
        foreach ($days as $day) {
            foreach ($timeSlots as $timeSlot) {
                $timeSlotWithDate = $day->copy()->setTimeFromTimeString($timeSlot);
    
                // Lấy giới hạn từ mảng hoặc sử dụng giá trị mặc định
                $maxCapacity = $slotLimits[$timeSlot] ?? $defaultMaxCapacity;
    
                $totalPeople = Reservation::where('status', 'Confirmed')
                ->where('reservation_date', $day->format('Y-m-d')) // Kiểm tra ngày chính xác
                ->whereTime('reservation_time', $timeSlot) // Kiểm tra khung giờ
                ->sum('guest_count');
            
    
                if ($totalPeople >= $maxCapacity) {
                    $disabledSlots[$day->format('Y-m-d')][] = $timeSlot;
                }
            }
        }
    
        return view('client.booking', compact('days', 'timeSlots', 'now', 'disabledSlots'));

    }
    public function showInformation(Request $request)
    {
        $data = $request->all();
        $date = $request->query('date');
        $time = $request->query('time');
        return view('client.customer-information', compact('date', 'time', 'data'));
    }


    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();

        return redirect()->route('admin.reservation.index')->with('success', 'Đã xóa đặt bàn thành công');
    }







    public function updateReservation(Request $request, $id)
    {
        try {
            // Tìm sự kiện theo ID
            $reservation = Reservation::findOrFail($id);

            // Xác thực dữ liệu đầu vào
            $validatedData = $request->validate([
                'reservation_date' => 'required|date',
                'reservation_time' => 'required|date_format:H:i:s',
                'status' => 'nullable|in:Pending,Cancelled', // Trạng thái tùy chọn, chỉ nhận các giá trị hợp lệ
            ]);

            // Cập nhật thông tin sự kiện
            $reservation->reservation_date = $validatedData['reservation_date'];
            $reservation->reservation_time = \Carbon\Carbon::createFromFormat('H:i:s', $validatedData['reservation_time'])->format('H:i:s');
            $reservation->status = $validatedData['status'] ?? $reservation->status; // Nếu không có trạng thái, giữ nguyên

            // Lưu vào cơ sở dữ liệu
            $reservation->save();

            return response()->json([
                'message' => 'Cập nhật thành công!',
                'reservation' => $reservation,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Xử lý lỗi không tìm thấy sự kiện
            return response()->json([
                'message' => 'Không tìm thấy sự kiện với ID này!',
                'error' => $e->getMessage(),
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Xử lý lỗi xác thực
            return response()->json([
                'message' => 'Dữ liệu không hợp lệ!',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Xử lý lỗi khác
            return response()->json([
                'message' => 'Đã xảy ra lỗi khi cập nhật sự kiện!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }






    public function updateCalendar(Request $request, $id)
    {
        try {
            // Xác thực dữ liệu
            $validatedData = $request->validate([
                'customer_name' => 'required|string|max:255', // ánh xạ tới user_name
                'user_phone' => 'required|string|max:15',
                'reservation_date' => 'required|date_format:Y-m-d',
                'guest_count' => 'required|integer|min:1',
                'notes' => 'nullable|string',
            ]);

            // Tìm đặt bàn theo ID
            $reservation = Reservation::findOrFail($id);

            // Cập nhật thông tin đặt bàn
            $reservation->user_name = $validatedData['customer_name']; // ánh xạ customer_name tới user_name
            $reservation->user_phone = $validatedData['user_phone'];
            $reservation->reservation_date = $validatedData['reservation_date'];
            $reservation->guest_count = $validatedData['guest_count'];
            $reservation->note = $validatedData['notes']; // ánh xạ notes tới cột note

            // Lưu vào cơ sở dữ liệu
            $reservation->save();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật thành công!',
                'data' => $reservation,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ.',
                'errors' => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy lịch đặt bàn.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi cập nhật.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }












    public function processReservationCancellation(Request $request, $id)
    {
        try {
            Log::info("Bắt đầu xử lý hủy đặt bàn với ID: $id");

            // Validate dữ liệu đầu vào
            $validatedData = $request->validate([
                'note' => 'required|string|max:255',
            ]);

            // Tìm đặt bàn theo ID
            $reservation = Reservation::findOrFail($id);
            Log::info("Tìm thấy đặt bàn: ", ['reservation' => $reservation]);

            // Kiểm tra trạng thái đặt bàn
            if ($reservation->status === 'Completed') {
                Log::warning("Không thể hủy đặt bàn đã hoàn thành", ['id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể hủy đặt bàn đã hoàn thành.',
                ], 400);
            }

            if ($reservation->status === 'Cancelled') {
                Log::warning("Đặt bàn đã bị hủy trước đó", ['id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Đặt bàn đã bị hủy trước đó.',
                ], 400);
            }

            // Kiểm tra thời gian đặt bàn nếu trạng thái là pending
            if ($reservation->status === 'Pending') {
                if (!$reservation->reservation_date) {
                    Log::error("Thời gian đặt bàn không hợp lệ (null)", ['id' => $id]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Thời gian đặt bàn không hợp lệ.',
                    ], 400);
                }

                $reservationDateTime = Carbon::parse($reservation->reservation_date);
                Log::info("Thời gian đặt bàn: $reservationDateTime, Thời gian hiện tại: " . now());

                if (now()->greaterThan($reservationDateTime)) {
                    Log::warning("Đặt bàn đã quá hạn nhưng vẫn được hủy", [
                        'id' => $id,
                        'reservation_date' => $reservationDateTime,
                    ]);
                    // Thêm thông báo vào lý do hủy
                    $validatedData['note'] .= ' (Hủy đặt bàn đã quá hạn)';
                }
            }

            // Cập nhật trạng thái và lý do hủy
            $reservation->note = $validatedData['note'];
            $reservation->status = 'Cancelled'; // Đảm bảo khớp với ENUM
            $reservation->updated_at = now(); // Cập nhật thời gian chỉnh sửa
            $reservation->save();

            Log::info("Hủy đặt bàn thành công với ID: $id");
            return response()->json([
                'success' => true,
                'message' => 'Đặt bàn đã được hủy thành công.',
                'data' => $reservation,
            ]);
        } catch (ModelNotFoundException $e) {
            Log::error("Không tìm thấy đặt bàn với ID: $id");
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đặt bàn.',
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error("Dữ liệu không hợp lệ", ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error("Lỗi khi hủy đặt bàn: " . $e->getMessage(), ['id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi hủy đặt bàn.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

































    public function createReservation(StoreReservationRquest $request)
    {
        $lastReservation = Reservation::where('customer_id', auth()->id())
            ->latest('created_at')
            ->first();
        if ($lastReservation && now()->diffInMinutes($lastReservation->created_at) < 1) {
            return back()->with('err', 'Mỗi đơn đặt bàn cần cách nhau 1 phút!');
        }
        // Kiểm tra số lượng khách, nếu >= 6 thì chuyển hướng đến trang đặt cọc
        if ($request->guest_count >= 6) {
            // Lưu thông tin khách hàng tạm thời để sử dụng ở trang cọc
            $customerInformation = $request->all();
            return redirect()->route('deposit.client', compact('customerInformation'));
        } else {
            // Thực hiện giao dịch đặt bàn mà không cần cọc
            [$reservation, $user] = DB::transaction(function () use ($request) {
                $customer_id = null;
                $user = null;

                if (auth()->check()) {
                    $customer_id = auth()->id();
                } else {
                    $user = User::where('phone', $request->user_phone)->first();
                    if (!$user) {
                        $user = User::create([
                            'name' => $request->user_name,
                            'phone' => $request->user_phone,
                            'password' => bcrypt(Str::random(10)),
                            'status' => 'inactive',
                        ]);
                    }
                    $customer_id = $user->id;
                }

                $reservation = Reservation::create([
                    'customer_id' => $customer_id,
                    'user_name' => $request->user_name,
                    'user_phone' => $request->user_phone,
                    'guest_count' => $request->guest_count,
                    'note' => $request->note,
                    'reservation_date' => $request->reservation_date,
                    'reservation_time' => $request->reservation_time,
                ]);

                return [$reservation, $user];
            });

            return redirect()->route('reservationSuccessfully.client')->with([
                'reservation' => $reservation,
                'msg' => 'Đăng nhập thành công!',
                'user' => $user,
            ]);
        }
    }


    public function storeOtpSession(Request $request)
    {
        if ($request->otpVerified) {
            session(['otpVerified' => true]); // Lưu trạng thái OTP đã xác thực
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }


    // Hàm kiểm tra điều kiện cần OTP
    private function requireOtp($request)
    {
        // Ví dụ kiểm tra nếu số lượng người đặt bàn >= 6 thì cần OTP
        return $request->guest_count >= 6;
    }



    public function reservationSuccessfully(Request $request)
    {
        $reservation = session('reservation');
        $user = session('user');
        if (isset($reservation) && $reservation != null) {
            if (!Auth::check()) {
                Auth::login($user);
                $msg = session('msg');
                return view('client.reservation-successfully', compact('reservation', 'msg'));
            } else {
                return view('client.reservation-successfully', compact('reservation'));
            }
        } else {
            return redirect()->route('booking.client');
        }
    }
    public function createReservationWithMomo(Request $request)
    {
        $a = Reservation::where('id', $request->orderId)->first();
        if ($a) {
            return redirect()->route('booking.client')->with('err', 'Có vẻ như bạn đã gửi yêu cầu đặt bàn hai lần liên tiếp. Đơn đặt bàn này đã tồn tại!');
        }
        if ($request->query('extraData')) {
            if ($request->query('message') == 'Successful.') {
                $reservation = $request->query('extraData');
                $data = str_replace("'", '"', $reservation);
                $reservation = json_decode($data, true);
                $user = DB::transaction(function () use ($reservation, $request) {

                    $customer_id = null;
                    if (auth()->check()) {
                        $customer_id = auth()->id();
                        $user = null;
                    } else {
                        $user = User::where('phone', $reservation['user_phone'])->first();
                        if (!isset($user) && $user == null) {
                            $user = User::create([
                                'name' => $reservation['user_name'],
                                'phone' => $reservation['user_phone'],
                                'password' => fake()->password(),
                                'status' => 'inactive',
                            ]);
                        }

                        $customer_id = $user->id;
                    }
                    Reservation::create([
                        'id' => $request->query('orderId'),
                        'customer_id' => $customer_id,
                        'user_name' => $reservation['user_name'],
                        'user_phone' => $reservation['user_phone'],
                        'guest_count' => $reservation['guest_count'],
                        'deposit_amount' => $reservation['deposit_amount'],
                        'note' => $reservation['note'],
                        'reservation_date' => $reservation['reservation_date'],
                        'reservation_time' => $reservation['reservation_time'],
                    ]);
                    return $user;
                });
            } else {
                return redirect()->back()->with('err', 'Thanh toán không thành công!');
            }
        } else {
            $reservation = $request->all();
            $user = DB::transaction(function () use ($request) {
                $customer_id = null;
                if (auth()->check()) {
                    $customer_id = auth()->id();
                    $user = null;
                } else {
                    $user = User::where('phone', $request->user_phone)->first();
                    if (!isset($user) && $user == null) {
                        $user = User::create([
                            'name' => $request->user_name,
                            'phone' => $request->user_phone,
                            'password' => fake()->password(),
                            'status' => 'inactive',
                        ]);
                    }

                    $customer_id = $user->id;
                }
                Reservation::create([
                    'id' => $request->orderId,
                    'customer_id' => $customer_id,
                    'user_name' => $request->user_name,
                    'user_phone' => $request->user_phone,
                    'guest_count' => $request->guest_count,
                    'deposit_amount' => $request->deposit,
                    'note' => $request->note,
                    'reservation_date' => $request->reservation_date,
                    'reservation_time' => $request->reservation_time,
                ]);
                return $user;
            });
        }
        return redirect()->route('reservationSuccessfully.client')->with([
            'reservation' => $reservation,
            'msg' => 'Đăng nhập thành công!',
            'user' => $user
        ]);
    }


    public function showDeposit(Request $request)
    {
        $showDeposit = $request->customerInformation;
        $deposit = $showDeposit['guest_count'] * 100000;
        $orderId = time();
        return view('client.deposit', compact('showDeposit', 'deposit', 'orderId'));
    }

    public function checkout($orderId)
    {
        DB::transaction(function () use ($orderId) {
            $order = Order::find($orderId);
            $order->status = 'completed';
            $reservation = $order->reservation;
            if ($reservation) {
                $reservation->status = 'completed';
                $reservation->save();
            }
            $order->save();
            foreach ($order->tables as $tables) {
                $table = Table::find($tables->id);
                $table->status = 'Available';
                $table->save();
                $orderTable = OrdersTable::where('order_id', $orderId)
                    ->where('table_id', $table->id)
                    ->first();
                $orderTable->status = 'Hoàn thành';
                $orderTable->end_time = now();
                $orderTable->save();
            }
            $tables = Table::with([
                'orders' => function ($query) {
                    $query->where('orders.status', '!=', 'completed')
                        ->where('orders.status', '!=', 'waiting')
                        ->with([
                            'reservation' => function ($query) {
                                $query->select('id', 'user_name');
                            },
                            'customer' => function ($query) {
                                $query->select('id', 'name');
                            }
                        ]);
                }
            ])->get();
            broadcast(new MessageSent($tables))->toOthers();
            broadcast(new NotifyUserEvent('Đơn đặt bàn của bạn đã được thanh toán thành công!'));
        });
        return redirect(route('pos.index'));
    }
    public function checkoutt($orderId)
    {
        DB::transaction(function () use ($orderId) {
            $order = Order::find($orderId);
            $order->status = 'completed';
            $reservation = $order->reservation;
            if ($reservation) {
                $reservation->status = 'completed';
                $reservation->save();
            }
            $order->save();
            foreach ($order->tables as $tables) {
                $table = Table::find($tables->id);
                $table->status = 'Available';
                $table->save();
                $orderTable = OrdersTable::where('order_id', $orderId)
                    ->where('table_id', $table->id)
                    ->first();
                $orderTable->status = 'Hoàn thành';
                $orderTable->end_time = now();
                $orderTable->save();
            }
            $tables = Table::with([
                'orders' => function ($query) {
                    $query->where('orders.status', '!=', 'completed')
                        ->where('orders.status', '!=', 'waiting')
                        ->with([
                            'reservation' => function ($query) {
                                $query->select('id', 'user_name');
                            },
                            'customer' => function ($query) {
                                $query->select('id', 'name');
                            }

                        ]);
                }
            ])->get();
            broadcast(new MessageSentt($tables))->toOthers();
            broadcast(new NotifyUserEvent('Đơn đặt bàn của bạn đã được thanh toán thành công!'));
        });
        return response()->json([
            'success' => true,
            'message' => 'Thanh toán thành công !'
        ]);
    }




    //Layout bàn
    public function assignTables($reservationId)
    {

        DB::beginTransaction();
        try {
            // Tìm reservation
            $reservation = Reservation::query()->findOrFail($reservationId);

            // Parse start_time và tính end_time
            $startTime = Carbon::parse($reservation->reservation_time);
            $endTime = $startTime->copy()->addHours(1);
            // dd($startTime);
            // Lấy tất cả các bàn
            $allTables = Table::all();

            // Lấy các bàn có trạng thái trong khung giờ
            $reservedTables = OrdersTable::where('start_time', '<', $endTime) // Bàn bắt đầu trước khi kết thúc khung giờ
                ->where(function ($query) use ($startTime) {
                    $query->where('end_time', '>', $startTime)
                        ->orWhereNull('end_time');  // Bàn kết thúc sau khi bắt đầu khung giờ
                })
                ->get();

            // Map các bàn
            $tables = $allTables->map(function ($table) use ($reservedTables, $startTime) {
                // Tìm bàn trong danh sách đã đặt
                $order = $reservedTables->firstWhere('table_id', $table->id); // Tìm order có table_id trùng với table->id
                // dd($table, $order);
                if ($order) {
                    // Kiểm tra trạng thái để xác định
                    if ($order->status === 'Đang sử dụng' && $order->start_time <= $startTime) {
                        return [
                            'table_id' => $table->id,
                            'name' => $table->table_number,
                            'status' => 'Occupied',
                            'start_time' => $order->start_time,
                            'end_time' => $order->end_time,

                        ];
                    } elseif ($order->status === 'Đặt trước') {
                        return [
                            'table_id' => $table->id,
                            'name' => $table->table_number,
                            'status' => 'Reserved',
                            'start_time' => $order->start_time,
                            'end_time' => $order->end_time,
                        ];
                    }
                }
                // Nếu không có trong danh sách, bàn còn trống
                return [
                    'table_id' => $table->id,
                    'name' => $table->table_number,
                    'status' => 'Available',
                    'start_time' => null,
                    'end_time' => null,
                ];
            });
            // dd($tables);
            // Commit transaction nếu không có lỗi
            DB::commit();

            // Trả về view với các thông tin đã lấy
            return view('admin.reservation.table_layout', compact('tables', 'reservationId'));
        } catch (\Exception $e) {
            // Nếu có lỗi, rollback transaction
            DB::rollBack();

            // Xử lý lỗi (hoặc trả về một thông báo lỗi)
            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }

    public function submitTable(Request $request)
    {
        // dd($request->all());
        // DB::beginTransaction();
        //lấy ra đơn đặt bàn
        $reservationId = $request->reservation_id;
        $reservation = Reservation::query()->FindOrfail($reservationId);
        //lấy ra giờ check-in
        $start_time = Carbon::parse($reservation->reservation_time);
        $end_time = $start_time->copy()->addHours(1);
        $tables = $request->tables;

        $order = Order::where('reservation_id', $reservationId)->first();

        if (!$order) {
            // Nếu không có order liên kết với reservation_id, tạo một order mới
            $order = Order::create([
                'reservation_id' => $reservationId,
                'status' => 'pending', // Hoặc trạng thái bạn muốn
            ]);
        }
        $reservation->update([
            'status' => 'Confirmed', //
        ]);
        // dd($order);
        foreach ($tables as $table) {
            // Kiểm tra xung đột với bàn trong orders_tables
            $conflicts = DB::table('orders_tables')
                ->where('table_id', $table)
                ->where(function ($query) use ($start_time, $end_time) {
                    $query->whereBetween('start_time', [$start_time, $end_time])
                        ->orWhereBetween('end_time', [$start_time, $end_time])
                        ->orWhere(function ($query) use ($start_time, $end_time) {
                            $query->where('start_time', '<', $start_time)
                                ->where('end_time', '>', $end_time);
                        });
                })
                ->exists();

            // Nếu có xung đột thời gian, trả về lỗi
            if ($conflicts) {
                return redirect()->route('admin.reservation.index')->with('success', ' Bàn đã được đặt vui lòng chọn bàn khác');
            }
            $order->tables()->attach($table, [
                'order_id' => $order->id,
                'table_id' => $table,
                'start_time' => $start_time,
                'end_time' => $end_time,
                'status' => "Đặt trước",
            ]);
        }
        return redirect()->route('admin.reservation.index')->with('success', 'Xếp bàn thành công.');
    }

    // public function submitMoveTable(Request $request)
    // {
    //     $tableId = $request->input('dataId');
    //     $reservationId = $request->input('reservationId');
    //     $reservationData = ReservationTable::where('reservation_id', $reservationId)->first();
    //     if ($reservationData) {
    //         Table::where('id', $reservationData->table_id)->update(['status' => 'Available']);
    //     }
    //     ReservationTable::where('reservation_id', $reservationId)->update(['table_id' => $tableId]);
    //     Table::where('id', $tableId)->update(['status' => 'Reserved']);
    // return response()->json([
    //     'success' => true,
    //     'message' => 'Chuyển bàn thành công'
    // ]);
    // }



    public function cancelReservation(Request $request, $id)
    {
        try {
            // Lấy số điện thoại đã xác thực từ request
            $verifiedPhoneNumber = $request->input('phone_number');

            // Chuẩn hóa số điện thoại xác thực
            $normalizedVerifiedPhone = $this->normalizePhoneNumber($verifiedPhoneNumber);

            $reservation = Reservation::findOrFail($id);

            // Chuẩn hóa số điện thoại trong đơn đặt bàn
            $normalizedReservationPhone = $this->normalizePhoneNumber($reservation->user_phone);

            // Log để debug
            Log::info('Phone numbers comparison', [
                'original_verified' => $verifiedPhoneNumber,
                'original_reservation' => $reservation->user_phone,
                'normalized_verified' => $normalizedVerifiedPhone,
                'normalized_reservation' => $normalizedReservationPhone
            ]);

            // So sánh số điện thoại đã chuẩn hóa
            if ($normalizedVerifiedPhone !== $normalizedReservationPhone) {
                return response()->json([
                    'success' => false,
                    'message' => 'Số điện thoại xác thực không khớp với số điện thoại đặt bàn.'
                ], 403);
            }

            // Thực hiện hủy đặt bàn
            $reservation->status = 'cancelled';
            $reservation->save();

            return response()->json([
                'success' => true,
                'message' => 'Đặt bàn đã được hủy thành công.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error cancelling reservation', [
                'reservation_id' => $id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi hủy đặt bàn: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cancelReservationPopUp(Request $request)
    {
        try {
            $id = $request->id;
            $reason = $request->reason; // Lấy lý do từ request
            $reservation = Reservation::findOrFail($id);

            if ($reservation->status != 'Completed') {
                $reservation->status = 'Cancelled';
                $reservation->save();
                return response()->json([
                    'success' => true,
                    'icon' => 'success',
                    'title' => 'Thành công',
                    'message' => 'Đặt bàn đã được hủy thành công.'
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'icon' => 'warning',
                    'title' => 'Lỗi',
                    'message' => 'Cõ lỗi xảy ra, vui lòng thử lại!'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error cancelling reservation', [
                'reservation_id' => $id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi hủy đặt bàn: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getBanks()
    {

        $client = new Client();
        $response = $client->get('https://api.vietqr.io/v2/banks');
        $data = json_decode($response->getBody(), true);

        if ($data['code'] == '00') {
            $banks = $data['data'];
            return view('test', compact('banks'));
        }

        return 'Lỗi khi lấy danh sách ngân hàng';
    }



    public function print($orderId, Request $request)
    {
        $data = $request->end_time;
        $order = Order::find($orderId);
        $table = $order->tables['0'];
        $reservation_table = OrdersTable::where('order_id', $orderId)
            ->where('table_id', $table->id)
            ->first();
        $items = OrderItem::where('order_id', $orderId)
            ->where('status', '!=', 'hủy')
            ->where('status', '!=', 'chưa yêu cầu')
            ->get();
        $item = $items->all();
        $dishIds = $items->pluck('item_id')->toArray();
        $dishes = Dishes::whereIn('id', $dishIds)->get();
        $staff = User::find($order->staff_id);
        return view('pos.receipt', compact('dishes', 'data', 'order', 'table', 'staff', 'reservation_table', 'items'))->render();
    }

    // Hàm chuẩn hóa số điện thoại
    private function normalizePhoneNumber($phoneNumber)
    {
        // Loại bỏ tất cả ký tự không phải số
        $numbers = preg_replace('/[^0-9]/', '', $phoneNumber);

        // Nếu số điện thoại bắt đầu bằng 84, loại bỏ
        if (strpos($numbers, '84') === 0) {
            $numbers = substr($numbers, 2);
        }

        // Nếu số điện thoại không bắt đầu bằng 0, thêm vào
        if (strpos($numbers, '0') !== 0) {
            $numbers = '0' . $numbers;
        }

        return $numbers;
    }
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
            'reservation_id' => 'required|exists:reservations,id',
        ]);

        $inputOtp = $request->input('otp');
        $sessionOtp = Session::get('otp');

        if ($inputOtp == $sessionOtp) {
            $reservation = Reservation::find($request->input('reservation_id'));

            if ($reservation && $reservation->user_id == Auth::id()) {
                $reservation->delete();
                return response()->json(['success' => true, 'message' => 'Hủy đặt bàn thành công.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Không tìm thấy đặt bàn hoặc bạn không có quyền hủy.']);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Mã OTP không đúng. Vui lòng thử lại.']);
        }
    }

    // review
    public function submitFeedback(Request $request)
    {
        // $validated = $request->validate([
        //     'reservation_id' => 'required|exists:reservations,id',
        //     'customer_id' => 'required|exists:customers,id',
        //     'content' => 'required|string|max:500'
        // ]);
        // dd($validated);

        Feedback::create([
            'reservation_id' => $request->reservation_id,
            'customer_id' => $request->customer_id,
            'content' => $request->content
        ]);


        return response()->json(['success' => true]);
    }

    public function showFeedback($reservationId)
    {
        // Lấy danh sách đánh giá theo reservation_id
        $feedbacks = Feedback::where('reservation_id', $reservationId)->get();

        // Trả về view kèm danh sách đánh giá
        return view('reservation.feedback', compact('feedbacks'));
    }
}
