<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class BackupClear extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'clearBackup:exec';
	
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Clear backups of the website';
	
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		// Set the backup type configurations
		setBackupConfig();
		
		// Start the backup process
		try {
			Artisan::call('backup:clean');
		} catch (\Exception $e) {
			dd($e->getMessage());
		}
	}
}
