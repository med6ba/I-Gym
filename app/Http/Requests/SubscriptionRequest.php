<?php

namespace App\Http\Requests;

use App\Models\Subscription;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isGymAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'plan_name' => ['required', Rule::in(array_keys(Subscription::plans()))],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after_or_equal:starts_at'],
            'status' => ['required', Rule::in(['active', 'expired', 'cancelled'])],
            'payment_status' => ['required', Rule::in(['paid', 'unpaid'])],
        ];
    }
}
