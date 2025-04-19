<?php

namespace App\Filament\Resources\TamuResource\Pages;

use App\Filament\Resources\TamuResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTamus extends ListRecords
{
    protected static string $resource = TamuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
