<?php


namespace App\Http\Requests;

use App\Rules\BetweenRule;
use App\Rules\EmailRule;

class SendMessageRequest extends Request
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$rules = [
			'from_name'  => [ new BetweenRule(2, 200)],
			'from_email' => ['max:100'],
			'from_phone' => ['max:20'],
			'message'    => ['required'],
			'post_id'    => [ 'numeric'],
		];
		
		// reCAPTCHA
		$rules = $this->recaptchaRules($rules);
		
		// Check 'resume' is required
		if ($this->filled('parentCatType') && in_array($this->input('parentCatType'), ['job-offer'])) {
			$rules['filename'] = [
				
				'mimes:' . getUploadFileTypes('file'),
				'min:' . (int)config('settings.upload.min_file_size', 0),
				'max:' . (int)config('settings.upload.max_file_size', 1000),
			];
		}
		
		// Email
		if ($this->filled('from_email')) {
			$rules['from_email'][] = 'email';
			$rules['from_email'][] = new EmailRule();
		}
		if (isEnabledField('email')) {
			if (isEnabledField('phone') && isEnabledField('email')) {
				$rules['from_email'][] = '';
			} else {
				$rules['from_email'][] = '';
			}
		}
		
		// Phone
		if (config('settings.sms.phone_verification') == 1) {
			if ($this->filled('from_phone')) {
				$countryCode = $this->input('country_code', config('country.code'));
				if ($countryCode == 'UK') {
					$countryCode = 'GB';
				}
				$rules['from_phone'][] = 'phone:' . $countryCode;
			}
		}
		if (isEnabledField('phone')) {
			if (isEnabledField('phone') && isEnabledField('email')) {
				$rules['from_phone'][] = '';
			} else {
				$rules['from_phone'][] = '';
			}
		}
		
		return $rules;
	}
}
