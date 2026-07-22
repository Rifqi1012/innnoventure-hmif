<?php

namespace App\Filament\Resources\DaftarSeminarResource\Pages;

use App\Filament\Resources\DaftarSeminarResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDaftarSeminar extends EditRecord
{
    protected static string $resource = DaftarSeminarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
