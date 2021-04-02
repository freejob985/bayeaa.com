<?php


namespace App\Http\Controllers\Install\Traits\Install\Db;

use Illuminate\Support\Facades\Artisan;

/*
 * NOTE: THIS IS UNUSED WAITING ONE LARAVEL'S MIGRATION SYSTEM UPDATE
 * For now it's not possible to rollback a specific Laravel migration (very important for the plugins migrations).
 */
trait MigrationsTrait
{
	/**
	 * Import from Laravel Migrations
	 * php artisan migrate --force
	 */
	protected function importFromMigrations()
	{
		Artisan::call('migrate', ['--force' => true]);
	}
	
	/**
	 * Import from Laravel Seeders
	 * php artisan db:seed --force
	 */
	protected function importFromSeeders()
	{
		Artisan::call('db:seed', ['--force' => true]);
	}
}
