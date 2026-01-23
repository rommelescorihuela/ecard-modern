<?php

namespace App\Filament\App\Resources\VCards\Pages;

use App\Filament\App\Resources\VCards\VCardResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVCard extends EditRecord
{
    protected static string $resource = VCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
