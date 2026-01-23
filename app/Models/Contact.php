<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    use HasFactory, \App\Traits\BelongsToVcard; // Scoped by Tenant

    protected $guarded = [];

    public function vcard()
    {
        return $this->belongsTo(Vcard::class);
    }
}
