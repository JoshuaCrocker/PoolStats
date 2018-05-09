<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateTeamVenue extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'member_from' => 'required|date_format:Y-m-d'
        ];

        if (!empty($this->input('member_to'))) {
            $rules['member_from'] .= '|before:member_to';
            $rules['member_to'] = 'required|date_format:Y-m-d|after:member_from';
        }

        return $rules;
    }
}
