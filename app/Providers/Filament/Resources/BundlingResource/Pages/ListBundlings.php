<?php

namespace App\Filament\Resources\BundlingResource\Pages;

use App\Filament\Resources\BundlingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBundlings extends ListRecords
{
    protected static string $resource = BundlingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
