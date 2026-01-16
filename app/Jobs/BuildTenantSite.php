<?php

namespace App\Jobs;

use App\Models\Vcard;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;

class BuildTenantSite implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Vcard $vcard
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("Starting build for tenant: {$this->vcard->slug}");

        // Ensure we use the correct Node environment (NVM)
        // We wrap the command to source NVM before running
        $command = 'export NVM_DIR="$HOME/.nvm" && [ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh" && node build-tenant.cjs ' . $this->vcard->slug;

        $result = Process::path(base_path())
            ->run($command);

        if ($result->successful()) {
            Log::info("Build successful for {$this->vcard->slug}");
            $this->vcard->update(['last_built_at' => now()]);
        } else {
            Log::error("Build failed for {$this->vcard->slug}: " . $result->errorOutput());
            throw new \Exception("Build failed: " . $result->errorOutput());
        }
    }
}
