<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\FlashMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use FlashMessage;

    public function index()
    {
        $users = User::with('roles')->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Gán roles cho user
        $roles = Role::whereIn('id', $request->roles)->get();
        $user->syncRoles($roles);

        $this->flashSuccess('Người dùng đã được tạo thành công.');
        return redirect()->route('admin.users.index');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id'
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        // Chỉ cập nhật password nếu được cung cấp
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        // Cập nhật roles cho user
        $roles = Role::whereIn('id', $request->roles)->get();
        $user->syncRoles($roles);

        $this->flashSuccess('Người dùng đã được cập nhật thành công.');
        return redirect()->route('admin.users.index');
    }

    public function destroy(User $user)
    {
        // Không cho phép xóa chính mình
        if ($user->id === auth()->id()) {
            $this->flashError('Không thể xóa tài khoản của chính mình.');
            return redirect()->route('admin.users.index');
        }

        $user->delete();

        $this->flashSuccess('Người dùng đã được xóa thành công.');
        return redirect()->route('admin.users.index');
    }
}
