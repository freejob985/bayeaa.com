<?php


namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class LocaleOfLanguageRule implements Rule
{
	public $langCode = null;
	
	public function __construct($langCode)
	{
		$this->langCode = $langCode;
	}
	
	/**
	 * Determine if the validation rule passes.
	 * Check the Locale related to the Language Code.
	 *
	 * @param  string  $attribute
	 * @param  mixed  $value
	 * @return bool
	 */
	public function passes($attribute, $value)
	{
		$langCode = $this->langCode;
		$locales = (array)config('locales');
		
		$filtered = collect($locales)->filter(function ($item, $key) use ($langCode) {
			return Str::startsWith($key, $langCode);
		});
		
		if ($filtered->isNotEmpty()) {
			return Str::startsWith($value, $langCode);
		}
		
		return isset($locales[$value]);
	}
	
	/**
	 * Get the validation error message.
	 *
	 * @return string
	 */
	public function message()
	{
		return trans('validation.locale_of_language_rule');
	}
}
