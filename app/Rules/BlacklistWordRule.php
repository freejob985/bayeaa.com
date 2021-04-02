<?php


namespace App\Rules;

use App\Models\Blacklist;
use Illuminate\Contracts\Validation\Rule;

class BlacklistWordRule implements Rule
{
	/**
	 * Determine if the validation rule passes.
	 *
	 * @param  string  $attribute
	 * @param  mixed  $value
	 * @return bool
	 */
	public function passes($attribute, $value)
	{
		$value = trim(mb_strtolower($value));
		// $words = Blacklist::ofType('word')->get();
		$words = Blacklist::whereIn('type', ['word', 'domain', 'email'])->get();
		if ($words->count() > 0) {
			foreach ($words as $word) {
				// Check if a ban's word is contained in the user entry
				$startPatten = '\s\-.,;:=/#\|_<>';
				$endPatten = $startPatten . 's';
				try {
					if (preg_match('|[' . $startPatten . '\\\]+' . $word->entry . '[' . $endPatten . '\\\]+|ui', ' ' . $value . ' ')) {
						return false;
					}
				} catch (\Exception $e) {
					if (preg_match('|[' . $startPatten . ']+' . $word->entry . '[' . $endPatten . ']+|ui', ' ' . $value . ' ')) {
						return false;
					}
				}
			}
		}
		
		return true;
	}
	
	/**
	 * Get the validation error message.
	 *
	 * @return string
	 */
	public function message()
	{
		return trans('validation.blacklist_word_rule');
	}
}
