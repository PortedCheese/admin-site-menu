<?php

namespace PortedCheese\AdminSiteMenu\Http\Middleware;

use App\Menu;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Gate;

class ManagementMenu
{
    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|mixed
     * @throws AuthenticationException
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
        if (! $request->user()) {
            return redirect('login');
        }
        if (! $request->user()->can("viewAny", Menu::class)) {
            abort(403, trans('Forbidden.'));
        }
        return $next($request);
    }
}
