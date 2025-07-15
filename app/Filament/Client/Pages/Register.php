<?php

namespace App\Filament\Client\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Filament\Notifications\Notification;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class Register extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static string $view = 'filament.client.pages.register';

    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $role;

    public function mount()
    {
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('name')->required(),
            TextInput::make('email')->email()->required(),
            TextInput::make('password')->password()->required()->minLength(6),
            TextInput::make('password_confirmation')->password()->same('password')->required(),
            Select::make('role')->required()
                ->options([
                    'client' => 'Client',
                    'coach' => 'Coach',
                ]),
        ];
    }

    public function register()
    {
        $data = $this->form->getState();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole($data['role']);

        Notification::make()
            ->title('Account registered successfully!')
            ->success()
            ->send();

        return redirect()->route('filament.' . $data['role'] . '.auth.login');
    }

    protected function getFormModel(): string
    {
        return User::class;
    }
}
