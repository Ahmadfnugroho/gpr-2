<?php

namespace App\Filament\Resources\RentalIncludeResource\Pages;

use App\Filament\Resources\RentalIncludeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRentalInclude extends EditRecord
{
    protected static string $resource = RentalIncludeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
