<?php namespace Sharenjoy\Cmsharenjoy\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel {

	/**
	 * The application's global HTTP middleware stack.
	 *
	 * @var array
	 */
	protected $middleware = [
		'Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode',
		'Illuminate\Cookie\Middleware\EncryptCookies',
		'Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse',
		'Illuminate\Session\Middleware\StartSession',
		'Illuminate\View\Middleware\ShareErrorsFromSession',
		'Sharenjoy\Cmsharenjoy\Http\Middleware\VerifyCsrfToken',
	];

	/**
	 * The application's route middleware.
	 *
	 * @var array
	 */
	protected $routeMiddleware = [
		'auth' => 'Sharenjoy\Cmsharenjoy\Http\Middleware\Authenticate',
		'auth.basic' => 'Illuminate\Auth\Middleware\AuthenticateWithBasicAuth',
		'guest' => 'Sharenjoy\Cmsharenjoy\Http\Middleware\RedirectIfAuthenticated',

		// for backend
		'admin.auth' => 'Sharenjoy\Cmsharenjoy\Http\Middleware\Admin\Authenticate',
		'admin.guest' => 'Sharenjoy\Cmsharenjoy\Http\Middleware\Admin\RedirectIfAuthenticated',
	];

}
