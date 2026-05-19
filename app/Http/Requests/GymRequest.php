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
        $gym = $this->route('gym');
        $gymId = $gym?->id;
        $adminId = $gym?->primaryAdmin?->id;
        $creating = $this->isMethod('post');
        $needsAdminPassword = $creating || ! $adminId;

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('gyms', 'slug')->ignore($gymId)],
            'phone' => ['nullable', 'string', 'max:40'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:120'],
            'status' => ['required', Rule::in(['active', 'trial', 'expired', 'suspended'])],
            'subscription_plan' => ['required', Rule::in(['basic', 'pro', 'business'])],
            'subscription_started_at' => ['nullable', 'date', 'after:2026-06-30'],
            'subscription_ends_at' => ['nullable', 'date', 'after:2026-06-30', 'after_or_equal:subscription_started_at'],
            'admin_name' => ['required', 'string', 'max:255'],
            'admin_email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($adminId)],
            'admin_password' => [$needsAdminPassword ? 'required' : 'nullable', 'string', 'min:8', 'confirmed'],
        ];
    }
}
