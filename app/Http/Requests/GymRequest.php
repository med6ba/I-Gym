<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GymRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isSuperAdmin() ?? false;
    }

    public function rules(): array
    {
        $gymId = $this->route('gym')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('gyms', 'slug')->ignore($gymId)],
            'email' => ['required', 'email', 'max:255', Rule::unique('gyms', 'email')->ignore($gymId)],
            'phone' => ['nullable', 'string', 'max:40'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:120'],
            'status' => ['required', Rule::in(['active', 'trial', 'expired', 'suspended'])],
            'subscription_plan' => ['required', Rule::in(['basic', 'pro', 'business'])],
            'subscription_started_at' => ['nullable', 'date'],
            'subscription_ends_at' => ['nullable', 'date', 'after_or_equal:subscription_started_at'],
        ];
    }
}
