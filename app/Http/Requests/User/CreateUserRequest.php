<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            'name'                 => 'required|string|max:255',
            'surname'              => 'required|string|max:255',
            'cellphone'            => 'required|string|max:11',
            'email'                => 'required|email|unique:users',
            'password'             => 'required|string|min:6',
            'document'             => 'required|string|unique:users|max:11',
            'driver_document'      => 'sometimes|nullable|string|unique:users|max:11',
            'driver_document_code' => 'sometimes|nullable|string|max:20',
            'profile_photo'        => 'sometimes|nullable|string|max:255',
            'device_token'         => 'sometimes|nullable|string|max:255',
        ];
    }
}
