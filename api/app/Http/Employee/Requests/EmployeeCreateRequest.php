<?php

namespace App\Http\Employee\Requests;

use App\Rules\EmployeeCanWorkAtRestaurant;
use Illuminate\Foundation\Http\FormRequest;

class EmployeeCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->user())
            return true;
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'firstname' => ['required', 'max:128'],
            'lastname' => ['required', 'max:128'],
            'email' => ['required', 'email', 'max:128', 'unique:employees'],
            'restaurant_ids' => ['required', new EmployeeCanWorkAtRestaurant()]
        ];
    }
}
