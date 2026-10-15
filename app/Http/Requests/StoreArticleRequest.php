<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Request;

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
    /**
     * Drop empty text lines (keeping array keys so uploaded files still match their block).
     */
    protected function prepareForValidation(): void
    {
        $content = $this->input('content');

        if (! is_array($content)) {
            return;
        }

        $this->merge([
            'content' => array_filter($content, fn ($block) => is_array($block)
                && (($block['type'] ?? null) === 'image' || filled($block['text'] ?? null))),
        ]);
    }

    public function rules(): array
    {

        return [
            'title' => 'required|string|max:255',
            'content' => 'required|array',
            'content.*.type' => 'required|string|in:paragraph,quote,image',
            'content.*.text' => 'required_if:content.*.type,paragraph,quote|nullable|string',
            'content.*.image' => 'exclude_unless:content.*.type,image|required_without:content.*.url|image|mimes:jpeg,png,jpg,webp|max:5048',
            'content.*.url' => ['nullable', 'string', 'regex:#^/storage/articles/content/[\w.\-]+$#'],
            'images' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5048',
            'is_premium' => 'required|boolean',
            'status' => 'required|in:draft,published',
        ];
    }

    public function messages()
    {
        return [
            'content.required' => 'Content is required.',
            'content.min' => 'Content cannot be empty.',
            'content.*.required' => 'Each paragraph cannot be empty.',
            'content.*.min' => 'Each paragraph cannot be empty.',
        ];
    }
}
