<?php 

namespace Kalani\TableSchemaCollector;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;

class TableSchemaCollectorServiceProvider extends ServiceProvider 
{
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
		$this->app['table-schema-collector'] = $this->app->share(function($app)
		{
			return new TableSchemaCollector($app['db']);
		});

		$this->app->booting(function()
		{
			$loader = \Illuminate\Foundation\AliasLoader::getInstance();
			$loader->alias('TableSchemaCollector', 
				'Kalani\TableSchemaCollector\Facades\TableSchemaCollector');
		});		
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('TableSchemaCollector');
	}

}

