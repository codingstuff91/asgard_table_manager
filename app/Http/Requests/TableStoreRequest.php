<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TableStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'category_id'    => 'required',
            'game_id'        => 'required',
            'players_number' => 'required|integer',
            'total_points'   => 'required|integer',
            'start_hour'     => 'required',
        ];
    }

    public function messages()
    {
        return [
            'category_id.required'    => 'Veuillez choisir une catégorie',
            'game_id.required'        => 'Veuillez choisir un jeu',
            'players_number.required' => 'Veuillez définir un nombre de joueurs', 
            'players_number.integer'  => 'Veuillez saisir un nombre entier', 
            'total_points.required'   => 'Veuillez définir un nombre de points',
            'total_points.integer'    => 'Veuillez définir un nombre entier',
            'start_hour.required'     => 'Veuillez définir une heure de début'
        ];
    }
}
