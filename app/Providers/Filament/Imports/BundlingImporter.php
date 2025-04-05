<?php

namespace App\Filament\Imports;

use App\Models\Bundling;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class BundlingImporter extends Importer
{
    protected static ?string $model = Bundling::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('price')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            // 'product_id' hanya ada di tabel pivot, jadi tidak dimasukkan sebagai kolom dalam Bundling
            // Anda akan mengimpor dan menyinkronkan produk nanti.
        ];
    }

    public function resolveRecord(): ?Bundling
    {
        // Temukan atau buat bundling berdasarkan nama dan harga
        $bundling = Bundling::firstOrNew([
            'name' => $this->data['name'],
            'price' => $this->data['price'],
        ]);

        // Simpan bundling jika baru dibuat
        $bundling->save();

        // Cek apakah ada 'product_id' dan jika ada, sinkronkan ke tabel pivot
        if (!empty($this->data['product_id'])) {
            // Pastikan 'product_id' disinkronkan ke pivot table (bundling_products)
            $bundling->products()->syncWithoutDetaching([$this->data['product_id']]);
        }

        return $bundling;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your bundling import has completed and ' . number_format($import->successful_rows) . ' rows imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' rows failed to import.';
        }

        return $body;
    }
}
