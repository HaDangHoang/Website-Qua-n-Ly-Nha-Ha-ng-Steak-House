<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Table;
use App\Http\Requests\StoreTableRequest;
use App\Http\Requests\UpdateTableRequest;
use App\Traits\TraitCRUD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class TableController extends Controller
{

    public function __construct()
    {
        // Gán middleware cho các phương thức
        $this->middleware('permission:Xem bàn', ['only' => ['index']]);
        $this->middleware('permission:Tạo mới bàn', ['only' => ['create']]);
        $this->middleware('permission:Sửa bàn', ['only' => ['edit']]);
        $this->middleware('permission:Xóa bàn', ['only' => ['destroy']]);
    }

    use TraitCRUD;

    protected $model = Table::class;
    protected $viewPath = 'admin.tables';
    protected $routePath = 'table';


    public function index(Request $request)
    {
        $title = 'Bàn';
        $query = Table::query();

        // Tìm kiếm bàn theo số bàn
        if ($request->filled('name')) {
            $query->where('table_number', 'like', '%' . $request->name . '%');
        }

        // Lọc theo trạng thái bàn
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }


        // Lọc theo loại bàn (nếu có)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Lọc theo thời gian đặt bàn (từ ngày - đến ngày)
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
        }

        // Lọc theo số lượng khách (từ - đến)
        if ($request->filled('guest_min')) {
            $query->where('guest_count', '>=', $request->guest_min);
        }
        if ($request->filled('guest_max')) {
            $query->where('guest_count', '<=', $request->guest_max);
        }

        // Lọc theo số lượng đơn hàng
        if ($request->filled('order_count')) {
            if ($request->order_count == '1') {
                $query->has('orders'); // Giả sử có quan hệ 'orders' với Table
            } else {
                $query->doesntHave('orders');
            }
        }

        // Lấy tham số sort và direction từ request
        $sort = $request->input('sort', 'table_number'); // Mặc định sắp xếp theo 'table_number'
        $direction = $request->input('direction', 'asc'); // Mặc định sắp xếp tăng dần

        // Xác nhận cột sắp xếp hợp lệ
        $allowedSorts = ['area', 'table_number']; // Các cột cho phép sắp xếp
        $sort = in_array($sort, $allowedSorts) ? $sort : 'table_number';

        // Xác nhận thứ tự sắp xếp hợp lệ
        $direction = in_array($direction, ['asc', 'desc']) ? $direction : 'asc';

        // Lấy danh sách bàn và phân trang, áp dụng sắp xếp
        $tables = $query->orderBy($sort, $direction)->paginate(10);


        // Trả về view cùng với kết quả
        return view('admin.tables.index', compact('tables', 'title'));
    }





    public function create()
    {
        $title = 'Thêm Mới Bàn';
        return view('admin.tables.create', compact('title')); // Kiểm tra xem view này có tồn tại không
    }

    public function store(StoreTableRequest $request)
    {
        Table::create($request->validated());
        return redirect()->route('admin.table.index')->with('success', 'Bàn đã được thêm thành công!');
    }

    public function edit(Table $table)
    {
        $title = 'Chỉnh Sửa Bàn';
        // Kiểm tra nếu bàn đã thay đổi số bàn và số bàn mới đã tồn tại trong khu vực
        if ($table->isDirty('table_number')) {
            $existingTable = Table::where('area', $table->area)
                ->where('table_number', $table->table_number)
                ->where('id', '!=', $table->id)
                ->exists();

            if ($existingTable) {
                return redirect()->back()->withErrors(['table_number' => 'Số bàn này đã tồn tại trong khu vực, không thể sửa.']);
            }
        }

        return view('admin.tables.edit', compact('table', 'title'));
    }




    public function update(Request $request, $id)
    {
        $table = Table::findOrFail($id);

        // Kiểm tra sự trùng lặp chỉ khi số bàn thay đổi
        $request->validate([
            'area' => 'required|string|max:255',
            'table_number' => [
                'required',
                'integer',
                'min:1',
                'max:100',
                Rule::unique('tables')->where(function ($query) use ($request, $table) {
                    return $query->where('area', $request->area)
                        ->where('table_number', $request->table_number)
                        ->where('id', '!=', $table->id);
                })
            ],
            'status' => 'required|string|max:255',
        ]);

        // Kiểm tra và cập nhật trạng thái bàn theo thứ tự hợp lệ
        if ($request->has('status') && $request->status != $table->status) {
            // Đảm bảo trạng thái chuyển đổi hợp lệ
            $validTransitions = [
                'Available' => ['Pending', 'Reserved'],   // Từ 'Available' có thể chuyển sang 'Pending' và 'Reserved'
                'Pending' => ['Reserved'],                 // Từ 'Pending' có thể chuyển sang 'Reserved'
                'Reserved' => ['Occupied'],                // Từ 'Reserved' có thể chuyển sang 'Occupied'
                'Occupied' => ['Completed'],              // Từ 'Occupied' có thể chuyển sang 'Completed'
                'Completed' => [],                        // 'Completed' không thể chuyển sang trạng thái khác
            ];

            // Kiểm tra nếu trạng thái yêu cầu không hợp lệ so với trạng thái hiện tại
            if (!in_array($request->status, $validTransitions[$table->status] ?? [])) {
                // Trả về thông báo lỗi
                return redirect()->back()->withErrors(['status' => 'Không thể thay đổi trạng thái bàn này theo cách này.']);
            }
        }

        // Cập nhật thông tin bàn
        $table->update([
            'area' => $request->area,
            'table_number' => $request->table_number,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.table.index')->with('success', 'Bàn đã được cập nhật thành công!');
    }









    public function destroy(Table $table)
    {
        // Kiểm tra xem bàn có đang được đặt không
        if ($table->status === 'Reserved') {
            return redirect()->route('admin.table.index')->with('error', 'Không thể xóa bàn này vì đang ở trạng thái đã đặt trước.');
        }

        // Kiểm tra xem có đặt trước hoặc đơn hàng nào liên quan không
        if ($this->hasRelatedRecords($table->id)) {
            return redirect()->route('admin.table.index')->with('error', 'Không thể xóa bàn này vì vẫn còn đặt trước liên quan.');
        }

        $table->delete(); // Xóa mềm
        return redirect()->route('admin.table.index')->with('success', 'Bàn đã được xóa mềm thành công!');
    }

    public function trash()
    {
        $title = 'Khôi Phục Danh Sách Bàn';
        $tables = Table::onlyTrashed()->paginate(10);
        return view('admin.tables.trash', compact('tables', 'title'));
    }

    public function restore($id)
    {
        $table = Table::withTrashed()->findOrFail($id);
        $table->restore();
        return redirect()->route('admin.tables.trash')->with('success', 'Bàn đã được khôi phục thành công!');
    }

    public function forceDelete($id)
    {
        $table = Table::withTrashed()->findOrFail($id);
        $table->forceDelete();
        return redirect()->route('admin.tables.trash')->with('success', 'Bàn đã được xóa vĩnh viễn!');
    }

    private function hasRelatedRecords($id)
    {
        $reservationHistoryExists = Schema::hasTable('reservation_history') &&
            Schema::hasTable('reservations') &&
            Schema::hasTable('reservation_tables') &&
            DB::table('reservation_history')->whereExists(function ($query) use ($id) {
                $query->select(DB::raw(1))
                    ->from('reservations')
                    ->whereColumn('reservation_history.reservation_id', 'reservations.id')
                    ->whereExists(function ($query) use ($id) {
                        $query->select(DB::raw(1))
                            ->from('reservation_tables')
                            ->whereColumn('reservations.id', 'reservation_tables.reservation_id')
                            ->where('reservation_tables.table_id', $id);
                    });
            })->exists();

        return $reservationHistoryExists;
    }
}
