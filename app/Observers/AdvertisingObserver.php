<?php


namespace App\Observers;

use App\Models\Advertising;
use Illuminate\Support\Facades\Cache;

class AdvertisingObserver
{
	/**
	 * Listen to the Entry saved event.
	 *
	 * @param Advertising $advertising
	 * @return void
	 */
	public function saved(Advertising $advertising)
	{
		// Removing Entries from the Cache
		$this->clearCache($advertising);
	}
	
	/**
	 * Listen to the Entry deleted event.
	 *
	 * @param Advertising $advertising
	 * @return void
	 */
	public function deleted(Advertising $advertising)
	{
		// Removing Entries from the Cache
		$this->clearCache($advertising);
	}
	
	/**
	 * Removing the Entity's Entries from the Cache
	 *
	 * @param $advertising
	 */
	private function clearCache($advertising)
	{
		Cache::forget('advertising.top');
		Cache::forget('advertising.bottom');
		Cache::forget('advertising.auto');
	}
}
