<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Vcard;
use App\Models\SystemActivity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Stancl\Tenancy\Events\TenancyInitialized;

class FullSystemIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        \Illuminate\Support\Facades\Bus::fake();

        // Mock Stripe to avoid API calls during Billable tests if needed
        // For now we just test DB interaction and Trait presence
    }

    /** @test */
    public function activity_logger_logs_model_events()
    {
        // 1. Create User
        $user = User::create([
            'name' => 'Log Test User',
            'email' => 'logtest@example.com',
            'password' => bcrypt('password'),
        ]);

        // Assert Creation Log
        $this->assertDatabaseHas('system_activities', [
            'subject_type' => User::class,
            'subject_id' => $user->id,
            'action' => 'create',
        ]);

        // 2. Update User
        $user->update(['name' => 'Updated Name']);

        // Assert Update Log
        $log = SystemActivity::where('subject_type', User::class)
            ->where('subject_id', $user->id)
            ->where('action', 'update')
            ->first();

        $this->assertNotNull($log);
        $this->assertEquals('Log Test User', $log->old_values['name']);
        $this->assertEquals('Updated Name', $log->new_values['name']);

        // 3. Delete User
        $user->delete();

        // Assert Delete Log
        $this->assertDatabaseHas('system_activities', [
            'subject_type' => User::class,
            'subject_id' => $user->id,
            'action' => 'delete',
        ]);
    }

    /** @test */
    public function activity_logger_captures_tenant_context()
    {
        $user = User::create([
            'name' => 'Owner',
            'email' => 'owner@test.com',
            'password' => bcrypt('password')
        ]);

        $vcard = Vcard::create([
            'user_id' => $user->id,
            'slug' => 'test-log-tenant',
            'template_identifier' => 'modern',
            'is_active' => true,
        ]);

        // Initialize Tenancy
        tenancy()->initialize($vcard);

        // Perform action inside tenant context
        // e.g. Create another user or update Vcard (Vcard itself logs too)
        $vcard->update(['slug' => 'updated-tenant-slug']);

        $log = SystemActivity::where('subject_type', Vcard::class)
            ->where('subject_id', $vcard->id)
            ->where('action', 'update')
            ->first();

        $this->assertNotNull($log);
        // Assert vcard_id is captured in the log
        $this->assertEquals($vcard->id, $log->vcard_id);
    }

    /** @test */
    public function user_is_billable_and_has_subscriptions_table()
    {
        $user = User::create([
            'name' => 'Stripe User',
            'email' => 'stripe@test.com',
            'password' => bcrypt('password'),
        ]);

        // Check Billable trait methods
        $this->assertTrue(method_exists($user, 'newSubscription'));
        $this->assertTrue(method_exists($user, 'subscriptions'));

        // Check if we can interact with subscriptions relation (Empty initially)
        $this->assertCount(0, $user->subscriptions);

        // We cannot create a real subscription without Stripe API, 
        // but we can verify the table structure allows insertion via Model if we forced it or mocked it.
        // For this test, verifying Trait + Table existence is sufficient for "Integration" without external API.
    }
}
