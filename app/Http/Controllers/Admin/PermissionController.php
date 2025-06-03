<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        return view('admin.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions',
            'description' => 'nullable|string|max:255'
        ]);

        Permission::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', 'Quyền hạn đã được tạo thành công.');
    }

    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            'description' => 'nullable|string|max:255'
        ]);

        $permission->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', 'Quyền hạn đã được cập nhật thành công.');
    }

    public function destroy(Permission $permission)
    {
        if ($permission->roles()->count() > 0) {
            return redirect()
                ->route('admin.permissions.index')
                ->with('error', 'Không thể xóa quyền hạn này vì đang được sử dụng bởi các vai trò.');
        }

        $permission->delete();

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', 'Quyền hạn đã được xóa thành công.');
    }
}
