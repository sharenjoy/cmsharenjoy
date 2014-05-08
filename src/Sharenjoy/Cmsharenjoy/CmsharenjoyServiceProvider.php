<?php namespace Sharenjoy\Cmsharenjoy;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
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

        $config = $this->app['config'];

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
        $urlSegment = $config->get('cmsharenjoy::app.access_url');


		// Binding some repositroy
        $this->bindRepository($app, $config);

        // Make some alias
        $this->makeAlias();

        // Loading some of files
		$this->loadIncludes($urlSegment);
	}

	protected function bindRepository($app, $config)
	{
		// The Users Bindings
		$app->bind('Sharenjoy\Cmsharenjoy\User\UserInterface', function($app)
		{
		    return new User\UserRepository(
		        new User\User,
                new User\UserValidator($app['validator']),
                new User\AccountValidator($app['validator'])
		    );
		});

		// The Setting Bindings
		$app->bind('Sharenjoy\Cmsharenjoy\Setting\SettingInterface', function($app)
		{
		    return new Setting\SettingRepository(
		        new Setting\Setting,
                new Setting\SettingValidator($app['validator'])
		    );
		});

        // The parser binding
        $app->bind('Sharenjoy\Cmsharenjoy\Utilities\Parser', function($app)
        {
            return new Utilities\Parser;
        });

        // The Filer binding
        $driver = $config->get('cmsharenjoy::filer.driver');
        
        $app->bind('Sharenjoy\Cmsharenjoy\Filer\FilerInterface', function($app) use ($driver)
        {
            switch ($driver)
            {
                case 'file':
                    return new Filer\Filer;                  
                
                default:
                    throw new \InvalidArgumentException('Invalid file driver.');
            }
        });
	}

    /**
     * There are some useful alias
     * @return void
     */
    protected function makeAlias()
    {
        // For setting
        AliasLoader::getInstance()->alias('Setting', 'Sharenjoy\Cmsharenjoy\Setting\Facades\Setting');

        // For filer
        AliasLoader::getInstance()->alias('Filer', 'Sharenjoy\Cmsharenjoy\Filer\Facades\Filer');
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