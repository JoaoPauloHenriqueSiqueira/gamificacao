<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use App\Role\UserCheck;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

/**
 * Class CheckActiveUserRole
 * @package App\Http\Middleware
 */
class CheckActiveUserRole
{
    protected $userCheck;
    public function __construct(UserCheck $userCheck)
    {
        $this->userCheck = $userCheck;
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
        /** @var User $user */

        $user = Auth::guard()->user();
        

        if (!$this->userCheck->check($user)) {
            return redirect()->route('active', [$user]);
        }

        session(['company' => Auth::user()->company_id]);

        return $next($request);
    }
}
