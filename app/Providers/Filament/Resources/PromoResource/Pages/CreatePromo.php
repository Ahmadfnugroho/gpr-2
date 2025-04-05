<?php

namespace App\Filament\Resources\PromoResource\Pages;

use App\Filament\Resources\PromoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;

class CreatePromo extends CreateRecord
{
    protected static string $resource = PromoResource::class;
    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }


}
