<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryTeamResource\Pages;
use App\Filament\Resources\CategoryTeamResource\RelationManagers;
use App\Models\CategoryTeam;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryTeamResource extends Resource
{
    protected static ?string $model = CategoryTeam::class;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';

    protected static ?string $navigationGroup = 'Manager Team';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('Nama Kategori')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Kategori'),
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
            'index' => Pages\ListCategoryTeams::route('/'),
            'create' => Pages\CreateCategoryTeam::route('/create'),
            'edit' => Pages\EditCategoryTeam::route('/{record}/edit'),
        ];
    }
}
