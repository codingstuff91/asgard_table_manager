<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class storeDayRequest extends FormRequest
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
            'date' => 'required|date|unique:days,date'
        ];
    }

    public function messages()
    {
        return [
            'date.unique'   => 'Une session existe déjà pour cette date !',
            'date.required' => 'Veuillez fournir une date de session',
            'date.date'     => 'Veuillez fournir une date valide',
        ];
    }
}
