<?php

namespace App\Http\Requests\Auth;

use App\Models\Enums\OtpType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VerifyOtpRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'identifier' => ['required', 'string'],
            'otp_code' => ['required', 'string', 'size:6'],
            'type' => ['nullable', 'string', Rule::enum(OtpType::class)],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'identifier.required' => 'L\'identifiant est obligatoire.',
            'otp_code.required' => 'Le code OTP est obligatoire.',
            'otp_code.size' => 'Le code OTP doit contenir 6 caractères.',
        ];
    }
}
