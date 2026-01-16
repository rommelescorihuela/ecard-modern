<?php

namespace App\Observers;

use App\Jobs\BuildTenantSite;
use App\Models\Vcard;

class VcardObserver
{
    /**
     * Handle the Vcard "saved" event.
     */
    public function saved(Vcard $vcard): void
    {
        // Only trigger build if relevant fields changed or it's a new Vcard
        if ($vcard->wasChanged('content', 'slug', 'template_identifier', 'is_active')) {
            BuildTenantSite::dispatch($vcard);
        }
    }

    /**
     * Handle the Vcard "deleted" event.
     */
    public function deleted(Vcard $vcard): void
    {
        // Optional: Clean up generated site files
    }
}
