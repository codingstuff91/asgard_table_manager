<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventStoreRequest extends FormRequest
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
            'name' => 'required',
            'description' => 'required',
            'start_hour' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Veuillez saisir un nom',
            'description.required' => 'Veuillez fournir une description',
            'start_hour.required' => 'Veuillez fournir une heure de début',
        ];
    }
}
