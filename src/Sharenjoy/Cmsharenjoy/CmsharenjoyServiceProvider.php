<?php namespace Sharenjoy\Cmsharenjoy;

use Illuminate\Support\ServiceProvider;
use Response, Session, Route, Request, Input;

class CmsharenjoyServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

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
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$app = $this->app;		

		$this->package('sharenjoy/cmsharenjoy');

		// Setting the locale langage
        if (Session::has('admin-locale'))
        {
            $app->setLocale(Session::get('admin-locale'));
        }

		// Define 404 page
		$app->missing(function($exception)
		{
		    return Response::view('cmsharenjoy::errors.missing', array(), 404);
		});

		// Get the URL segment to use for routing
        $urlSegment = $app['config']->get('cmsharenjoy::app.access_url');


		// Binding some repositroy
        $this->bindRepository($app);

        // Loading some of files
		$this->loadIncludes($urlSegment);
	}

	public function bindRepository($app)
	{
		// The Users Bindings
		$app->bind('Sharenjoy\Cmsharenjoy\User\UserInterface', function($app)
		{
		    return new User\UserRepository(
		        new User\User
		    );
		});

		// The Settings Bindings
		$app->bind('Sharenjoy\Cmsharenjoy\Settings\SettingsInterface', function($app)
		{
		    return new Settings\SettingsRepository(
		        new Settings\Settings
		    );
		});
	}

	/**
     * Include some specific files from the src-root.
     * @return void
     */
    protected function loadIncludes($urlSegment)
    {
        // Add file names without the `php` extension to this list as needed.
        $filesToLoad = array(
            'filters',
            'routes',
            'helpers',
        );

        // Run through $filesToLoad array.
        foreach ($filesToLoad as $file)
        {
            // Add needed database structure and file extension.
            $file = __DIR__ . '/../../' . $file . '.php';
            // If file exists, include.
            if (is_file($file)) include $file;
        }
    }

    /**
	 * Get the services provided by the provider.
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}