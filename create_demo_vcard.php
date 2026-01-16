<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::first() ?? App\Models\User::factory()->create();
$vcard = App\Models\Vcard::updateOrCreate(['slug' => 'demo-card'], [
    'user_id' => $user->id,
    'template_identifier' => 'default',
    'is_active' => true,
    'content' => json_encode([
        'title' => 'Juan Perez',
        'description' => 'Demo Card',
        'theme' => [
            'primary' => '#ef4444',
            'secondary' => '#3b82f6'
        ],
        'portfolio' => [
            ['title' => 'Project A', 'image' => 'https://via.placeholder.com/400', 'description' => 'Awesome project'],
            ['title' => 'Project B', 'image' => 'https://via.placeholder.com/400', 'description' => 'Another one']
        ],
        'testimonials' => [
            ['name' => 'Client X', 'content' => 'Great service!'],
            ['name' => 'Client Y', 'content' => 'Highly recommended.']
        ],
        'contact' => [
            'email' => 'juan@example.com',
            'phone' => '+1234567890'
        ]
    ])
]);

// Clear services to avoid dupes
$vcard->vcard_services()->delete();
$vcard->vcard_services()->create([
    'title' => 'Demo Service',
    'price' => 99,
    'description' => 'A test service'
]);

echo "Created Vcard: " . $vcard->slug . "\n";
