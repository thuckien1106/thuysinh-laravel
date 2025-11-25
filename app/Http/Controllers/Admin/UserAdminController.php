<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserAdminController extends Controller
{
    public function index()
    {
        $users = User::orderByDesc('id')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role' => 'in:admin,user',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        // Tạo username từ email (phần trước @)
        $validated['username'] = explode('@', $validated['email'])[0];
        $user = User::create($validated);

        // Tạo record trong bảng customers để liên kết với user
        DB::table('customers')->updateOrInsert(
            ['email' => $validated['email']],
            [
                'full_name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => null,
                'address' => null,
            ]
        );

        return redirect()->route('admin.users.index')->with('success', 'Người dùng đã được thêm!');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'nullable|min:6|confirmed',
            'role' => 'in:admin,user',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Cập nhật username từ email nếu email thay đổi
        $validated['username'] = explode('@', $validated['email'])[0];

        $user->update($validated);
        
        // Cập nhật record trong bảng customers
        DB::table('customers')->updateOrInsert(
            ['email' => $validated['email']],
            [
                'full_name' => $validated['name'],
                'email' => $validated['email'],
            ]
        );
        
        // Cập nhật lại session nếu user hiện tại đang đăng nhập
        if (session('admin') && session('admin')->id == $id) {
            $userFresh = User::find($id);
            session(['admin' => $userFresh]);
        }
        
        return redirect()->route('admin.users.index')->with('success', 'Người dùng đã được cập nhật!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return back()->with('success', 'Người dùng đã được xóa!');
    }
}
