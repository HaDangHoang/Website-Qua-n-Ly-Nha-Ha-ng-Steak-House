<?php

namespace App\Http\Controllers\Admin;

use App\Events\CouponExpiryNotification;
use App\Events\OverdueCouponEvent;
use App\Events\UpcomingCouponEvent;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Traits\TraitCRUD;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class CouponController extends Controller
{
    use TraitCRUD;

    protected $model = Coupon::class;
    protected $viewPath = 'admin.coupon';
    protected $routePath = 'admin.coupon';

    public function __construct()
    {
        $this->middleware('permission:Xem mã giảm giá', ['only' => ['index', 'show']]);
        $this->middleware('permission:Tạo mới mã giảm giá', ['only' => ['create', 'store']]);
        $this->middleware('permission:Sửa mã giảm giá', ['only' => ['edit', 'update']]);
        $this->middleware('permission:Xóa mã giảm giá', ['only' => ['destroy', 'trash', 'forceDelete']]);
    }

    public function index(Request $request)
    {
        $title = 'Phiếu Giảm Giá';

        // Lấy tham số sort, direction, code và status từ request
        $sort = $request->input('sort', 'code');
        $direction = $request->input('direction', 'asc');
        $code = $request->input('code');
        $status = $request->input('status');

        // Xác nhận cột sắp xếp hợp lệ
        $allowedSorts = ['code', 'max_uses'];
        $sort = in_array($sort, $allowedSorts) ? $sort : 'code';

        // Xác nhận thứ tự sắp xếp hợp lệ
        $direction = in_array($direction, ['asc', 'desc']) ? $direction : 'asc';

        // Lấy danh sách các coupon sắp hết hạn trong 24 giờ
        $UpcomingCouponEvent = Coupon::where('end_time', '>', now())
            ->where('end_time', '<=', now()->addDay())
            ->get();

        // Lấy danh sách các coupon đã hết hạn
        $OverdueCouponEvent = Coupon::where('end_time', '<', now())
            ->get();

        // Phát sự kiện cho các coupon sắp hết hạn
        foreach ($UpcomingCouponEvent as $coupon) {
            event(new UpcomingCouponEvent($coupon));
        }

        // Phát sự kiện cho các coupon đã hết hạn
        foreach ($OverdueCouponEvent as $coupon) {
            event(new OverdueCouponEvent($coupon));
        }

        // Lấy danh sách coupon với phân trang, sắp xếp và lọc theo mã và trạng thái
        $coupons = Coupon::query();

        // Lọc theo mã coupon nếu có
        if ($code) {
            $coupons->where('code', 'like', "%{$code}%");
        }

        // Lọc theo trạng thái nếu có
        if ($status) {
            $coupons->where('status', $status);
        }

        // Áp dụng sắp xếp
        $coupons = $coupons->orderBy($sort, $direction)->paginate(10);

        // Trả về view với dữ liệu
        return view('admin.coupon.index', compact('UpcomingCouponEvent', 'OverdueCouponEvent', 'coupons', 'title'));
    }






    private function updateOverdueCoupons()
    {
        $now = Carbon::now();

        try {
            Log::info('Bắt đầu cập nhật phiếu giảm giá vào lúc ' . $now->toDateTimeString());

            // Cập nhật trạng thái phiếu giảm giá đã hết hạn
            $expiredCouponsCount = Coupon::where('end_time', '<', $now)
                ->where('status', '!=', 'expired')
                ->update(['status' => 'expired']);

            Log::info("Số phiếu giảm giá đã hết hạn được cập nhật: $expiredCouponsCount");

            // Kiểm tra lại trong cơ sở dữ liệu sau khi cập nhật
            $updatedCoupons = Coupon::where('status', 'expired')
                ->where('end_time', '<', $now)
                ->get();

            Log::info("Số phiếu giảm giá đã cập nhật: " . $updatedCoupons->count());

            // Phát sự kiện cho các phiếu giảm giá đã quá hạn (nếu có)
            $overdueCoupons = Coupon::where('status', 'expired')
                ->where('end_time', '<', $now)
                ->get();

            $overdueCouponsCount = $overdueCoupons->count();
            if ($overdueCouponsCount > 0) {
                foreach ($overdueCoupons as $coupon) {
                    // Phát sự kiện quá hạn cho mỗi phiếu giảm giá
                    event(new OverdueCouponEvent($coupon));
                    Log::info("Sự kiện quá hạn được kích hoạt cho phiếu giảm giá ID: {$coupon->id}");
                }
            }

            return redirect()->route('admin.coupon.index')
                ->with('status', "Phiếu giảm giá đã được kiểm tra và cập nhật thành công. Đã hết hạn: $expiredCouponsCount, Quá hạn: $overdueCouponsCount");
        } catch (\Exception $e) {
            Log::error('Lỗi khi cập nhật phiếu giảm giá: ' . $e->getMessage());

            return redirect()->route('admin.coupon.index')
                ->with('error', 'Đã xảy ra lỗi khi cập nhật phiếu giảm giá.');
        }
    }






    // Tạo phiếu giảm giá mới
    public function create()
    {
        $title = 'Thêm Mới Phiếu Giảm Giá';
        return view('admin.coupon.create', compact('title'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|max:15|unique:coupons', // Mã giảm giá không quá 15 ký tự
            'description' => 'nullable|string',
            'max_uses' => 'nullable|integer|min:1|max:100', // Số lượt sử dụng tối thiểu là 1, tối đa là 100
            'start_time' => 'nullable|date|after_or_equal:today', // Thời gian bắt đầu không được trong quá khứ và phải từ ngày hôm nay
            'end_time' => 'nullable|date|after_or_equal:start_time|after:start_time', // Thời gian kết thúc phải sau thời gian bắt đầu ít nhất 1 ngày
            'discount_type' => 'required|in:Percentage,Fixed',
            'discount_amount' => 'required_if:discount_type,Fixed|numeric',
            'discount_percentage' => 'required_if:discount_type,Percentage|numeric|between:5,100',
            'status' => 'required|in:active,inactive',
        ], [
            'code.required' => 'Mã giảm giá là bắt buộc.',
            'code.max' => 'Mã giảm giá không được quá 15 ký tự.',
            'max_uses.integer' => 'Số lượt sử dụng phải là một số nguyên.',
            'max_uses.min' => 'Số lượt sử dụng tối thiểu là 1.',
            'max_uses.max' => 'Số lượt sử dụng tối đa là 100.',
            'start_time.after_or_equal' => 'Thời gian bắt đầu phải từ ngày hôm nay trở đi.',
            'end_time.after_or_equal' => 'Thời gian kết thúc phải sau thời gian bắt đầu.',
            'end_time.after' => 'Thời gian kết thúc phải ít nhất cách thời gian bắt đầu một ngày.',
            'discount_amount.required_if' => 'Trường này là bắt buộc khi loại giảm giá là Fixed.',
            'discount_percentage.required_if' => 'Trường này là bắt buộc khi loại giảm giá là Percentage.',
            'discount_percentage.between' => 'Giảm giá theo phần trăm phải nằm trong khoảng từ 5% đến 100%.',
            'status.in' => 'Trạng thái phải là một trong các giá trị: active, inactive.',
        ]);



        // Kiểm tra nếu phiếu đã hết hạn
        if (isset($validated['end_time']) && now()->greaterThan($validated['end_time'])) {
            $validated['status'] = 'expired';
        }

        Coupon::create($validated);

        return redirect()->route('admin.coupon.index')->with('success', 'Phiếu giảm giá đã được tạo thành công.');
    }

    // Hiển thị chi tiết phiếu giảm giá
    public function show($id)
    {
        $title = 'Chi Tiết Phiếu Giảm Giá';
        $coupon = Coupon::findOrFail($id);
        return view('admin.coupon.detail', compact('coupon', 'title'));
    }

    // Chỉnh sửa phiếu giảm giá
    public function edit(Coupon $coupon)
    {
        $title = 'Chỉnh Sửa Phiếu Giảm Giá';
        return view('admin.coupon.edit', compact('coupon', 'title'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $now = Carbon::now(); // Đảm bảo lấy thời gian hiện tại

        $validated = $request->validate([
            'code' => [
                'required',
                'max:15',  // Giới hạn mã giảm giá không quá 15 ký tự
                Rule::unique('coupons')->ignore($coupon->id) // Tránh xung đột khi cập nhật
            ],
            'description' => 'nullable|string',
            'max_uses' => 'nullable|integer|min:1|max:100', // Số lượt sử dụng tối thiểu là 1, tối đa là 100
            'start_time' => 'nullable|date|after_or_equal:today', // Thời gian bắt đầu phải không nhỏ hơn thời gian hiện tại
            'end_time' => 'nullable|date|after_or_equal:start_time|after:start_time', // Thời gian kết thúc phải sau thời gian bắt đầu ít nhất 1 ngày
            'discount_type' => 'required|in:Percentage,Fixed', // Loại giảm giá phải là Percentage hoặc Fixed
            'discount_amount' => 'nullable|numeric|required_if:discount_type,Fixed|min:0.01', // Nếu loại là Fixed, phải có giá trị
            'discount_percentage' => 'nullable|integer|required_if:discount_type,Percentage|min:1|max:100', // Nếu loại là Percentage, giá trị trong khoảng 1 đến 100
            'status' => 'required|in:active,inactive,expired', // Trạng thái chỉ có thể là active, inactive hoặc expired
        ], [
            // Tùy chỉnh thông báo lỗi
            'code.required' => 'Mã giảm giá là bắt buộc.',
            'code.max' => 'Mã giảm giá không được vượt quá 15 ký tự.',
            'max_uses.integer' => 'Số lượt sử dụng phải là một số nguyên.',
            'max_uses.min' => 'Số lượt sử dụng tối thiểu là 1.',
            'max_uses.max' => 'Số lượt sử dụng tối đa là 100.',
            'start_time.after_or_equal' => 'Thời gian bắt đầu phải không nhỏ hơn ngày hôm nay.',
            'end_time.after_or_equal' => 'Thời gian kết thúc phải sau thời gian bắt đầu.',
            'end_time.after' => 'Thời gian kết thúc phải cách thời gian bắt đầu ít nhất 1 ngày.',
            'discount_amount.required_if' => 'Số tiền giảm giá là bắt buộc khi loại giảm giá là Fixed.',
            'discount_percentage.required_if' => 'Phần trăm giảm giá là bắt buộc khi loại giảm giá là Percentage.',
            'discount_percentage.between' => 'Phần trăm giảm giá phải nằm trong khoảng từ 1 đến 100.',
            'status.in' => 'Trạng thái phải là một trong các giá trị: active, inactive, expired.',
        ]);


        // Kiểm tra trạng thái "đã hủy"
        if ($coupon->status === 'expired' && $validated['status'] !== 'expired') {
            return redirect()->back()->with('error', 'Không thể thay đổi trạng thái của phiếu giảm giá đã hủy.');
        }

        // Xử lý logic tùy theo loại giảm giá
        if ($validated['discount_type'] === 'Fixed') {
            // Loại giảm giá cố định thì bỏ discount_percentage
            $validated['discount_percentage'] = null;
        } elseif ($validated['discount_type'] === 'Percentage') {
            // Loại giảm giá phần trăm thì bỏ discount_amount
            $validated['discount_amount'] = null;
        }

        // Cập nhật dữ liệu vào model
        $coupon->update($validated);

        // Chuyển hướng về danh sách với thông báo thành công
        return redirect()->route('admin.coupon.index')->with('success', 'Phiếu giảm giá đã được cập nhật thành công.');
    }




    // Xóa phiếu giảm giá
    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);

        if ($coupon->status === 'active') {
            return redirect()->route('admin.coupon.index')->with('error', 'Không thể xóa phiếu giảm giá đang hoạt động.');
        }

        $coupon->delete();

        return redirect()->route('admin.coupon.index')->with('success', 'Phiếu giảm giá đã được xóa thành công.');
    }

    // Quản lý thùng rác
    public function trash()
    {
        $title = 'Khôi Phục Danh Sách Phiếu Giảm Giá';
        $coupons = Coupon::onlyTrashed()->paginate(10);
        return view($this->viewPath . '.trash', compact('coupons', 'title'));
    }

    public function restore($id)
    {
        $coupon = Coupon::withTrashed()->findOrFail($id);
        $coupon->restore();

        return redirect()->route($this->routePath . '.trash')->with('success', 'Phiếu giảm giá đã được khôi phục thành công.');
    }

    public function forceDelete($id)
    {
        $coupon = Coupon::withTrashed()->findOrFail($id);
        $coupon->forceDelete();

        return redirect()->route($this->routePath . '.trash')->with('success', 'Phiếu giảm giá đã được xóa vĩnh viễn.');
    }
}
