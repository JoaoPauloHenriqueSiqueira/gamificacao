<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use App\Role\AdminCheck;
use App\Role\CompanyCheck;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

/**
 * Class CheckActiveCompanyRole
 * @package App\Http\Middleware
 */
class CheckActiveCompanyRole
{
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
        $company = session()->get('company');

        if(!$company){
            session(['company' => Auth::user()->company_id]);
        }

        return $next($request);
    }
}
