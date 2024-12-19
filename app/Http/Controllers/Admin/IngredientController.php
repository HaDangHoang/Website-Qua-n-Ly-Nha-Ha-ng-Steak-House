<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\IngredientType;
use App\Models\Supplier;
use App\Traits\TraitCRUD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\InventoryStock;

class IngredientController extends Controller
{
    public function __construct()
    {
        // Gán middleware cho các phương thức
        $this->middleware('permission:Xem nguyên liệu', ['only' => ['index']]);
        $this->middleware('permission:Tạo mới nguyên liệu', ['only' => ['create']]);
        $this->middleware('permission:Sửa nguyên liệu', ['only' => ['edit']]);
        $this->middleware('permission:Xóa nguyên liệu', ['only' => ['destroy']]);
    }

    use TraitCRUD;

    protected $model = Ingredient::class;
    protected $viewPath = 'admin.ingredient';
    protected $routePath = 'admin.ingredient';

    public function index(Request $request)
    {

        // Nhận các tham số sắp xếp từ request
        $title = 'Nguyên liệu';
        $sort = $request->input('sort', 'id'); // Mặc định sắp xếp theo id
        $direction = $request->input('direction', 'asc'); // Mặc định theo thứ tự tăng dần

        // Nhận tham số tìm kiếm từ request
        $searchTerm = $request->input('search', ''); // Mặc định là chuỗi rỗng nếu không tìm

        // Lấy danh sách nguyên liệu theo loại và sắp xếp, đồng thời áp dụng tìm kiếm
        $freshIngredients = Ingredient::where('category', 'Đồ Tươi')
            ->where('name', 'like', '%' . $searchTerm . '%') // Tìm kiếm theo tên
            ->orderBy($sort, $direction)
            ->paginate(6);

        $cannedIngredients = Ingredient::where('category', 'Đồ Đóng Hộp')
            ->where('name', 'like', '%' . $searchTerm . '%') // Tìm kiếm theo tên
            ->orderBy($sort, $direction)
            ->paginate(6);
        $ingredients = Ingredient::latest()->paginate(10);
        return view('admin.ingredientType.ingredient.index', compact('freshIngredients', 'cannedIngredients', 'searchTerm', 'title', 'ingredients'));
    }

    public function create()
    {
        $title = 'Thêm Mới Nguyên liệu';
        $suppliers = Supplier::all();
        // $ingredientTypes = IngredientType::all();

        return view('admin.ingredientType.ingredient.create', compact('suppliers', 'title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // 'supplier_id' => 'required|exists:suppliers,id',
            'price' => 'required|numeric',
            'unit' => 'required|string|max:50',
            'category' => 'required|in:Đồ tươi,Đồ đóng hộp',
        ]);

        DB::transaction(function () use ($request) {
            Ingredient::create([
                'name' => $request->name,
                // 'supplier_id' => $request->supplier_id, // Bỏ comment nếu cần sử dụng
                'price' => $request->price,
                'unit' => $request->unit,
                'category' => $request->category,
                'name.max' => 'Tên nguyên liệu không được vượt quá 50 ký tự.',
            ]);
        });

        return redirect()->route('admin.ingredient.index')->with('success', 'Nguyên liệu đã được tạo thành công.');
    }
    public function edit($id)
    {
        $title = 'Chỉnh Sửa Nguyên liệu';
        $ingredient = Ingredient::findOrFail($id);
        // $suppliers = Supplier::all(); // Bỏ comment nếu cần sử dụng

        return view('admin.ingredientType.ingredient.edit', compact('ingredient', 'title'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // 'supplier_id' => 'required|exists:suppliers,id', // Bỏ comment nếu cần sử dụng
            'price' => 'required|numeric',
            'unit' => 'required|string|max:50',
            'category' => 'required|in:Đồ tươi,Đồ đóng hộp',
            'name.max' => 'Tên nguyên liệu không được vượt quá 50 ký tự.',
        ]);

        DB::transaction(function () use ($request, $id) {
            $ingredient = Ingredient::findOrFail($id);
            $ingredient->update([
                'name' => $request->name,
                // 'supplier_id' => $request->supplier_id, // Bỏ comment nếu cần sử dụng
                'price' => $request->price,
                'unit' => $request->unit,
                'category' => $request->category,
            ]);
        });

        return redirect()->route('admin.ingredient.index')->with('success', 'Nguyên liệu đã được cập nhật thành công.');
    }

    public function show($id)
    {
        $title = 'Chi Tiết Nguyên liệu';
        $ingredient = Ingredient::findOrFail($id);

        return view('admin.ingredientType.ingredient.show', compact('ingredient', 'title'));
    }


    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $ingredient = Ingredient::findOrFail($id);
            $ingredient->delete();
        });

        return redirect()->route('admin.ingredient.index')->with('success', 'Nguyên liệu đã được xóa thành công.');
    }
    public function import(Request $request)
    {
        DB::transaction(function () use ($request) {
            // Xác thực file upload
            $request->validate([
                'file' => 'required|mimes:xlsx,xls',
            ], [
                'file.required' => 'Vui lòng chọn file.',
                'file.mimes' => 'File phải là định dạng Excel (.xlsx, .xls).',
            ]);

            // Đọc file Excel
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getPathname());

            // Lấy dữ liệu từ sheet đầu tiên
            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            // Các cột cần thiết
            $requiredColumns = ['name', 'price', 'unit', 'quantity', 'category'];
            $numRequiredColumns = count($requiredColumns);

            $headerRow = true;
            $errors = [];

            foreach ($sheetData as $index => $row) {
                // Bỏ qua dòng tiêu đề nếu có
                if ($headerRow) {
                    $headerRow = false;

                    // Kiểm tra xem header có nhiều hơn số cột yêu cầu không
                    if (count($row) > $numRequiredColumns) {
                        $errors[] = "File không đúng, vui lòng kiểm tra lại file của bạn.";
                        break;
                    }

                    continue;
                }

                // Kiểm tra nếu hàng hiện tại có nhiều cột hơn số cột cần thiết
                if (count($row) > $numRequiredColumns) {
                    $errors[] = "Dòng " . ($index + 1) . ": File không đúng, vui lòng kiểm tra lại file của bạn.";
                    continue;
                }

                // Thực hiện validation cho từng dòng
                $validator = Validator::make([
                    'name'     => $row[0],
                    'price'    => $row[1],
                    'unit'     => $row[2],
                    'quantity' => $row[3],
                    'category' => $row[4],
                ], [
                    'name'     => 'required|string|max:255',
                    'price'    => 'required|numeric|min:0',
                    'unit'     => 'required|string|max:50',
                    'quantity' => 'required|numeric|min:0',
                    'category' => 'required|string|max:100', // Quy tắc xác thực cho 'category'
                ]);

                // Kiểm tra nếu dữ liệu không hợp lệ
                if ($validator->fails()) {
                    $errors[] = "Dòng " . ($index + 1) . ": " . implode(', ', $validator->errors()->all());
                    continue;
                }

                // Kiểm tra trùng lặp trước khi thêm mới
                $existingIngredient = Ingredient::where('name', $row[0])
                    // ->where('price', $row[1])
                    // ->where('unit', $row[2])
                    ->first();

                if ($existingIngredient) {
                    $errors[] = "Dòng " . ($index + 1) . ": Nguyên liệu đã tồn tại.";
                    continue;
                }

                // Tạo nguyên liệu mới nếu dữ liệu hợp lệ
                $ingredient = Ingredient::create([
                    'name'     => $row[0],
                    'price'    => $row[1],
                    'unit'     => $row[2],
                    'category' => $row[4], // Thêm 'category'
                ]);

                // Thêm nguyên liệu vào bảng tồn kho (inventory_stock) với số lượng từ file Excel
                InventoryStock::create([
                    'ingredient_id'  => $ingredient->id, // Thêm dòng này để liên kết với bảng Ingredient
                    'name'           => $ingredient->name,
                    'unit'           => $ingredient->unit,
                    'quantity_stock' => $row[3], // Số lượng tồn kho từ file Excel
                    'last_update' => now(), // Thêm giá trị cho 'last_update'
                ]);
            }

            // Kiểm tra nếu có lỗi trong quá trình import
            if (!empty($errors)) {
                return redirect()->back()->withErrors(['errors' => $errors]);
            }
        });

        // Sau khi import thành công, quay lại trang danh sách nguyên liệu
        return redirect()->route('admin.ingredient.index')->with('success', 'Import nguyên liệu thành công!');
    }




    // Hiển thị form import
    public function showImportForm()
    {
        return view('admin.ingredientType.ingredient.import');
    }

    public function downloadTemplate()
    {
        // Tạo một file Excel mới
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Thiết lập tiêu đề cho các cột
        $sheet->setCellValue('A1', 'Name');      // Tên nguyên liệu
        $sheet->setCellValue('B1', 'Price');     // Giá
        $sheet->setCellValue('C1', 'Unit');      // Đơn vị
        $sheet->setCellValue('D1', 'Quantity');  // Số lượng
        $sheet->setCellValue('E1', 'Category');  // Phân loại

        // Thiết lập một số ví dụ dữ liệu mẫu (tùy chọn)
        $sheet->setCellValue('A2', 'Đường');
        $sheet->setCellValue('B2', '10000');      // Giá mẫu
        $sheet->setCellValue('C2', 'g');          // Đơn vị mẫu
        $sheet->setCellValue('D2', '500');        // Số lượng mẫu
        $sheet->setCellValue('E2', 'Đồ đóng hộp');     // Phân loại mẫu

        // Tạo writer và xuất file Excel
        $writer = new Xlsx($spreadsheet);
        $fileName = 'template_ingredients.xlsx';

        // Thiết lập header để download file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
