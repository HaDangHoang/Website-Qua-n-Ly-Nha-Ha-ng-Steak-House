<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use App\Models\InventoryTransaction;
use App\Models\InventoryItem;
use App\Models\InventoryStock;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class InventoryTransactionController extends Controller
{
    public function __construct()
    {
        // Gán middleware cho các phương thức
        $this->middleware('permission:Xem nhập kho', ['only' => ['index']]);
        $this->middleware('permission:Tạo mới nhập kho', ['only' => ['create']]);
        $this->middleware('permission:Sửa nhập kho', ['only' => ['edit']]);
        $this->middleware('permission:Xóa nhập kho', ['only' => ['destroy']]);
    }
    public function index(Request $request)
{
    $title = 'Phiếu Nhập Kho';

  
    $query = InventoryTransaction::query()
        ->select('inventory_transactions.*', 'users.name as staff_name')
        ->leftJoin('users', 'inventory_transactions.staff_id', '=', 'users.id')
        ->leftJoin('suppliers', 'inventory_transactions.supplier_id', '=', 'suppliers.id')
        ->with('supplier'); 

    // Lọc theo từ khóa tìm kiếm (tìm trong tên nhân viên và tên nhà cung cấp)
    if ($request->has('search') && !empty($request->search)) {
        $searchTerm = $request->search;
        $query->where(function($q) use ($searchTerm) {
            $q->where('users.name', 'like', '%' . $searchTerm . '%')  // Tìm kiếm trong tên nhân viên
              ->orWhere('suppliers.name', 'like', '%' . $searchTerm . '%'); // Tìm kiếm trong tên nhà cung cấp
        });
    }

    // Lọc theo trạng thái nếu có
    if ($request->has('status') && !empty($request->status)) {
        $query->where('inventory_transactions.status', $request->status);
    }

    // Lọc theo ngày nếu có
    if ($request->has('date') && !empty($request->date)) {
        $query->whereDate('inventory_transactions.created_at', $request->date);
    }

    // Sắp xếp theo cột và chiều hướng sắp xếp nếu có
    if ($request->has('sort') && in_array($request->sort, ['id', 'staff_name', 'name', 'total_amount'])) {
        $direction = $request->get('direction', 'asc') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($request->sort, $direction);
    }

    // Phân trang kết quả
    $transactions = $query->paginate(10);

    return view('admin.inventoryTransaction.index', compact('transactions', 'title'));
}




    public function showImportForm()
    {
        return view('admin.inventoryTransaction.import');
    }


    // Trong InventoryTransactionController.php
    public function import(Request $request)
    {
        try {
            // Validate file
            $validator = Validator::make($request->all(), [
                'file' => [
                    'required',
                    'file',
                    'mimes:xlsx,xls',
                    'max:5120', // 5MB max
                ]
            ], [
                'file.required' => 'Vui lòng chọn file Excel',
                'file.mimes' => 'File phải có định dạng .xlsx hoặc .xls',
                'file.max' => 'Kích thước file không được vượt quá 5MB',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ]);
            }

            // Đọc file Excel
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file);
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, true, true, true);

            if (count($data) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'File Excel không có dữ liệu'
                ]);
            }

            $ingredients = [];

            // Bỏ qua dòng tiêu đề
            array_shift($data);

            foreach ($data as $row) {
                if (!empty($row['A']) && !empty($row['B']) && !empty($row['C'])) {
                    $ingredientName = trim($row['A']);
                    $quantity = floatval($row['B']);
                    $unitPrice = floatval($row['C']);

                    // Validate dữ liệu
                    if ($quantity <= 0) {
                        return response()->json([
                            'success' => false,
                            'message' => "Số lượng của nguyên liệu '{$ingredientName}' phải lớn hơn 0"
                        ]);
                    }

                    if ($unitPrice < 0) {
                        return response()->json([
                            'success' => false,
                            'message' => "Đơn giá của nguyên liệu '{$ingredientName}' không được âm"
                        ]);
                    }

                    $ingredient = Ingredient::where('name', 'LIKE', "%{$ingredientName}%")->first();

                    if ($ingredient) {
                        $ingredients[] = [
                            'ingredient_id' => $ingredient->id,
                            'name' => $ingredient->name,
                            'quantity' => $quantity,
                            'unit_price' => $unitPrice
                        ];
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => "Nguyên liệu '{$ingredientName}' không tồn tại trong hệ thống"
                        ]);
                    }
                }
            }

            if (empty($ingredients)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không có dữ liệu hợp lệ trong file Excel'
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Import thành công!',
                'ingredients' => $ingredients
            ]);
        } catch (\Exception $e) {
            \Log::error('Import Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi import: ' . $e->getMessage()
            ]);
        }
    }




    public function show($id)
    {
        $title = 'Chi Tiết Phiếu Nhập Kho';
        $transaction = InventoryTransaction::with(['supplier', 'inventoryItems.ingredient'])
            ->findOrFail($id);

        // Lấy thông tin nhân viên từ bảng users
        $staff = User::find($transaction->staff_id);

        return view('admin.inventoryTransaction.show', compact('transaction', 'title', 'staff'));
    }

    public function createTransaction()
    {
        $user = Auth::user(); // Lấy thông tin user đã đăng nhập
        $suppliers = Supplier::all();

        // Kiểm tra xem có dữ liệu nguyên liệu nào đã được import trong session không
        $ingredients = session('imported_ingredients', Ingredient::all());

        return view('admin.inventoryTransaction.create', compact('user', 'suppliers', 'ingredients'));
    }

    public function storeTransaction(Request $request)
    {
        $request->merge([
            'total_amount' => (float) str_replace(['.', ','], ['', '.'], $request->input('total_amount'))
        ]);
        
        $validated = $request->validate([
            'total_amount' => 'required|numeric|min:0|max:99999999.99',
            'description' => 'nullable|string|max:255',
            'supplier_id' => 'required|exists:suppliers,id',
            'ingredients' => 'required|array',
            'ingredients.*.ingredient_id' => 'required|exists:ingredients,id',
            'ingredients.*.quantity' => 'required|numeric|min:1',
            'ingredients.*.unit_price' => 'required|numeric|min:0',
        ], [
            'total_amount.required' => 'Tổng số tiền là bắt buộc.',
            'total_amount.numeric' => 'Tổng số tiền phải là một số.',
            'total_amount.max' => 'Tổng số tiền không được vượt quá 100.000.000.',
            'total_amount.min' => 'Tổng số tiền không được âm.',
            'supplier_id.required' => 'Vui lòng chọn nhà cung cấp.',
            'supplier_id.exists' => 'Nhà cung cấp không tồn tại.',
            'ingredients.required' => 'Vui lòng thêm ít nhất một nguyên liệu.',
            'ingredients.*.ingredient_id.required' => 'Mã nguyên liệu là bắt buộc.',
            'ingredients.*.ingredient_id.exists' => 'Nguyên liệu không tồn tại.',
            'ingredients.*.quantity.required' => 'Số lượng là bắt buộc.',
            'ingredients.*.quantity.numeric' => 'Số lượng phải là số.',
            'ingredients.*.quantity.min' => 'Số lượng phải lớn hơn 0.',
            'ingredients.*.unit_price.required' => 'Đơn giá là bắt buộc.',
            'ingredients.*.unit_price.numeric' => 'Đơn giá phải là số.',
            'ingredients.*.unit_price.min' => 'Đơn giá không được âm.',
        ]);



        // Lấy ID của user đang đăng nhập
        $userId = Auth::id();

        // Tạo giao dịch nhập kho
        $transaction = InventoryTransaction::create([
            'transaction_type' => 'nhập',
            'total_amount' => $validated['total_amount'],
            'description' => $validated['description'],
            'supplier_id' => $validated['supplier_id'],
            'staff_id' => $userId,
            'status' => 'chờ xử lý',
        ]);

        // Lặp qua các nguyên liệu đã chọn và tạo InventoryItem
        foreach ($validated['ingredients'] as $ingredientData) {
            InventoryItem::create([
                'ingredient_id' => $ingredientData['ingredient_id'],
                'inventory_transaction_id' => $transaction->id,
                'quantity' => $ingredientData['quantity'],
            ]);
        }

        // Trở lại trang tạo giao dịch với thông báo thành công
        return redirect()->route('transactions.index')->with('success', 'Tạo phiếu nhập và thêm nguyên liệu thành công!');
    }




    public function addItemForm($id)
    {
        $transaction = InventoryTransaction::findOrFail($id);
        $ingredients = Ingredient::all(); // Lấy tất cả nguyên liệu
        return view('admin.inventory.add_item', compact('transaction', 'ingredients')); // Đảm bảo đúng đường dẫn
    }

    public function storeItem($id, Request $request)
    {
        $validated = $request->validate([
            'ingredient_id' => 'required|exists:ingredients,id',
            'quantity' => 'required|numeric|min:1',
        ]);

        InventoryItem::create([
            'ingredient_id' => $validated['ingredient_id'],
            'inventory_transaction_id' => $id,
            'quantity' => $validated['quantity'],
        ]);

        return redirect()->route('transactions.add_items', $id)->with('success', 'Thêm nguyên liệu thành công!');
    }

    public function edit($id)
    {
        $title = 'Chỉnh Sửa Phiếu Nhập Kho';
        $transaction = InventoryTransaction::findOrFail($id);

        // Kiểm tra nếu trạng thái là "hoàn thành" thì ngăn chỉnh sửa
        if ($transaction->status === 'hoàn thành') {
            return redirect()->route('transactions.show', $transaction->id)
                ->with('error', 'Phiếu đã hoàn thành và không thể chỉnh sửa.');
        }

        $user = auth()->user();
        $suppliers = Supplier::all();
        $ingredients = Ingredient::all();

        return view('admin.inventoryTransaction.edit', compact('transaction', 'title', 'suppliers', 'ingredients', 'user'));
    }



    public function update(Request $request, $id)
    {
        $transaction = InventoryTransaction::findOrFail($id);

        // Bỏ `staff_id` ra khỏi xác thực
        $validated = $request->validate([
            'total_amount' => 'required|numeric',
            'description' => 'nullable|string',
            'supplier_id' => 'required|exists:suppliers,id',
            'ingredients.*.ingredient_id' => 'required|exists:ingredients,id',
            'ingredients.*.quantity' => 'required|numeric|min:1',
        ]);

        // Cập nhật các trường trong giao dịch
        $transaction->update([
            'total_amount' => $validated['total_amount'],
            'description' => $validated['description'],
            'supplier_id' => $validated['supplier_id'],
            // Không cần cập nhật staff_id
        ]);

        // Xóa các mục nguyên liệu cũ và thêm lại
        $transaction->inventoryItems()->delete();

        foreach ($validated['ingredients'] as $ingredientData) {
            InventoryItem::create([
                'ingredient_id' => $ingredientData['ingredient_id'],
                'inventory_transaction_id' => $transaction->id,
                'quantity' => $ingredientData['quantity'],
            ]);
        }

        return redirect()->route('transactions.index', $transaction->id)->with('success', 'Cập nhật phiếu nhập thành công!');
    }


    public function updateStatus(Request $request, $id)
    {
        // Tìm giao dịch theo ID
        $transaction = InventoryTransaction::findOrFail($id);

        // Xác thực dữ liệu đầu vào
        $validated = $request->validate([
            'status' => 'required|string',
        ]);

        // Kiểm tra nếu trạng thái hiện tại là "hoàn thành" thì không cho phép chuyển đổi ngược về trạng thái "chờ xử lý"
        if ($transaction->status === 'hoàn thành' && $validated['status'] === 'chờ xử lý') {
            return redirect()->route('transactions.show', $transaction->id)
                ->with('error', 'Không thể chuyển trạng thái từ hoàn thành về chờ xử lý.');
        }

        // Cập nhật trạng thái mới
        $transaction->status = $validated['status'];
        $transaction->save();

        // Kiểm tra nếu trạng thái được thay đổi thành "Hủy"
        if ($transaction->status === 'Hủy') {
            $transaction->inventoryItems()->delete();
        }

        // Nếu trạng thái được thay đổi thành "hoàn thành", cập nhật tồn kho
        if ($transaction->status === 'hoàn thành') {
            foreach ($transaction->inventoryItems as $item) {
                $stock = InventoryStock::firstOrNew(['ingredient_id' => $item->ingredient_id]);
                $stock->quantity_stock += $item->quantity;
                $stock->last_update = now();
                $stock->save();
            }
        }

        return redirect()->route('transactions.show', $transaction->id)->with('success', 'Cập nhật phiếu nhập thành công!');
    }



    public function finalizeTransaction($id)
    {
        $transaction = InventoryTransaction::with('inventoryItems')->findOrFail($id);

        foreach ($transaction->inventoryItems as $item) {
            $stock = InventoryStock::firstOrNew(['ingredient_id' => $item->ingredient_id]);
            $stock->quantity_stock += $item->quantity;
            $stock->last_update = now(); // Sử dụng now() để lấy thời gian hiện tại
            $stock->save(); // Lưu stock, không cần `updated_at`
        }

        $transaction->status = 'hoàn thành';
        $transaction->save();

        return back()->with('success', 'Phiếu nhập hoàn thành và kho được cập nhật!');
    }


    public function destroy($id)
    {
        $transaction = InventoryTransaction::findOrFail($id);

        // Xóa tất cả các mục nguyên liệu liên quan đến giao dịch
        $transaction->inventoryItems()->delete();

        // Sau đó xóa giao dịch
        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Xóa phiếu nhập thành công!');
    }
}
