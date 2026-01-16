<?php

namespace App\Http\Controllers;

use App\Models\Vcard;
use Illuminate\Http\Request;

class VCardController extends Controller
{
    public function show($slug)
    {
        $vcard = Vcard::where('slug', $slug)->where('is_active', true)->firstOrFail();

        return response()->json($vcard->content);
    }
}
