<?php


namespace App\Observers;

use App\Models\FieldOption;
use App\Models\PostValue;

class FieldOptionObserver extends TranslatedModelObserver
{
	/**
	 * Listen to the Entry deleting event.
	 *
	 * @param FieldOption $fieldOption
	 * @return void
	 */
	public function deleting($fieldOption)
	{
		parent::deleting($fieldOption);
		
		// Delete all translated entries
		$fieldOption->translated()->delete();
		
		// Delete all Posts Custom Field's Values
		$postValues = PostValue::where('value', $fieldOption->id)->get();
		if ($postValues->count() > 0) {
			foreach ($postValues as $value) {
				$value->delete();
			}
		}
	}
}
