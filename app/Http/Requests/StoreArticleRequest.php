<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Request;
use Override;

class StoreArticleRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|array',
            'content.*' => 'required|string|min:1',
            'images' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5048',
            'is_premium' => 'required|boolean',
            'status' => 'required|in:draft,published'
        ];
    }

    
    public function messages()
    {
        return [
            'content.required'   => 'Content is required.',
            'content.min'        => 'Content cannot be empty.',
            'content.*.required' => 'Each paragraph cannot be empty.',
            'content.*.min'      => 'Each paragraph cannot be empty.',
        ];
    }
}
