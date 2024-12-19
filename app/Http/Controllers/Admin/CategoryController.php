<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\TraitCRUD;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CategoryController extends Controller
{

    public function __construct()
    {
        // Gán middleware cho các phương thức
        $this->middleware('permission:Xem danh mục', ['only' => ['index']]);
        $this->middleware('permission:Tạo mới danh mục', ['only' => ['create']]);
        $this->middleware('permission:Sửa danh mục', ['only' => ['edit']]);
        $this->middleware('permission:Xóa danh mục', ['only' => ['destroy']]);
    }


    use TraitCRUD;

    protected $model = Category::class;
    protected $viewPath = 'admin.dish.category';
    protected $routePath = 'admin.category';

    public function index(Request $request)
    {
        $query = Category::query();
        $title = 'Thực Đơn';

        // Lọc theo tên category
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Lấy danh sách categories với phân trang
        $categories = $query->latest()->paginate(10);

        // Trả về view với dữ liệu
        return view('admin.dish.category.index', [
            'categories' => $categories,
            'title' => $title
        ]);
    }


    public function create()
    {
        $title = 'Thêm Mới Danh Mục Thực Đơn';
        return view('admin.dish.category.create', compact('title'));
    }

    public function store(Request $request)
    {
        // Kiểm tra tên danh mục có bị trùng không
        $request->validate([
            'name' => 'required|unique:categories,name',  // Kiểm tra tính duy nhất của tên danh mục
            'description' => 'nullable|max:1000',
        ], [
            'name.unique' => 'Tên danh mục đã tồn tại. Vui lòng nhập tên khác.'
        ]);

        // Thêm danh mục mới
        Category::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        // Quay lại trang danh sách danh mục với thông báo thành công
        return redirect()->route('admin.category.index')->with('success', 'Danh mục đã được thêm thành công!');
    }



    public function edit($id)
    {
        $title = 'Chỉnh Sửa Danh Mục Thực Đơn';
        $category = Category::findOrFail($id);

        return view('admin.dish.category.edit', compact('category', 'title'));
    }

    public function update(Request $request, $id)
    {
        // Kiểm tra xem tên danh mục đã tồn tại hay chưa (ngoại trừ danh mục hiện tại)
        $request->validate([
            'name' => 'required|unique:categories,name,' . $id,
            'description' => 'nullable',  // Nếu mô tả không bắt buộc, thì chỉ cần nullable
        ], [
            'name.unique' => 'Tên danh mục đã tồn tại, vui lòng chọn tên khác.'
        ]);

        $category = Category::findOrFail($id);
        $category->update($request->all());

        return redirect()->route('admin.category.index')->with('success', 'Danh mục đã được cập nhật thành công!');
    }


    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // Kiểm tra xem danh mục có món ăn nào không
        if ($category->dishes()->count() > 0) {
            // Nếu có món ăn, trả về thông báo lỗi
            return redirect()->route('admin.category.index')->with('error', 'Không thể xóa danh mục này vì vẫn còn món ăn thuộc danh mục.');
        }

        // Nếu không có món ăn, tiến hành xóa mềm
        $category->delete();

        return redirect()->route('admin.category.index')->with('success', 'Danh mục đã được xóa mềm thành công!');
    }

    // Hiển thị thùng rác
    public function trash()
    {
        $title = 'Khôi Phục Danh Mục Thực Đơn';
        $categories = Category::onlyTrashed()->paginate(10); // Lấy ra các danh mục đã bị xóa mềm

        return view('admin.dish.category.trash', compact('categories','title'));
    }

    // Khôi phục
    public function restore($id)
    {
        $category = Category::withTrashed()->findOrFail($id); // Lấy danh mục từ thùng rác
        $category->restore(); // Khôi phục danh mục

        return redirect()->route('admin.category.trash')->with('success', 'Danh mục đã được khôi phục thành công!');
    }

    // Xóa vĩnh viễn
    public function forceDelete($id)
    {
        $category = Category::withTrashed()->findOrFail($id); // Lấy danh mục từ thùng rác
        $category->forceDelete(); // Xóa vĩnh viễn

        return redirect()->route('admin.category.trash')->with('success', 'Danh mụcđã được xóa vĩnh viễn!');
    }
}
