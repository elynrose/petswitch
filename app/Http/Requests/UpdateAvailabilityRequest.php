<?php

namespace App\Http\Requests;

use App\Models\Availability;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateAvailabilityRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('availability_edit');
    }

    public function rules()
    {
        return [
            'service_id' => [
                'required',
                'integer',
            ],
            'zip_code' => [
                'string',
                'required',
            ],
            'message' => [
                'required',
            ],
            'date_from' => [
                'required',
            ],
            'date_to' => [
                'required',
            ],
            'user_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
