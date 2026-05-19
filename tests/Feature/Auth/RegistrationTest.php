<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_redirects_to_login(): void
    {
        $response = $this->get('/register');

        $response
            ->assertRedirect(route('login', absolute: false))
            ->assertSessionHas('status', __('messages.registration_invite_only'));
    }

    public function test_public_registration_does_not_create_users(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response
            ->assertRedirect(route('login', absolute: false))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
        $this->assertFalse(User::where('email', 'test@example.com')->exists());
    }
}
