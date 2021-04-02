<?php


namespace App\Providers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Cached\CachedAdapter;
use League\Flysystem\Cached\Storage\Memory;
use League\Flysystem\Filesystem;

class CachedS3ServiceProvider extends ServiceProvider
{
	/**
	 * Register bindings in the container.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}
	
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		Storage::extend('s3-cached', function ($app, $config) {
			$adapter = $app['filesystem']->createS3Driver($config);
			
			$adapter = $adapter->getDriver()->getAdapter();
			
			// return new Filesystem($adapter);
			$store = new Memory();
			return new Filesystem(new CachedAdapter($adapter, $store));
		});
	}
}
