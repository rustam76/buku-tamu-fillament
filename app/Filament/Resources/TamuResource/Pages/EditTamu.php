<?php

namespace App\Filament\Resources\TamuResource\Pages;

use App\Filament\Resources\TamuResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTamu extends EditRecord
{
    protected static string $resource = TamuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
