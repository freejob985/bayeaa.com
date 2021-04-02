<?php


namespace App\Http\Requests\Admin;

class FieldRequest extends Request
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'name'    => ['required', 'min:2', 'max:255'],
			'type'    => ['required'],
			'max'     => ['not_in:0'],
			'default' => ['max:255'],
		];
	}
}
