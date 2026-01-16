<?php

namespace App\Console\Commands;

use App\Models\Vcard;
use App\Services\AstroDataGenerator;
use Illuminate\Console\Command;

class ExportTenantData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:export {slug? : The slug of the tenant to export} {--all : Export all active tenants}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export tenant data to JSON for Astro build';

    /**
     * Execute the console command.
     */
    public function handle(AstroDataGenerator $generator)
    {
        $slug = $this->argument('slug');
        $all = $this->option('all');

        if ($all) {
            $this->info('Exporting all active tenants...');
            Vcard::where('is_active', true)->chunk(100, function ($vcards) use ($generator) {
                foreach ($vcards as $vcard) {
                    $this->exportOne($vcard, $generator);
                }
            });
            return;
        }

        if (!$slug) {
            $this->error('Please provide a slug or use --all');
            return;
        }

        $vcard = Vcard::where('slug', $slug)->first();

        if (!$vcard) {
            $this->error("Tenant with slug '{$slug}' not found.");
            return;
        }

        $this->exportOne($vcard, $generator);
    }

    protected function exportOne(Vcard $vcard, AstroDataGenerator $generator)
    {
        try {
            $path = $generator->generate($vcard);
            $this->info("Exported {$vcard->slug} to {$path}");
        } catch (\Exception $e) {
            $this->error("Failed to export {$vcard->slug}: " . $e->getMessage());
        }
    }
}
