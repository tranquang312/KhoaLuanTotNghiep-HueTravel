<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {

        // if (!auth()->check() || !auth()->user()->can($permission)) {
        //     abort(403, 'Bạn không có quyền truy cập trang này.');
        // }

        // return $next($request);
        $user = $request->user();

        // Nếu là admin thì bỏ qua kiểm tra permission
        if ($user && ($user->hasRole('admin'))) {
            return $next($request);
        }

        // Nếu không phải admin, kiểm tra permission như bình thường
        if (!$user || !$user->can($permission)) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        return $next($request);
    }
}
