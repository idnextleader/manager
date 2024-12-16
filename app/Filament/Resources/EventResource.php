<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')->label('Title')->required(),
                RichEditor::make('content')->label('Content')->columnSpan(2)->required(),
                TextInput::make('description')->label('Description')->required(),

                // Repeater untuk menambah gambar
                Repeater::make('images')
                    ->relationship('images') // Relasi ke model Image
                    ->schema([
                        FileUpload::make('image')
                            ->label('Upload Image')
                            ->image() // Tipe file gambar
                            ->required()
                            ->directory('event/images') // Tentukan direktori penyimpanan
                            ->helperText('Upload multiple images for the article'),
                    ])
                    ->createItemButtonLabel('Tambah Gambar')
                    ->columns(1), // Atur kolom sesuai kebutuhan
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Title'),
                // TextColumn::make('content')->label('Content'),
                // Menampilkan gambar pertama dari relasi 'images'
                ImageColumn::make('images')  // Gunakan 'images' yang mengacu pada relasi
                    ->label('Image')
                    ->getStateUsing(function ($record) {
                        // Ambil gambar pertama dari relasi images dan tampilkan path-nya
                        return $record->images->first()?->image;
                    }),
                TextColumn::make('description')->label('Description'),
            ])
            ->filters([
                // Filters jika diperlukan
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

    // public static function table(Table $table): Table
    // {
    //     return $table
    //         ->columns([
    //             //
    //         ])
    //         ->filters([
    //             //
    //         ])
    //         ->actions([
    //             Tables\Actions\EditAction::make(),
    //         ])
    //         ->bulkActions([
    //             Tables\Actions\BulkActionGroup::make([
    //                 Tables\Actions\DeleteBulkAction::make(),
    //             ]),
    //         ]);
    // }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
