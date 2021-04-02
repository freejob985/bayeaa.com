<?php


namespace App\Rules;

use App\Models\Currency;
use Illuminate\Contracts\Validation\Rule;

class CurrenciesCodesAreValidRule implements Rule
{
	/**
	 * Determine if the validation rule passes.
	 * Check if each the Currency Code in the list is valid.
	 *
	 * @param  string  $attribute
	 * @param  mixed  $value
	 * @return bool
	 */
	public function passes($attribute, $value)
	{
		$valid = true;
		
		$currenciesCodes = explode(',', $value);
		if (!empty($currenciesCodes)) {
			foreach($currenciesCodes as $code) {
				if (Currency::where('code', $code)->count() <= 0) {
					$valid = false;
					break;
				}
			}
		}
		
		return $valid;
	}
	
	/**
	 * Get the validation error message.
	 *
	 * @return string
	 */
	public function message()
	{
		return trans('validation.currencies_codes_are_valid_rule');
	}
}
