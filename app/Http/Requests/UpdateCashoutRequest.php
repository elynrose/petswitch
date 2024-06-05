<?php

namespace App\Http\Requests;

use App\Models\Cashout;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateCashoutRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('cashout_edit');
    }

    public function rules()
    {
        return [
            'user_id' => [
                'required',
                'integer',
            ],
            'credits' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'amount' => [
                'required',
            ],
            'tracking' => [
                'string',
                'nullable',
            ],
        ];
    }
}
