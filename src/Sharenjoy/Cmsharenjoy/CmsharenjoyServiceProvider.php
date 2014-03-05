<?php namespace Sharenjoy\Cmsharenjoy;

use Illuminate\Support\ServiceProvider;
use App;

class CmsharenjoyServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		// $app = $this->app;
		
		$this->package('sharenjoy/cmsharenjoy');

		// Get the URL segment to use for routing
        $urlSegment = $this->app['config']->get('cmsharenjoy::app.access_url');

		// Do some routing here specific to this package
		include __DIR__.'/../../routes.php'; 

		// Include IOC Bindings
		include __DIR__.'/../../bindings.php';
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}