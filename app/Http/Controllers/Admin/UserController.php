<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;


class UserController extends Controller
{

    public function __construct()
    {
        // Gán middleware cho các phương thức
        $this->middleware('permission:Xem người dùng', ['only' => ['index']]);
        $this->middleware('permission:Tạo mới người dùng', ['only' => ['create']]);
        $this->middleware('permission:Sửa người dùng', ['only' => ['edit']]);
        $this->middleware('permission:Xóa người dùng', ['only' => ['destroy']]);

    }

     /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::whereDoesntHave('roles'); // Những người dùng không có vai trò
    
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }
    
        $users = $query->latest()->paginate(10);
    
        return view('admin.user.index', [
            'users' => $users,
            'type' => 'user',  // Điều này đảm bảo view sẽ biết đang hiển thị người dùng
            'request' => $request
        ]);
    }
    
    public function employeeList(Request $request)
    {
        $query = User::whereHas('roles'); // Những người dùng có vai trò
    
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%')
                  ->orWhereHas('roles', function($roleQuery) use ($search) {
                      $roleQuery->where('name', 'like', '%' . $search . '%');
                  });
            });
        }
    
        $users = $query->latest()->paginate(10);
    
        return view('admin.user.index', [
            'users' => $users,
            'type' => 'employee',  
            'request' => $request
        ]);
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
{
    $type = $request->type ?? 'user'; // Mặc định là 'user' nếu không truyền type

    $roles = $type === 'employee' ? Role::orderBy('name', 'ASC')->get() : collect(); // Chỉ lấy vai trò nếu là nhân viên

    return view('admin.user.create', [
        'roles' => $roles,
        'type' => $type,
    ]);
}

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|digits_between:10,15',
            'date_of_birth' => 'nullable|date', 
            'gender' => 'nullable|in:male,female,other', 
            'hire_date' => 'nullable|date',
            'password' => 'required|min:5|same:confirm_password',
            'confirm_password' => 'required',
        ]);
    
        if ($validator->fails()) {
            return redirect()->route('admin.user.create', ['type' => $request->type])
                             ->withInput()->withErrors($validator);
        }
    
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->date_of_birth = $request->date_of_birth;
        $user->gender = $request->gender;
        $user->hire_date = $request->hire_date;
        $user->password = Hash::make($request->password);
        $user->save();
    
        // Chỉ gán vai trò nếu là nhân viên
        if ($request->type === 'employee' && $request->role) {
            $user->syncRoles($request->role);
        }
    
        // Redirect về trang chi tiết người dùng vừa tạo
        return redirect()->route('admin.user.show', $user->id)->with('success', 'Thêm mới thành công');
    }
    
    
    
    


    /**
     * Display the specified resource.
     */
    public function show($id)
{
    // Lấy thông tin người dùng kèm vai trò
    $user = User::with('roles')->findOrFail($id); 

    // Kiểm tra xem người dùng có vai trò nhân viên hay không
    $type = $user->roles->isEmpty() ? 'user' : 'employee'; 

    return view('admin.user.show', compact('user', 'type'));
}


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, Request $request)
    {
        $type = $request->query('type', 'user');
        $user = User::findOrFail($id);
    
        $roles = $type === 'employee' ? Role::orderBy('name', 'ASC')->get() : collect();
        $hasRoles = $type === 'employee' ? $user->roles->pluck('id') : collect();
    
        return view('admin.user.edit', [
            'user' => $user,
            'roles' => $roles,
            'hasRoles' => $hasRoles,
            'type' => $type,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
    
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|digits_between:10,15',
        ]);
    
        if ($validator->fails()) {
            return redirect()->route('admin.user.edit', [
                'user' => $id, // Đúng tên tham số như định nghĩa route
                'type' => $request->type, // Truyền type để quay lại đúng tab
            ])
            ->withInput()
            ->withErrors($validator);
            
        }
    
        // Cập nhật thông tin người dùng
        $user->name = $request->name;
        if ($request->email != $user->email) {
            $user->email = $request->email;
        }
        $user->phone = $request->phone;
        $user->save();
    
        $user->syncRoles($request->role);
    
        // Chuyển hướng về trang chi tiết người dùng sau khi sửa
        return redirect()->route('admin.user.show', $user->id)->with('success', 'Chỉnh sửa thành công!');
    }
    
    
    
    


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
{
    // Tìm người dùng theo ID
    $user = User::findOrFail($id);

    // Kiểm tra xem người dùng có quyền hay không
    if (!auth()->user()->can('Xóa người dùng')) {
        return redirect()->route('admin.user.index')
            ->with('error', 'Bạn không có quyền xóa người dùng.');
    }

    // Nếu người dùng có vai trò (employee), xóa vai trò của họ trước
    if ($user->roles()->exists()) {
        $user->roles()->detach();
    }

    // Thực hiện xóa người dùng
    $user->delete();    

    // Redirect về danh sách người dùng
    return redirect()->route('admin.user.index')->with('success', 'Người dùng đã được xóa thành công.');
}

}
