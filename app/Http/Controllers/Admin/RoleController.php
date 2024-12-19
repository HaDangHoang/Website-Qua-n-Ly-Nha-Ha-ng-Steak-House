<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class RoleController extends Controller
{



    public function __construct()
    {
        // Gán middleware cho các phương thức
        $this->middleware('permission:Xem vai trò', ['only' => ['index']]);
        $this->middleware('permission:Tạo mới vai trò', ['only' => ['create']]);
        $this->middleware('permission:Sửa vai trò', ['only' => ['edit']]);
        $this->middleware('permission:Xóa vai trò', ['only' => ['destroy']]);
    }

    protected $model = Role::class;
    protected $viewPath = 'admin.role';
    protected $routePath = 'admin.role';



    public function index()
    {
        $title = 'Vai Trò';
        $roles = Role::orderBy('name', 'ASC')->paginate(10);
        return view('admin.user.role.index', [
            'roles' => $roles,
            'title' => $title
        ]);
    }
    public function create()
    {
        $permissions = Permission::orderBy('name', 'DESC')->get();
        return view('admin.user.role.create', [
            'permissions' => $permissions
        ]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles|min:3'
        ]);
        if ($validator->passes()) {
            // dd($request->permission);
            $role = Role::create(['name' => $request->name]);

            if (!empty($request->permission)) {
                foreach ($request->permission as $name) {
                    $role->givePermissionTo($name);
                }
            }

            return redirect()->route('admin.role.index')->with('success', 'Thêm vai trò thành công');
        } else {
            return redirect()->route('admin.role.create')->withInput()->withErrors($validator);
        }
    }



    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $hasPermissions = $role->permissions->pluck('name');
        $permissions = Permission::orderBy('name', 'DESC')->get();
        // dd($hasPermissions);
        return view('admin.user.role.edit', [
            'permissions' => $permissions,
            'hasPermissions' => $hasPermissions,
            'role' => $role
        ]);
    }

    public function update($id, Request $request)
    {
        $role = Role::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name,' . $id . ',id'
        ]);
        if ($validator->passes()) {
            // dd($request->name);
            // $role = Role::create(['name' => $request->name]);

            $role->name = $request->name;
            $role->save();

            if (!empty($request->permission)) {
                $role->syncPermissions($request->permission);
            } else {
                $role->syncPermissions([]);
            }

            return redirect()->route('admin.role.index')->with('success', 'Cập nhật vai trò thành công');
        } else {
            return redirect()->route('admin.role.edit', $id)->withInput()->withErrors($validator);
        }
    }
    public function destroy($id)
    {
        $role = Role::find($id);
        if (!$role) {
            return redirect()->back()->with('error', 'Vai trò không tồn tại.');
        }

        if ($role->delete()) {
            return redirect()->back()->with('success', 'Xóa mềm thành công.');
        } else {
            return redirect()->back()->with('error', 'Không thể xóa.');
        }
    }


    public function trash()
    {
        $title = 'Khôi Phục Danh Sách Vai Trò';
        $roles = Role::onlyTrashed()->paginate(10);
        return view('admin.role.trash', compact('roles','title')); // Sửa 'role' thành 'roles'
    }

    public function restore($id)
    {
        $role = Role::withTrashed()->findOrFail($id);
        $role->restore();
        return redirect()->route('admin.role.trash')->with('success', 'đã được khôi phục thành công!');
    }

    public function forceDelete($id)
    {
        $role = Role::withTrashed()->findOrFail($id);
        $role->forceDelete();
        return redirect()->route('admin.role.trash')->with('success', ' đã được xóa vĩnh viễn!');
    }
}
