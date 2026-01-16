<?php

namespace App\Filament\Resources\VCards\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Schema;

class VCardForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Wizard\Step::make('General')
                        ->description('Información básica de la tarjeta')
                        ->schema([
                            TextInput::make('slug')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->label('Slug (Subdominio)')
                                ->helperText('La URL de tu tarjeta será: slug.midominio.com'),
                            Select::make('user_id')
                                ->relationship('user', 'name')
                                ->required()
                                ->label('Usuario Asignado'),
                            Select::make('template_id')
                                ->relationship('template', 'name')
                                ->required()
                                ->default(1)
                                ->label('Plantilla Base'),
                            CheckboxList::make('content.modulos')
                                ->options([
                                    'servicios' => 'Servicios',
                                    'portafolio' => 'Portafolio',
                                    'testimonios' => 'Testimonios',
                                    'contacto' => 'Contacto',
                                ])
                                ->columns(2)
                                ->label('Módulos Activos'),
                        ]),
                    Wizard\Step::make('Contenido')
                        ->description('Datos personales y perfil')
                        ->schema([
                            TextInput::make('content.nombre')
                                ->required()
                                ->label('Nombre Completo'),
                            TextInput::make('content.cargo')
                                ->label('Cargo / Profesión'),
                            Textarea::make('content.biografia')
                                ->label('Biografía Corta')
                                ->rows(3)
                                ->columnSpanFull(),
                            Section::make('Detalles de Contacto')
                                ->schema([
                                    TextInput::make('content.contact.email')
                                        ->email()
                                        ->label('Email Público'),
                                    TextInput::make('content.contact.phone')
                                        ->tel()
                                        ->label('Teléfono'),
                                    TextInput::make('content.contact.whatsapp')
                                        ->label('WhatsApp (con código país)'),
                                ])->columns(2),
                        ]),
                    Wizard\Step::make('Aspecto')
                        ->description('Personalización visual')
                        ->schema([
                            ColorPicker::make('content.theme.primary')
                                ->label('Color Primario')
                                ->default('#3b82f6'),
                            ColorPicker::make('content.theme.secondary')
                                ->label('Color Secundario')
                                ->default('#10b981'),
                            Select::make('content.theme.headingFont')
                                ->options([
                                    'Inter, sans-serif' => 'Inter',
                                    'Roboto, sans-serif' => 'Roboto',
                                    'Open Sans, sans-serif' => 'Open Sans',
                                ])
                                ->label('Fuente de Títulos')
                                ->default('Inter, sans-serif'),
                        ])->columns(2),
                ])->columnSpanFull(),
            ]);
    }
}
