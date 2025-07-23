<?php

namespace App\Filament\Customer\Pages\Auth;

use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;
use Filament\Pages\Auth\Register as BaseRegister;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class Register extends BaseRegister
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ])
            ->statePath('data');
    }

    protected function getNameFormComponent(): TextInput
    {
        return TextInput::make('name')
            ->label('Full Name')
            ->required()
            ->maxLength(255)
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }

    protected function getEmailFormComponent(): TextInput
    {
        return TextInput::make('email')
            ->label('Email Address')
            ->email()
            ->required()
            ->maxLength(255)
            ->unique(User::class)
            ->extraInputAttributes(['tabindex' => 2]);
    }

    protected function getPasswordFormComponent(): TextInput
    {
        return TextInput::make('password')
            ->label('Password')
            ->password()
            ->required()
            ->rule(Password::default())
            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
            ->same('passwordConfirmation')
            ->validationAttribute('Password')
            ->extraInputAttributes(['tabindex' => 3]);
    }

    protected function getPasswordConfirmationFormComponent(): TextInput
    {
        return TextInput::make('passwordConfirmation')
            ->label('Confirm Password')
            ->password()
            ->required()
            ->dehydrated(false)
            ->extraInputAttributes(['tabindex' => 4]);
    }

    public function register(): ?RegistrationResponse
    {
        $data = $this->form->getState();

        $user = User::create($data);

        event(new Registered($user));

        Filament::auth()->login($user);

        session()->regenerate();

        return app(RegistrationResponse::class);
    }

    protected function getRedirectUrl(): string
    {
        return $this->panel->getUrl();
    }

    public function getTitle(): string
    {
        return 'Create Account';
    }

    public function getHeading(): string
    {
        return 'Join Our Laundry Service';
    }

    public function getSubheading(): string
    {
        return 'Create your account to track your orders and payments.';
    }
}