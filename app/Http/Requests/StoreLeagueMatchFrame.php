<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreLeagueMatchFrame extends FormRequest
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
        return [
            'frame_type' => [
                'required',
                Rule::in(['single', 'double'])
            ],
            'home_player_id' => 'required|exists:players,id',
            'away_player_id' => 'required|exists:players,id',
            'winning_team' => [
                'required',
                Rule::in(['home', 'away'])
            ]
        ];
    }
}
