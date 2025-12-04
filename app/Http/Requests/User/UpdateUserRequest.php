<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'email'                => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->user)
            ],
            'cellphone'            => 'required|string|max:11',
            'document'             => [
                'required',
                'string',
                Rule::unique('users')->ignore($this->user),
                'max:11'
            ],
            'password'             => 'required|string|min:6',
            'driver_document'      => [
                'sometimes',
                'nullable',
                'string',
                Rule::unique('users')->ignore($this->user),
                'max:11'
            ],
            'driver_document_code' => 'sometimes|nullable|string|max:255',
            'profile_photo'        => 'sometimes|nullable|string|max:255',
        ];
    }
}
