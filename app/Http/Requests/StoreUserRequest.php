<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class StoreUserRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'gender' => [
                'required',
                Rule::in(['male', 'female','other']),
            ],
            'age_group' => [
                'required',
                'date',
                'before:' . Carbon::now()->subYears(18)->format('Y-m-d')
                ],
            'age_confirm'=> 'required|boolean',
            'english_confirm'=> 'required|boolean'
    ];
    }


    public function messages()
    {
        return [
            'age_group.before' => 'The age must be at least 18 years',
        ];
    }
}
