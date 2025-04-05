<?php

namespace App\Filament\Resources\ProductSpecificationResource\Pages;

use App\Filament\Resources\ProductSpecificationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductSpecification extends EditRecord
{
    protected static string $resource = ProductSpecificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
