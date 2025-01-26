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
use Illuminate\Support\Facades\Storage;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationGroup = 'Manager Content';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')->label('Title')->required(),
                TextInput::make('description')->label('Description')->required(),
                RichEditor::make('content')->label('Content')->columnSpan(2)->required(),

                // Repeater untuk menambah gambar
                Repeater::make('images')
                    ->relationship('images') // Relasi ke model Image
                    ->schema([
                        FileUpload::make('image')
                            ->label('Upload Image')
                            ->image() // Tipe file gambar
                            ->required()
                            ->directory('event/images') // Tentukan direktori penyimpanan
                            ->helperText('Upload multiple images for the event'),
                    ])
                    ->createItemButtonLabel('Tambah Gambar')
                    ->columns(1)->columnSpan(2), // Atur kolom sesuai kebutuhan
            ]);
    }

    // public static function afterSave($record, array $data)
    // {
    //     // Periksa apakah event sudah memiliki gambar
    //     if (isset($data['images'])) {
    //         // Ambil gambar yang sudah ada di relasi 'images'
    //         $existingImages = $record->images;

    //         // Ambil ID gambar baru dari data
    //         $newImages = collect($data['images'])->pluck('id')->toArray();

    //         // Hapus gambar yang sudah tidak digunakan
    //         $imagesToDelete = $existingImages->whereNotIn('id', $newImages);
    //         foreach ($imagesToDelete as $image) {
    //             // Hapus gambar di storage
    //             Storage::delete($image->image); // Hapus file gambar dari storage
    //             $image->delete(); // Hapus gambar dari database
    //         }

    //         // Simpan gambar baru atau yang diperbarui
    //         foreach ($data['images'] as $imageData) {
    //             // Jika gambar tidak ada di database (ID null), buat entri baru
    //             if (!isset($imageData['id'])) {
    //                 // Buat entri baru di tabel event_images
    //                 $record->images()->create(['image' => $imageData['image']]);
    //             } else {
    //                 // Jika gambar sudah ada di database (ID ada), perbarui gambar tersebut
    //                 $image = $record->images()->find($imageData['id']);
    //                 if ($image) {
    //                     $image->update(['image' => $imageData['image']]);
    //                 }
    //             }
    //         }
    //     }
    // }
    public static function afterSave($record, array $data)
    {
        // Periksa apakah artikel sudah memiliki gambar
        if (isset($data['images'])) {
            $existingImages = $record->images; // Ambil gambar yang sudah ada
            $newImages = collect($data['images'])->pluck('id')->toArray(); // ID gambar baru dari form

            // Hapus gambar yang sudah tidak digunakan
            $imagesToDelete = $existingImages->whereNotIn('id', $newImages);
            foreach ($imagesToDelete as $image) {
                // Hapus gambar di storage
                Storage::delete($image->image); // Hapus file gambar dari storage
                $image->delete(); // Hapus gambar dari database
            }

            // Simpan gambar baru atau yang diperbarui
            foreach ($data['images'] as $imageData) {
                // Jika gambar tidak ada di database, buat entri baru
                if (!isset($imageData['id'])) {
                    $record->images()->create(['image' => $imageData['image']]);
                }
            }
        }
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
