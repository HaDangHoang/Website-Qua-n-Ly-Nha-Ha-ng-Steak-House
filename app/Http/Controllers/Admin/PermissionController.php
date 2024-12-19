<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PermissionController extends Controller
{

    public function __construct()
    {
        // Gán middleware cho các phương thức
        $this->middleware('permission:Xem quyền hạn', ['only' => ['index']]);
        $this->middleware('permission:Tạo mới quyền hạn', ['only' => ['create']]);
        $this->middleware('permission:Sửa quyền hạn', ['only' => ['edit']]);
        $this->middleware('permission:Xóa quyền hạn', ['only' => ['destroy']]);
    }


    public function index(Request $request)
    {
        $title = 'Quyền Hạn';
        $permissions = Permission::query();

        // Tìm kiếm theo tên quyền hạn
        if ($search = $request->get('search')) {
            $permissions->where('name', 'like', '%' . $search . '%');
        }

        // Sắp xếp dữ liệu
        $sort = $request->get('sort', 'created_at'); // Mặc định sắp xếp theo 'created_at'
        $direction = $request->get('direction', 'desc'); // Mặc định sắp xếp giảm dần
        if (in_array($sort, ['id', 'name', 'created_at']) && in_array($direction, ['asc', 'desc'])) {
            $permissions->orderBy($sort, $direction);
        }

        // Phân trang kết quả
        $permissions = $permissions->paginate(10); // Giữ nguyên các tham số trong URL

        return view('admin.user.permissions.index', [
            'permissions' => $permissions,
            'title' => $title,
        ]);
    }



    public function create()
    {
        $title = 'Thêm Mới Quyền Hạn';
        return view('admin.user.permissions.create', compact('title'));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:permissions|min:3'
        ]);
        if ($validator->passes()) {
            Permission::create(['name' => $request->name]);
            return redirect()->route('admin.permissions.index')->with('success', 'Thêm Quyền hạn thành công');
        } else {
            return redirect()->route('admin.permissions.create')->withInput()->withErrors($validator);
        }
    }
    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        return view('admin.user.permissions.edit', [
            'permission' => $permission
        ]);
    }
    public function update($id, Request $request)
    {
        $permission = Permission::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|unique:permissions,name,' . $id . ',id'
        ]);
        if ($validator->passes()) {

            $permission->name = $request->name;
            $permission->save();
            return redirect()->route('admin.permissions.index')->with('success', 'Chỉnh sửa permission thành công');
        } else {
            return redirect()->route('admin.permissions.edit', $id)->withInput()->withErrors($validator);
        }
    }
    public function destroy($id)
    {
        try {
            // Tìm và xóa quyền hạn
            $permission = Permission::findOrFail($id);
            $permission->delete();
    
            // Chuyển hướng về trang danh sách quyền hạn sau khi xóa thành công
            return redirect()->route('admin.permissions.index')->with('success', 'Quyền hạn đã được xóa thành công!');
        } catch (\Exception $e) {
            // Trả về thông báo lỗi nếu không thể xóa
            return redirect()->route('admin.permissions.index')->with('error', 'Không thể xóa quyền hạn.');
        }
    }
    
}
