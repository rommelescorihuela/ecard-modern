<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Vcard;
use App\Models\Contact;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

class SaaSArchitectureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function vcard_has_onboarding_step()
    {
        $this->assertTrue(Schema::hasColumn('vcards', 'onboarding_step'));

        $user = \App\Models\User::factory()->create();
        $vcard = Vcard::create([
            'user_id' => $user->id,
            'slug' => 'onboarding-test',
            'template_identifier' => 'modern',
            'is_active' => true,
            'onboarding_step' => 2,
        ]);

        $this->assertEquals(2, $vcard->fresh()->onboarding_step);
    }

    /** @test */
    public function contact_module_structure()
    {
        $this->assertTrue(Schema::hasTable('contacts'));
        $this->assertTrue(Schema::hasColumn('contacts', 'vcard_id'));

        $contact = new Contact();
        $this->assertTrue(method_exists($contact, 'vcard'));
    }

    /** @test */
    public function payment_module_structure()
    {
        $this->assertTrue(Schema::hasTable('payments'));
        $this->assertTrue(Schema::hasColumn('payments', 'user_id'));
        $this->assertTrue(Schema::hasColumn('payments', 'amount'));

        $payment = new Payment();
        $this->assertTrue(method_exists($payment, 'user'));
    }

    /** @test */
    public function resources_exist()
    {
        $this->assertTrue(class_exists(\App\Filament\App\Resources\Contacts\ContactResource::class));
        $this->assertTrue(class_exists(\App\Filament\Resources\Payments\PaymentResource::class));
        $this->assertTrue(class_exists(\App\Filament\App\Resources\Appointments\AppointmentResource::class));
        $this->assertTrue(class_exists(\App\Filament\App\Resources\VCards\VCardResource::class));
        $this->assertTrue(class_exists(\App\Filament\App\Pages\Auth\Register::class));
    }
}
