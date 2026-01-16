<?php

use App\Models\Vcard;
use App\Models\VcardService;

// Find the wizardfinal card
$vcard = Vcard::where('slug', 'wizardfinal')->firstOrFail();

// 1. Create Services
$vcard->vcard_services()->delete(); // Clean up old
$vcard->vcard_services()->create([
    'title' => 'Web Development',
    'description' => 'Full stack websites using Laravel and Astro.',
    'price' => 1200.00,
]);
$vcard->vcard_services()->create([
    'title' => 'Mobile Apps',
    'description' => 'iOS and Android apps built with Flutter.',
    'price' => 2500.00,
]);
$vcard->vcard_services()->create([
    'title' => 'Consulting',
    'description' => 'Technical strategy and architecture planning.',
    'price' => 150.00,
]);

// 2. Prepare Content JSON with Portfolio and Testimonials
$content = is_string($vcard->content) ? json_decode($vcard->content, true) : $vcard->content;

// Ensure modules are active
$content['modulos'] = ['servicios', 'portafolio', 'testimonios', 'contacto'];

// Portfolio Data
$content['portfolio'] = [
    [
        'title' => 'E-Commerce Platform',
        'description' => 'A scalable shop built for high traffic.',
        'image' => 'https://images.unsplash.com/photo-1557821552-17105176677c?w=800&q=80',
    ],
    [
        'title' => 'Corporate Dashboard',
        'description' => 'Real-time analytics for enterprise.',
        'image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&q=80',
    ],
    [
        'title' => 'Travel App',
        'description' => 'Booking system for global travelers.',
        'image' => 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?w=800&q=80',
    ],
];

// Testimonials Data
$content['testimonials'] = [
    [
        'name' => 'Sarah Johnson',
        'role' => 'CTO at TechCorp',
        'text' => 'The most efficient developer we have ever worked with. Delivered on time and bug-free.',
        'image' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=400&q=80',
    ],
    [
        'name' => 'Michael Chen',
        'role' => 'Founder of StartUp',
        'text' => 'Incredible attention to detail. The new site doubled our conversions.',
        'image' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=400&q=80',
    ],
];

// Contact Data (Ensure it exists)
$content['contact'] = [
    'email' => 'wizard@example.com',
    'phone' => '+1 234 567 890',
    'whatsapp' => '+1234567890',
    'location' => 'San Francisco, CA',
];

// Save
$vcard->content = $content;
$vcard->save();

echo "WizardFinal card populated with rich data!\n";
