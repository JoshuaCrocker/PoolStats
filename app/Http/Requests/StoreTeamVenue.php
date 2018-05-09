<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class StoreTeamVenue extends FormRequest
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
            'team_id' => 'required|exists:teams,id',
            'member_from' => 'required|date_format:Y-m-d'
        ];

        if (!empty($this->input('member_to'))) {
            $rules['member_from'] .= '|before:member_to';
            $rules['member_to'] = 'required|date_format:Y-m-d|after:member_from';
        }

        return $rules;
    }
}
