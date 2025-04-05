<?php

namespace App\Filament\Resources\UserPhotoResource\Pages;

use App\Filament\Resources\UserPhotoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserPhoto extends EditRecord
{
    protected static string $resource = UserPhotoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
