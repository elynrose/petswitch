<?php

namespace App\Http\Requests;

use App\Models\ServiceRequest;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateServiceRequestRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('service_request_edit');
    }

    public function rules()
    {
        return [
            'service_id' => [
                'required',
                'integer',
            ],
            'pet_id' => [
                'required',
                'integer',
            ],
            'zip_code' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'from' => [
                'required',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
            ],
            'to' => [
                'required',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
            ],
            'comments' => [
                'required',
            ],
            'user_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
