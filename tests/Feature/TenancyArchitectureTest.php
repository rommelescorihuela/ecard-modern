<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vcard;
use App\Models\Domain;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class TenancyArchitectureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function vcard_is_a_valid_tenant()
    {
        $owner = User::factory()->create();
        $vcard = Vcard::create([
            'slug' => 'test-clinic',
            'user_id' => $owner->id,
            // Add other required fields if any
        ]);

        $this->assertInstanceOf(\Stancl\Tenancy\Contracts\Tenant::class, $vcard);
        $this->assertEquals('test-clinic', $vcard->slug);
    }

    /** @test */
    public function domains_can_be_attached_to_vcard()
    {
        $owner = User::factory()->create();
        $vcard = Vcard::create([
            'slug' => 'clinic-domains',
            'user_id' => $owner->id,
        ]);

        $domain = $vcard->domains()->create([
            'domain' => 'clinic.test',
        ]);

        $this->assertDatabaseHas('domains', ['domain' => 'clinic.test', 'vcard_id' => $vcard->id]);
    }

    /** @test */
    public function user_belongs_to_vcard_and_is_scoped()
    {
        $ownerA = User::factory()->create();
        $ownerB = User::factory()->create();

        // Create Tenant A
        $vcardA = Vcard::create(['slug' => 'tenant-a', 'user_id' => $ownerA->id]);

        // Define Logic: Initialize Tenant A
        tenancy()->initialize($vcardA);

        // Create User inside Tenant A
        $userA = User::create([
            'name' => 'User A',
            'email' => 'a@test.com',
            'password' => bcrypt('password'),
            'vcard_id' => $vcardA->id // should be auto-set if trait works on create, but we set explicit for now
        ]);

        // Create Tenant B
        tenancy()->end(); // Exit A
        $vcardB = Vcard::create(['slug' => 'tenant-b', 'user_id' => $ownerB->id]);
        tenancy()->initialize($vcardB);

        // Create User inside Tenant B
        $userB = User::create([
            'name' => 'User B',
            'email' => 'b@test.com',
            'password' => bcrypt('password'),
            'vcard_id' => $vcardB->id
        ]);

        // Assert: When in Tenant B, I should ONLY see User B (plus the owner if they are also scoped? Owners are users too!)
        // Wait, User::count() will count ALL users in the scope.
        // OwnerB is created in Central scope (no vcard_id usually, or is it?)
        // Factory creates user with no vcard_id?
        // If owner has no vcard_id, do they show up in tenant scope?
        // BelongsToVcard Global Scope: `where('vcard_id', $tenantId)`
        // So global scope will filter out everything that doesn't match current tenant.
        // OwnerB generally wouldn't have vcard_id unless assigned.
        // So User::count() should be 1 (User B).
        // Let's verify.

        $this->assertEquals(1, User::where('email', 'b@test.com')->count());
        // Better:
        $visibleUsers = User::all();
        $this->assertTrue($visibleUsers->contains('email', 'b@test.com'));
        $this->assertFalse($visibleUsers->contains('email', 'a@test.com'));


        // Switch to Tenant A
        tenancy()->initialize($vcardA);
        $visibleUsersA = User::all();
        $this->assertTrue($visibleUsersA->contains('email', 'a@test.com'));
        $this->assertFalse($visibleUsersA->contains('email', 'b@test.com'));
    }

    /** @test */
    public function user_panel_access_logic()
    {
        $owner = User::factory()->create();
        $vcard = Vcard::create(['slug' => 'tenant-panel', 'user_id' => $owner->id]);

        $adminUser = new User(['vcard_id' => null]);
        $tenantUser = new User(['vcard_id' => $vcard->id]);

        // Admin Panel Check
        $adminPanel = new \Filament\Panel();
        $adminPanel->id('admin');

        $this->assertTrue($adminUser->canAccessPanel($adminPanel));
        $this->assertFalse($tenantUser->canAccessPanel($adminPanel));

        // App Panel Check
        $appPanel = new \Filament\Panel();
        $appPanel->id('app');

        $this->assertFalse($adminUser->canAccessPanel($appPanel));
        $this->assertTrue($tenantUser->canAccessPanel($appPanel));
    }
}
