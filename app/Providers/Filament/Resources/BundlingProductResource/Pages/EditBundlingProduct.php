<?php

namespace App\Filament\Resources\BundlingProductResource\Pages;

use App\Filament\Resources\BundlingProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBundlingProduct extends EditRecord
{
    protected static string $resource = BundlingProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
