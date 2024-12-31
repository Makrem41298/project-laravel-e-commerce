<?php

namespace Spatie\Permission\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Guard;

class PermissionMiddleware
{
    public function handle($request, Closure $next, $permission, $guard = null)
    {
        $authGuard = Auth::guard($guard);

        $user = $authGuard->user();

        // For machine-to-machine Passport clients
        if (! $user && $request->bearerToken() && config('permission.use_passport_client_credentials')) {
            $user = Guard::getPassportClient($guard);
        }

        if (! $user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User is not logged in.',
            ], 401); // 401 Unauthorized
        }

        if (! method_exists($user, 'hasAnyPermission')) {
            return response()->json([
                'status' => 'error',
                'message' => 'The User model does not use the HasPermissions trait.',
            ], 403); // 403 Forbidden
        }

        $permissions = is_array($permission)
            ? $permission
            : explode('|', $permission);

        if (! $user->canAny($permissions)) {
            return response()->json([
                'status' => 'error',
                'message' => 'User does not have the required permissions.',
                'required_permissions' => $permissions,
            ], 403); // 403 Forbidden
        }

        return $next($request);
    }

    /**
     * Specify the permission and guard for the middleware.
     *
     * @param  array|string  $permission
     * @param  string|null  $guard
     * @return string
     */
    public static function using($permission, $guard = null)
    {
        $permissionString = is_string($permission) ? $permission : implode('|', $permission);
        $args = is_null($guard) ? $permissionString : "$permissionString,$guard";

        return static::class.':'.$args;
    }
}
