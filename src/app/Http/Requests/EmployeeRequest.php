<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class EmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'role_id' => 'nullable|exists:roles,id',
            'position_id' => 'nullable|exists:positions,id',
            'evaluator_id' => 'nullable|exists:users,id',
            'department' => 'nullable|string|max:255',
            'hire_date' => 'nullable|date',
            'status' => 'nullable|in:active,inactive',
            'username' => 'required_without:user_id|string|max:255|unique:users,username',
            'email' => 'required_without:user_id|email|max:255|unique:users,email',
            'password' => 'required_without:user_id|string|min:6',
            'user_role' => 'required_without:user_id|in:employee,evaluator,administrator',
        ];

        if ($this->route('id')) {
            $rules['username'] = 'sometimes|string|max:255|unique:users,username,' . $this->route('id');
            $rules['email'] = 'sometimes|email|max:255|unique:users,email,' . $this->route('id');
            $rules['password'] = 'sometimes|string|min:6';
            $rules['user_role'] = 'sometimes|in:employee,evaluator,administrator';
        }

        return $rules;
    }
}
