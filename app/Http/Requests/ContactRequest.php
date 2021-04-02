<?php


namespace App\Http\Requests;

use App\Rules\BetweenRule;
use App\Rules\BlacklistDomainRule;
use App\Rules\BlacklistEmailRule;
use App\Rules\EmailRule;

class ContactRequest extends Request
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$rules = [
			'first_name' => [ new BetweenRule(2, 100)],
			'last_name'  => [ new BetweenRule(2, 100)],
			'email'      => [ 'email', new EmailRule(), new BlacklistEmailRule(), new BlacklistDomainRule()],
			'message'    => ['required', new BetweenRule(0, 50000000000000000000)],
		];
		
		// reCAPTCHA
		$rules = $this->recaptchaRules($rules);
		
		return $rules;
	}
}
