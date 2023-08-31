<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'unique:users,id,'.$this->id. ',id'],
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'telephone' => ['required', 'string'],
            'age' => ['required', 'numeric']
        ];
    }
}
