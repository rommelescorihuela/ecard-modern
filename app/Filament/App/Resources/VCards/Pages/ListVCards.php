<?php

namespace App\Filament\App\Resources\VCards\Pages;

use App\Filament\App\Resources\VCards\VCardResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVCards extends ListRecords
{
    protected static string $resource = VCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Tenant cannot create VCards
        ];
    }
}
