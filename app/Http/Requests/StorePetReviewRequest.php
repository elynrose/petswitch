<?php

namespace App\Http\Requests;

use App\Models\PetReview;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StorePetReviewRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('pet_review_create');
    }

    public function rules()
    {
        return [
            'booking_id' => [
                'required',
                'integer',
            ],
            'comment' => [
                'required',
            ],
            'rating' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
