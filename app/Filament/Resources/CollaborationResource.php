<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CollaborationResource\Pages;
use App\Filament\Resources\CollaborationResource\RelationManagers;
use App\Models\Collaboration;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CollaborationResource extends Resource
{
    protected static ?string $model = Collaboration::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube-transparent';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('Nama')->required(),
                FileUpload::make('image')
                    ->label('Logo')
                    ->directory('collaborations/images')
                    ->disk('public')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Title'),
                TextColumn::make('position')->label('Content'),
                ImageColumn::make('image')  // Gunakan 'image' untuk kolom gambar
                    ->disk('public')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCollaborations::route('/'),
            'create' => Pages\CreateCollaboration::route('/create'),
            'edit' => Pages\EditCollaboration::route('/{record}/edit'),
        ];
    }
}
