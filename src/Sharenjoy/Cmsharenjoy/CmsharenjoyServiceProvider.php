<?php namespace Sharenjoy\Cmsharenjoy;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Session, Request;

class CmsharenjoyServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

    /**
     * This is the variable which end
     * @var string
     */
    protected $whichEnd;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register(){}

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('sharenjoy/cmsharenjoy');

		// Setting the locale langage
        if (Session::has('sharenjoy.backEndLanguage'))
        {
            $this->app->setLocale(Session::get('sharenjoy.backEndLanguage'));
        }

        // To define which end it is now
        $this->whichEnd = Request::segment(1) == 'admin' ? 'backEnd' : 'frontEnd';
        Session::set('sharenjoy.whichEnd', $this->whichEnd);

		// Get the URL segment to use for routing
        $accessUrl = $this->app['config']->get('cmsharenjoy::app.access_url');

		// Binding a bunch of handler
        $this->bindHandler();

        // Make some alias
        $this->makeAlias();

        // Loading some of files
		$this->loadIncludes($accessUrl);
	}

	protected function bindHandler()
	{
		// The Users Binding
		$this->app->bindShared('Sharenjoy\Cmsharenjoy\User\UserInterface', function()
		{
		    return new User\UserHandler(new User\User, new User\UserValidator);
		});

		// The Setting Bindings
		$this->app->bindShared('Sharenjoy\Cmsharenjoy\Setting\SettingInterface', function()
		{
		    return new Setting\SettingHandler(new Setting\Setting, new Setting\SettingValidator);
		});

        // The parser binding
        $this->app->bindShared('Sharenjoy\Cmsharenjoy\Utilities\Parser', function()
        {
            return new Utilities\Parser;
        });

        // The Filer binding
        $driver = $this->app['config']->get('cmsharenjoy::filer.driver');
        
        $this->app->bindShared('Sharenjoy\Cmsharenjoy\Filer\FilerInterface', function() use ($driver)
        {
            switch ($driver)
            {
                case 'file':
                    return new Filer\FilerHandler;                  
                
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
    protected function loadIncludes($accessUrl)
    {
        // Add file names without the `php` extension to this list as needed.
        $filesToLoad = [
            'helpers',
            'routes',
            'filters',
            'events'
        ];

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