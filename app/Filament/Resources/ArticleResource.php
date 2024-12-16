<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Filament\Resources\ArticleResource\RelationManagers;
use App\Models\Article;
use App\Models\Images;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
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

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                            ->directory('articles/images') // Tentukan direktori penyimpanan
                            ->helperText('Upload multiple images for the article'),
                    ])
                    ->createItemButtonLabel('Tambah Gambar')
                    ->columns(1)->columnSpan(2), // Atur kolom sesuai kebutuhan

                Section::make('Dibuat Oleh')->schema([
                    TextInput::make('published_by')
                        ->label('Nama')
                        ->required(),
                    TextInput::make('published_position')
                        ->label('Jabatan')
                        ->required(),
                    FileUpload::make('published_image')
                        ->label('Foto')
                        ->directory('articles/images/published')
                        ->required(),

                ])
            ]);
    }

    // Tangani gambar yang dihapus dan diubah setelah form disimpan
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }
}
