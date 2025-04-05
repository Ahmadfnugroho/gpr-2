<?php

namespace App\Filament\Resources\UserPhotoResource\Pages;

use App\Filament\Resources\UserPhotoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUserPhoto extends CreateRecord
{
    protected static string $resource = UserPhotoResource::class;
}
