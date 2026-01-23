<?php

namespace App\Traits;

use App\Models\Vcard;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

trait BelongsToVcard
{
    use BelongsToTenant;

    public function vcard()
    {
        return $this->tenant();
    }
}
