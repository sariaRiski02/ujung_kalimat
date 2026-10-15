<?php

namespace App\Http\Requests;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'username' => strtolower(ltrim(trim((string) $this->input('username')), '@')),
        ]);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'min:3',
                'max:30',
                'regex:/^[a-z0-9_.]+$/',
                Rule::unique('users', 'username')->ignore($this->user()->id),
                function (string $attribute, mixed $value, Closure $fail): void {
                    $takenWithPrefix = User::query()
                        ->whereKeyNot($this->user()->id)
                        ->where('username', '@'.$value)
                        ->exists();

                    if ($takenWithPrefix) {
                        $fail('This username is already taken.');
                    }
                },
            ],
            'bio' => ['nullable', 'string', 'max:160'],
            'city' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'username.regex' => 'The username may only contain lowercase letters, numbers, dots, and underscores.',
            'username.unique' => 'This username is already taken.',
        ];
    }
}
