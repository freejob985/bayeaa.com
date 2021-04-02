<?php


namespace App\Providers;

use Illuminate\Support\Facades\Storage;
use League\Flysystem\Cached\CachedAdapter;
use League\Flysystem\Cached\Storage\Memory;
use League\Flysystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Mhetreramesh\Flysystem\BackblazeAdapter;
use BackblazeB2\Client as BackblazeClient;
use Spatie\FlysystemDropbox\DropboxAdapter;

class BackblazeServiceProvider extends ServiceProvider
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
	 * Perform post-registration booting of services.
	 *
	 * @return void
	 */
	public function boot()
	{
		Storage::extend('backblaze', function ($app, $config) {
			$client = new BackblazeClient($config['account_id'], $config['application_key']);
			
			$adapter = new BackblazeAdapter($client, $config['bucket']);
			
			// return new Filesystem($adapter);
			$store = new Memory();
			return new Filesystem(new CachedAdapter($adapter, $store));
		});
	}
}
