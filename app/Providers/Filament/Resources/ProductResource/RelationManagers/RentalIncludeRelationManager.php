<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\RentalInclude;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RentalIncludeRelationManager extends RelationManager
{
    protected static string $relationship = 'rentalIncludes';

    public function form(Form $form): Form
    {
        return $form
                ->schema([
                    Forms\Components\Select::make('include_product_id')
                        ->label('Produk yang Di-include')
                        ->relationship('includedProduct', 'name') // Relasi dengan produk yang di-include
                        ->searchable()
                        ->preload()
                        ->required(),
                ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Rental Include')
            ->columns([
                Tables\Columns\TextColumn::make('includedProduct.name')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}

