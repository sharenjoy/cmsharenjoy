<?php

namespace Sharenjoy\Cmsharenjoy\Http\Middleware;

use Closure;

class Authenticate
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if (! auth()->guard('admin')->check() || auth()->guard('admin')->user()->activated == false) {
			if ($request->ajax()) {
				return response('Unauthorized.', 401);
			} else {
				auth()->guard('admin')->logout();
				return redirect()->guest($request->session()->get('accessUrl').'/login');
			}
		}

		$user = auth()->guard('admin')->user();
		session()->put('user', $user->toArray());
        view()->share('user', $user->toArray());

		return $next($request);
	}

}
