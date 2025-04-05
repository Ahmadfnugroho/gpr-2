<?php

namespace App\Filament\Resources\UserPhoneNumberResource\Pages;

use App\Filament\Resources\UserPhoneNumberResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserPhoneNumbers extends ListRecords
{
    protected static string $resource = UserPhoneNumberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
