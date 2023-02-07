<?php

namespace App\Http\Requests\Travel;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class CreateTravel extends FormRequest
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
            'slug' => 'sometimes|string:255',
            'description' => 'required|string',
            'isPublic' => 'sometimes|boolean',
            'numberOfDays' => 'required|integer',
            'moods' => 'required|array',
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
