<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Domain as BaseDomain;

class Domain extends BaseDomain
{
    public function tenant()
    {
        return $this->belongsTo(\App\Models\Vcard::class, 'vcard_id', 'id');
    }
}
