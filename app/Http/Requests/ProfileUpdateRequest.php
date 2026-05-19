<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return ! ($this->user()?->isSuperAdmin() ?? false);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'phone' => ['nullable', 'string', 'max:40'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'language' => ['sometimes', Rule::in(['en', 'fr', 'es', 'ar'])],
            'theme' => ['sometimes', Rule::in(['light', 'dark', 'system'])],
            'currency' => ['sometimes', Rule::in(['MAD', 'USD', 'EUR', 'GBP'])],
            'age' => ['nullable', 'integer', 'min:10', 'max:100'],
            'height_cm' => ['nullable', 'numeric', 'min:80', 'max:260'],
            'weight_kg' => ['nullable', 'numeric', 'min:25', 'max:350'],
            'gender' => ['nullable', Rule::in(['female', 'male', 'other', 'prefer_not_to_say'])],
            'fitness_goal' => ['nullable', Rule::in(['weight_loss', 'muscle_gain', 'fitness', 'endurance'])],
            'bio' => ['nullable', 'string', 'max:500'],
        ];
    }
}
