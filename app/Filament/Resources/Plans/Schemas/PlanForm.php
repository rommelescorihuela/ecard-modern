<?php

namespace App\Filament\Resources\Plans\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->label('Nombre'),
                TextInput::make('price')
                    ->numeric()
                    ->required()
                    ->label('Precio'),
                TextInput::make('limit_vcards')
                    ->numeric()
                    ->required()
                    ->label('Límite de vCards'),
                Textarea::make('features')
                    ->label('Características (JSON)')
                    ->rows(3),
            ]);
    }
}
