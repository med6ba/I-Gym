<?php

namespace Tests\Feature;

use App\Models\Gym;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use ZipArchive;

class SuperGymExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_export_gyms_to_excel(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'super_admin',
            'gym_id' => null,
        ]);

        $this->createGymWithUsers();

        $response = $this->actingAs($superAdmin)->get(route('super.gyms.export', 'excel'));

        $response
            ->assertOk()
            ->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $this->assertStringContainsString('attachment; filename="gyms-', $response->headers->get('content-disposition'));
        $this->assertStringStartsWith('PK', $response->getContent());
        $worksheet = $this->worksheetXml($response->getContent());

        $this->assertStringContainsString('I-Gym Gyms Report', $worksheet);
        $this->assertStringContainsString('mergeCell ref="A1:L1"', $worksheet);
        $this->assertStringContainsString('North Star Gym', $worksheet);
        $this->assertStringContainsString('North Star Admin', $worksheet);
    }

    public function test_super_admin_can_export_gyms_to_pdf(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'super_admin',
            'gym_id' => null,
        ]);

        $this->createGymWithUsers();

        $response = $this->actingAs($superAdmin)->get(route('super.gyms.export', 'pdf'));

        $response
            ->assertOk()
            ->assertHeader('Content-Type', 'application/pdf');

        $this->assertStringContainsString('attachment; filename="gyms-', $response->headers->get('content-disposition'));
        $this->assertStringStartsWith('%PDF-1.4', $response->getContent());
        $this->assertStringContainsString('I-Gym Gyms Report', $response->getContent());
        $this->assertStringContainsString('Total gyms', $response->getContent());
        $this->assertStringContainsString('North Star Gym', $response->getContent());
        $this->assertStringContainsString('North Star Admin', $response->getContent());
    }

    public function test_gym_admin_cannot_export_super_admin_gym_list(): void
    {
        $gym = $this->createGymWithUsers();
        $gymAdmin = User::where('gym_id', $gym->id)->where('role', 'gym_admin')->firstOrFail();

        $this->actingAs($gymAdmin)
            ->get(route('super.gyms.export', 'excel'))
            ->assertForbidden();
    }

    private function createGymWithUsers(): Gym
    {
        $gym = Gym::create([
            'name' => 'North Star Gym',
            'slug' => 'north-star-gym',
            'phone' => '+212 600 000 000',
            'address' => '1 Main Street',
            'city' => 'Casablanca',
            'status' => 'active',
            'subscription_plan' => 'pro',
            'subscription_started_at' => now()->subMonth(),
            'subscription_ends_at' => now()->addMonth(),
        ]);

        User::factory()->create([
            'gym_id' => $gym->id,
            'role' => 'gym_admin',
            'name' => 'North Star Admin',
            'email' => 'admin@northstar.test',
        ]);

        User::factory()->create([
            'gym_id' => $gym->id,
            'role' => 'coach',
            'email' => 'coach@northstar.test',
        ]);

        User::factory()->count(2)->create([
            'gym_id' => $gym->id,
            'role' => 'member',
        ]);

        return $gym;
    }

    private function worksheetXml(string $contents): string
    {
        $path = tempnam(sys_get_temp_dir(), 'igym-export-test-');

        $this->assertNotFalse($path);
        file_put_contents($path, $contents);

        $zip = new ZipArchive;
        $this->assertTrue($zip->open($path) === true);

        $xml = $zip->getFromName('xl/worksheets/sheet1.xml');
        $zip->close();
        @unlink($path);

        $this->assertNotFalse($xml);

        return $xml;
    }
}
