<?php

namespace App\Filament\Resources\KategoriTamuResource\Pages;

use App\Filament\Resources\KategoriTamuResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKategoriTamu extends EditRecord
{
    protected static string $resource = KategoriTamuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
