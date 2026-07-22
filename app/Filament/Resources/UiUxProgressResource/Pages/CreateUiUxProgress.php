<?php

namespace App\Filament\Resources\UiUxProgressResource\Pages;

use App\Filament\Resources\UiUxProgressResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUiUxProgress extends CreateRecord
{
    protected static string $resource = UiUxProgressResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
