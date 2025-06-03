<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
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

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Người dùng đã được tạo thành công.');
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

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Người dùng đã được cập nhật thành công.');
    }

    public function destroy(User $user)
    {
        // Không cho phép xóa chính mình
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Không thể xóa tài khoản của chính mình.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Người dùng đã được xóa thành công.');
    }
}
