<?php


namespace App\Http\Requests\Admin;

class FieldOptionRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'value' => ['required', 'max:255'],
        ];
    }
}
