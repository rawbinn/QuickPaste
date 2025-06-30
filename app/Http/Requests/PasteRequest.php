<?php

namespace App\Http\Requests;

use App\Models\Enums\ExpiryType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PasteRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'content' => ['required', 'string'],
            'expiry' => ['required', Rule::in(ExpiryType::values())],
            'password' => ['nullable', 'string', 'min:8', 'max:32'],
            'language' => ['nullable', 'string', 'max:50'],
        ];
    }
}
