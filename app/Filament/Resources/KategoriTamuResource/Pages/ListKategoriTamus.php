<?php

namespace App\Filament\Resources\KategoriTamuResource\Pages;

use App\Filament\Resources\KategoriTamuResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKategoriTamus extends ListRecords
{
    protected static string $resource = KategoriTamuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
