<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\AkunPesertaMail;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
    protected ?string $generatedPassword = null;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->generatedPassword = Str::random(12);
        $data['password'] = $this->generatedPassword;
        $data['raw_password'] = $this->generatedPassword;

        return $data;
    }

    protected function getCreatedNotification(): ?\Filament\Notifications\Notification
    {
        return \Filament\Notifications\Notification::make()
            ->success()
            ->title('User Berhasil Dibuat')
            ->body('Email: ' . $this->record->email . '<br>Password: <strong>' . $this->generatedPassword . '</strong><br><br>⚠️ <i>Silakan salin password ini dan berikan ke peserta. Password tidak dikirim via email.</i>')
            ->persistent();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
