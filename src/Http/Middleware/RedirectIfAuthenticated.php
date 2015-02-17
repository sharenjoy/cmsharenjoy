<?php namespace Sharenjoy\Cmsharenjoy\Http\Middleware;

use Closure, Sentry;

class RedirectIfAuthenticated {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if (Sentry::check())
		{
			return redirect($request->session()->get('accessUrl'));
		}

		return $next($request);
	}

}
