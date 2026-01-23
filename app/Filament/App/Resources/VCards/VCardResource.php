<?php


namespace App\Filament\App\Resources\VCards;

use App\Filament\App\Resources\VCards\Pages;
use App\Models\Vcard;
use BackedEnum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class VCardResource extends Resource
{
    protected static ?string $model = Vcard::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-identification';

    protected static ?string $recordTitleAttribute = 'slug';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('id', tenant('id'));
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('template_identifier')
                    ->label('Template')
                    ->readOnly(),
                Forms\Components\Section::make('Features')
                    ->description('Enable or disable features on your VCard.')
                    ->schema([
                        Forms\Components\Toggle::make('has_appointments')
                            ->label('Enable Appointments')
                            ->helperText('Allow visitors to book appointments with you.'),
                        Forms\Components\Toggle::make('has_contact_form')
                            ->label('Enable Contact Form')
                            ->helperText('Allow visitors to send you messages.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('template_identifier')
                    ->label('Template'),
                Tables\Columns\IconColumn::make('has_appointments')
                    ->boolean()
                    ->label('Appointments'),
                Tables\Columns\IconColumn::make('has_contact_form')
                    ->boolean()
                    ->label('Contact Form'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // No bulk delete for the tenant's own VCard!
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
            'index' => Pages\ListVCards::route('/'),
            // 'create' => Pages\CreateVCard::route('/create'), // Tenant should not create new Cards, only edit existing
            'edit' => Pages\EditVCard::route('/{record}/edit'),
        ];
    }
}
