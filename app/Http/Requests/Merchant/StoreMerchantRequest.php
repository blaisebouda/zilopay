<?php

declare(strict_types=1);

namespace App\Http\Requests\Merchant;

use App\Models\Enums\Country;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMerchantRequest extends FormRequest
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
            'business_name' => ['required', 'string', 'max:255'],
            'business_email' => ['required', 'string', 'email', 'max:255', 'unique:merchants,business_email'],
            'phone_number' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/', 'unique:merchants,phone_number'],
            'country' => ['required', 'string', Rule::enum(Country::class)],
            'documents' => ['nullable', 'array'],
            'documents.*' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],

        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'business_name.required' => 'The business name is required.',
            'business_name.max' => 'The business name may not be greater than 255 characters.',
            'business_email.required' => 'The business email is required.',
            'business_email.email' => 'Please provide a valid email address.',
            'business_email.unique' => 'This business email is already registered.',
            'country.required' => 'The country is required.',
            'country.size' => 'The country code must be exactly 4 characters.',
            'documents.*.file' => 'Each document must be a valid file.',
            'documents.*.mimes' => 'Each document must be a PDF file.',
            'documents.*.max' => 'Each document may not be larger than 5MB.',
        ];
    }
}
