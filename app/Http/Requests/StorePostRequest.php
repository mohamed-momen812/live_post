<?php

namespace App\Http\Requests;

use App\Rules\IntegerArray;
use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'body' => ['required', 'string'],
            'user_ids' => [
                'required',
                'array',
                new IntegerArray("momen") // rule class, don't forget the macros
            ],
        ];
    }

    // function messages to edit default values of validation error messages
    public function messages(): array
    {
        return [
            'body.required' => 'Please enter a value for body',
            'title.string' => 'Heyyy use a string',
        ];
    }
}
