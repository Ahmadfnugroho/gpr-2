<?php

namespace App\Filament\Resources\UserPhoneNumberResource\Pages;

use App\Filament\Resources\UserPhoneNumberResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserPhoneNumber extends EditRecord
{
    protected static string $resource = UserPhoneNumberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
