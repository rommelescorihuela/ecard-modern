<?php

namespace App\Filament\Resources\VCards;

use App\Filament\Resources\VCards\Pages\CreateVCard;
use App\Filament\Resources\VCards\Pages\EditVCard;
use App\Filament\Resources\VCards\Pages\ListVCards;
use App\Filament\Resources\VCards\Schemas\VCardForm;
use App\Filament\Resources\VCards\Tables\VCardsTable;
use App\Models\Vcard;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class VCardResource extends Resource
{
    protected static ?string $model = Vcard::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return VCardForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VCardsTable::configure($table)
            ->actions([
                EditAction::make(),
                Action::make('preview')
                    ->label('Preview')
                    ->icon('heroicon-o-eye')
                    ->url(fn(Vcard $record) => "/sites/{$record->slug}/index.html")
                    ->openUrlInNewTab(),
                Action::make('rebuild')
                    ->label('Rebuild')
                    ->icon('heroicon-o-arrow-path')
                    ->action(function (Vcard $record) {
                        \App\Jobs\BuildTenantSite::dispatch($record);
                        \Filament\Notifications\Notification::make()
                            ->title('Build Queued')
                            ->success()
                            ->send();
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVCards::route('/'),
            'create' => CreateVCard::route('/create'),
            'edit' => EditVCard::route('/{record}/edit'),
        ];
    }

}
