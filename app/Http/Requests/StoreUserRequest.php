<?php

namespace App\Http\Requests;

use App\Models\User;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreUserRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('user_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'last_name' => [
                'string',
                'required',
            ],
            'email' => [
                'required',
                'unique:users',
            ],
            'password' => [
                'required',
            ],
            'phone' => [
                'string',
                'required',
            ],
            'bio' => [
                'string',
                'nullable',
            ],
            'invitation_code' => [
                'string',
                'required',
            ],
            'invited_by_id' => [
                'integer',
                'nullable',
            ],
            'city' => [
                'string',
                'nullable',
            ],
            'timezone' => [
                'string',
                'nullable',
            ],
            'zip' => [
                'string',
                'nullable',
            ],
            'banned' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'roles.*' => [
                'integer',
            ],
            'roles' => [
                'required',
                'array',
            ],
            'expiry' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
        ];
    }
}
