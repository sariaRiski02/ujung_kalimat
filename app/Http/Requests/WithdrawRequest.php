<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class WithdrawRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $phone = preg_replace('/[\s\-]/', '', (string) $this->input('phone_number'));

        $this->merge([
            'phone_number' => preg_replace('/^(\+62|62)/', '0', $phone),
        ]);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'amount' => [
                'required',
                'integer',
                'min:'.config('monetization.min_withdrawal'),
                'max:'.$this->user()->availableBalance(),
            ],
            'phone_number' => ['required', 'regex:/^08[0-9]{8,12}$/'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'amount.min' => 'The minimum withdrawal is Rp '.number_format(config('monetization.min_withdrawal'), 0, ',', '.').'.',
            'amount.max' => 'The amount exceeds your available balance.',
            'phone_number.regex' => 'Enter a valid phone number, e.g. 081234567890.',
        ];
    }
}
