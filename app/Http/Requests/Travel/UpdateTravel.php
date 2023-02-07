<?php

namespace App\Http\Requests\Travel;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTravel extends FormRequest
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
            'name' => 'sometimes|string:255',
            'slug' => 'sometimes|string:255|unique:travels,slug',
            'description' => 'sometimes|string',
            'isPublic' => 'sometimes|boolean',
            'numberOfDays' => 'sometimes|integer',
            'moods' => 'sometimes|array',
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
