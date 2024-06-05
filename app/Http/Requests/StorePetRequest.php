<?php

namespace App\Http\Requests;

use App\Models\Pet;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StorePetRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('pet_create');
    }

    public function rules()
    {
        return [
            'animal_id' => [
                'required',
                'integer',
            ],
            'name' => [
                'string',
                'required',
            ],
            'breed' => [
                'string',
                'required',
            ],
            'size' => [
                'required',
            ],
            'age' => [
                'string',
                'required',
            ],
            'gets_along_with' => [
                'required',
            ],
            'user_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
