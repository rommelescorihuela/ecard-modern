<?php

namespace App\Services;

use App\Models\Vcard;
use Illuminate\Support\Facades\Storage;

class AstroDataGenerator
{
    /**
     * Generate the JSON data for a specific Vcard (Tenant).
     *
     * @param Vcard $vcard
     * @return string Path to the generated JSON file
     */
    public function generate(Vcard $vcard): string
    {
        $data = $this->transform($vcard);
        $filename = "astro-data/{$vcard->slug}.json";

        Storage::disk('local')->put($filename, json_encode($data, JSON_PRETTY_PRINT));

        return storage_path("app/{$filename}");
    }

    /**
     * Transform the Vcard model into the array structure expected by Astro.
     *
     * @param Vcard $vcard
     * @return array
     */
    protected function transform(Vcard $vcard): array
    {
        // Decode content if it's a string, otherwise use as is
        $content = is_string($vcard->content) ? json_decode($vcard->content, true) : $vcard->content;

        return [
            'slug' => $vcard->slug,
            'meta' => [
                'title' => $content['nombre'] ?? $content['title'] ?? $vcard->slug,
                'description' => $content['cargo'] ?? $content['description'] ?? 'Digital Card',
                'image' => $content['image'] ?? null,
            ],
            'theme' => $this->getThemeConfig($vcard, $content),
            'modules' => $this->getModules($vcard),
            'generated_at' => now()->toIso8601String(),
        ];
    }

    protected function getThemeConfig(Vcard $vcard, ?array $content): array
    {
        // Default theme
        $default = [
            'primary' => '#3b82f6',
            'secondary' => '#10b981',
            'background' => '#ffffff',
            'text' => '#1f2937',
            'headingFont' => 'Inter, sans-serif',
            'bodyFont' => 'Inter, sans-serif',
        ];

        // Merge with saved theme config if exists
        return array_merge($default, $content['theme'] ?? []);
    }

    protected function getModules(Vcard $vcard): array
    {
        // Load relationships if not loaded
        $vcard->load(['vcard_services', 'appointments']);

        $modules = [];

        // Example: Services Module
        if ($vcard->vcard_services->count() > 0) {
            $modules[] = [
                'type' => 'services',
                'title' => 'My Services',
                'data' => $vcard->vcard_services->map(function ($service) {
                    return [
                        'title' => $service->title,
                        'description' => $service->description,
                        'price' => $service->price,
                        'image' => $service->image_url,
                    ];
                })->toArray()
            ];
        }

        // Decode content if string
        $content = is_string($vcard->content) ? json_decode($vcard->content, true) : $vcard->content;

        // Portfolio Module
        if (isset($content['portfolio']) && !empty($content['portfolio'])) {
            $modules[] = [
                'type' => 'portfolio',
                'title' => 'My Work',
                'data' => $content['portfolio']
            ];
        }

        // Testimonials Module
        if (isset($content['testimonials']) && !empty($content['testimonials'])) {
            $modules[] = [
                'type' => 'testimonials',
                'title' => 'Happy Clients',
                'data' => $content['testimonials']
            ];
        }

        // Contact Module
        if (isset($content['contact']) && !empty($content['contact'])) {
            $modules[] = [
                'type' => 'contact',
                'title' => 'Contact Me',
                'data' => $content['contact']
            ];
        }

        return $modules;
    }
}
