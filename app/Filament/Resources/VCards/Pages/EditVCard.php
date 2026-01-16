<?php

namespace App\Filament\Resources\VCards\Pages;

use App\Filament\Resources\VCards\VCardResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditVCard extends EditRecord
{
    protected static string $resource = VCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
