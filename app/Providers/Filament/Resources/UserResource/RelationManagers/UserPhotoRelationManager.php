<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use App\Models\UserPhoto;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;

class UserPhotoRelationManager extends RelationManager
{
    protected static string $relationship = 'userPhotos';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('photo')
                ->image()    
                ->required(),

                Forms\Components\Select::make('photo_type')
                    ->options([
                        'Kartu Keluarga' => 'Kartu Keluarga',
                        'SIM' => 'SIM',
                        'NPWP' => 'NPWP',
                        'STNK' => 'STNK',
                        'BPKB' => 'BPKB',
                        'Passport' => 'Passport',
                        'BPJS' => 'BPJS',
                        'ID Card Kerja' => 'ID Card Kerja',
                        'KTP' => 'KTP',
                        'Screenshot Follow' => 'Screenshot Follow',   
                    ])
                    ->nullable(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('User Photo')
            ->columns([
                Tables\Columns\ImageColumn::make('photo'),
                Tables\Columns\TextColumn::make('photo_type'),
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
