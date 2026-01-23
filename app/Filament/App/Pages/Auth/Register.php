<?php

namespace App\Filament\App\Pages\Auth;

use App\Models\User;
use App\Models\Vcard;
use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Filament\Forms;

class Register extends BaseRegister
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                TextInput::make('slug')
                    ->label('Address / Slug')
                    ->prefix('ecard.com/')
                    ->required()
                    ->unique(Vcard::class, 'slug')
                    ->maxLength(255)
                    ->helperText('This will be the URL of your digital card.'),
            ])
            ->statePath('data');
    }

    protected function handleRegistration(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            $user = $this->getUserModel()::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $vcard = Vcard::create([
                'user_id' => $user->id,
                'slug' => $data['slug'],
                'template_identifier' => 'modern', // Default template
                'is_active' => true,
                'has_appointments' => false,
                'has_contact_form' => true,
            ]);

            // Assign VCard to User (for tenant context)
            $user->vcard_id = $vcard->id;
            $user->save();

            return $user;
        });
    }
}
