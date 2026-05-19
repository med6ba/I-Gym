<?php

namespace Tests\Feature;

use App\Models\Gym;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaasWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_creates_gym_with_admin_account(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'super_admin',
            'gym_id' => null,
        ]);

        $response = $this->actingAs($superAdmin)->post(route('super.gyms.store'), [
            'name' => 'North Star Gym',
            'slug' => 'north-star-gym',
            'email' => 'hello@northstar.test',
            'phone' => '+212 600 000 000',
            'address' => '1 Main Street',
            'city' => 'Casablanca',
            'status' => 'trial',
            'subscription_plan' => 'pro',
            'subscription_started_at' => now()->toDateString(),
            'subscription_ends_at' => now()->addMonth()->toDateString(),
            'admin_name' => 'North Star Admin',
            'admin_email' => 'admin@northstar.test',
            'admin_password' => 'password',
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('super.gyms.index'));

        $gym = Gym::where('slug', 'north-star-gym')->firstOrFail();

        $this->assertDatabaseHas('users', [
            'gym_id' => $gym->id,
            'name' => 'North Star Admin',
            'email' => 'admin@northstar.test',
            'role' => 'gym_admin',
            'status' => 'active',
        ]);
    }

    public function test_gym_admin_creates_coaches_and_members_inside_own_gym(): void
    {
        $gym = Gym::factory()->create(['status' => 'active']);
        $admin = User::factory()->create([
            'gym_id' => $gym->id,
            'role' => 'gym_admin',
        ]);

        $this->actingAs($admin)->post(route('admin.coaches.store'), [
            'name' => 'Demo Coach',
            'email' => 'coach@example.test',
            'phone' => '+212 611 111 111',
            'password' => 'password',
            'status' => 'active',
        ])->assertSessionHasNoErrors();

        $this->actingAs($admin)->post(route('admin.members.store'), [
            'name' => 'Demo Member',
            'email' => 'member@example.test',
            'phone' => '+212 622 222 222',
            'password' => 'password',
            'status' => 'active',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', [
            'gym_id' => $gym->id,
            'email' => 'coach@example.test',
            'role' => 'coach',
        ]);

        $this->assertDatabaseHas('users', [
            'gym_id' => $gym->id,
            'email' => 'member@example.test',
            'role' => 'member',
        ]);
    }
}
