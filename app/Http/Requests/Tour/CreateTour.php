<?php

namespace App\Http\Requests\Tour;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class CreateTour extends FormRequest
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
            'name' => 'required|string:255',
            'startingDate' => 'required|date',
            'endingDate' => 'required|date|after_or_equal:startingDate',
            'price' => 'required|integer|min:100',
        ];
    }

    public function failedValidation( Validator $validator )
    {
        $response = response()->json([
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
        ], 422);
        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
