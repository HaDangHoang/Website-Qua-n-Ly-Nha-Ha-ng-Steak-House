<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\TraitCRUD;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Imports\SupplierImport;
// use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class SupplierController extends Controller
{

    public function __construct()
    {
        // Gán middleware cho các phương thức
        $this->middleware('permission:Xem nhà cung cấp', ['only' => ['index']]);
        $this->middleware('permission:Tạo mới nhà cung cấp', ['only' => ['create']]);
        $this->middleware('permission:Sửa nhà cung cấp', ['only' => ['edit']]);
        $this->middleware('permission:Xóa nhà cung cấp', ['only' => ['destroy']]);
    }
    use TraitCRUD;

    protected $model = Supplier::class;
    protected $viewPath = 'suppliers';
    protected $routePath = 'suppliers';


    public function index(Request $request)
    {
        $title = 'Nhà Cung Cấp';

        // Lấy tham số tìm kiếm
        $suppliers = Supplier::when($request->name, function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->name . '%');
        });

        // Xử lý sắp xếp
        if ($request->has('sort') && $request->has('direction')) {
            $suppliers->orderBy($request->get('sort'), $request->get('direction'));
        } else {
            // Nếu không có tham số sắp xếp, mặc định sắp xếp theo ID giảm dần
            $suppliers->orderBy('id', 'desc');
        }

        // Phân trang kết quả
        $suppliers = $suppliers->paginate(10);

        return view('admin.ingredientType.supplier.index', compact('suppliers', 'title'));
    }




    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Thêm Mới Nhà Cung Cấp';
        return view('admin.ingredientType.supplier.create', compact('title'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        Supplier::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
        ]);


        return redirect()->route('admin.supplier.index')
            ->with('success', 'Nhà cung cấp đã được thêm thành công.');
    }

    public function edit($id)
    {
        $title = 'Chỉnh Sửa Nhà Cung Cấp';
        $supplier = Supplier::findOrFail($id);
        return view('admin.ingredientType.supplier.edit', compact('supplier', 'title'));
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        $supplier = Supplier::findOrFail($id);
        $supplier->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
        ]);

        return redirect()->route('admin.supplier.index')->with('success', 'Cập nhật nhà cung cấp thành công.');
    }


    public function destroy($id)
    {
        if (!isset($this->model)) {
            abort(500, 'Model is not defined.');
        }

        $item = $this->model::findOrFail($id);
        $item->delete();

        return redirect()->route('admin.supplier.index')->with('success', 'Xóa thành công.');
    }

    public function showImportForm()
    {
        return view('admin.ingredientType.supplier.import');
    }
    public function import(Request $request)
    {
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

        // Định nghĩa các cột cần thiết
        $requiredColumns = ['name', 'phone', 'email', 'address'];
        $numRequiredColumns = count($requiredColumns);

        $headerRow = true;
        $errors = [];

        foreach ($sheetData as $index => $row) {
            // Kiểm tra số lượng cột trong dòng đầu tiên (giả định dòng đầu tiên là header)
            if ($headerRow) {
                $headerRow = false;

                // Nếu số lượng cột không khớp, thông báo lỗi và dừng lại
                if (count($row) !== $numRequiredColumns) {
                    $errors[] = "File không đúng, vui lòng kiểm tra lại file của bạn. Số cột yêu cầu: " . $numRequiredColumns;
                    break;
                }

                continue;
            }

            // Nếu số lượng cột không khớp, thêm thông báo lỗi cho dòng hiện tại
            if (count($row) !== $numRequiredColumns) {
                $errors[] = "Dòng " . ($index + 1) . ": Không đúng số cột. Yêu cầu " . $numRequiredColumns . " cột.";
                continue;
            }

            // Thực hiện validation cho từng dòng
            $validator = Validator::make([
                'name'    => $row[0],
                'phone'   => $row[1],
                'email'   => $row[2],
                'address' => $row[3],
            ], [
                'name'    => 'required|string|max:255',
                'phone'   => 'required|numeric|digits_between:10,15',
                'email'   => 'required|email|unique:suppliers,email',
                'address' => 'nullable|string|max:255',
            ]);

            // Kiểm tra nếu dữ liệu không hợp lệ
            if ($validator->fails()) {
                $errors[] = "Dòng " . ($index + 1) . ": " . implode(', ', $validator->errors()->all());
                continue;
            }

            // Kiểm tra trùng lặp dựa trên 'name' và 'email'
            $existingSupplier = Supplier::where('name', $row[0])
                ->where('email', $row[2])
                ->first();

            if ($existingSupplier) {
                $errors[] = "Dòng " . ($index + 1) . ": Nhà cung cấp đã tồn tại.";
                continue;
            }

            // Nếu dữ liệu hợp lệ và không bị trùng, tạo nhà cung cấp mới
            Supplier::create([
                'name'    => $row[0],
                'phone'   => $row[1],
                'email'   => $row[2],
                'address' => $row[3],
            ]);
        }

        // Kiểm tra nếu có lỗi trong quá trình import
        if (!empty($errors)) {
            return redirect()->back()->withErrors(['errors' => $errors]);
        }

        // Sau khi import thành công, quay lại trang danh sách nhà cung cấp
        return redirect()->route('admin.supplier.index')->with('success', 'Import thành công!');
    }


    public function downloadTemplate()
    {
        // Tạo một file Excel mới
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Thiết lập tiêu đề cho các cột
        $sheet->setCellValue('A1', 'Name');     // Tên nhà cung cấp
        $sheet->setCellValue('B1', 'Phone');    // Số điện thoại
        $sheet->setCellValue('C1', 'Email');    // Email
        $sheet->setCellValue('D1', 'Address');  // Địa chỉ

        // Thiết lập một số ví dụ dữ liệu mẫu (tùy chọn)
        $sheet->setCellValue('A2', 'Công ty ABC');
        $sheet->setCellValue('B2', '0123456789');
        $sheet->setCellValue('C2', 'abc@example.com');
        $sheet->setCellValue('D2', '123 Đường A, Quận B');

        // Tạo writer và xuất file Excel
        $writer = new Xlsx($spreadsheet);
        $fileName = 'template_suppliers.xlsx';

        // Thiết lập header để download file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
