<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use App\Role\AdminCheck;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

/**
 * Class CheckUserAdminRole
 * @package App\Http\Middleware
 */
class CheckUserAdminRole
{
    /**
     * @var AdminCheck
     */
    protected $adminCheck;

    public function __construct(AdminCheck $adminCheck)
    {
        $this->adminCheck = $adminCheck;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string $role
     * @return mixed
     * @throws AuthorizationException
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::guard()->user();

        if (!$this->adminCheck->check($user)) {
            return redirect()->route('home_user', [$user]);
        }

        return $next($request);
    }
}
