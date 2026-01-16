<?php

namespace App\Filament\Resources\VCards\Pages;

use App\Filament\Resources\VCards\VCardResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVCards extends ListRecords
{
    protected static string $resource = VCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
