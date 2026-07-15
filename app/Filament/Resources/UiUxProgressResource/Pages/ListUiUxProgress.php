<?php

namespace App\Filament\Resources\UiUxProgressResource\Pages;

use App\Filament\Resources\UiUxProgressResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUiUxProgress extends ListRecords
{
    protected static string $resource = UiUxProgressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
