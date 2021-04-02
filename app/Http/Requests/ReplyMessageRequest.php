<?php


namespace App\Http\Requests;


use App\Rules\BetweenRule;

class ReplyMessageRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
			'message' => ['required', new BetweenRule(20, 500)],
        ];
        
        return $rules;
    }
}
