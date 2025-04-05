<?php

namespace App\Filament\Imports;

use App\Models\UserPhoneNumber;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class UserPhoneNumberImporter extends Importer
{
    protected static ?string $model = UserPhoneNumber::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('user')
                ->requiredMapping()
                ->relationship()
                ->rules(['required']),
            ImportColumn::make('phone_number')
                ->rules(['max:255']),
        ];
    }

    public function resolveRecord(): ?UserPhoneNumber
    {
        // return UserPhoneNumber::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new UserPhoneNumber();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your user phone number import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
