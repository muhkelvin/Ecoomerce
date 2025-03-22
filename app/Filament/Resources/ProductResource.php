<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    // Nama produk
                    Forms\Components\TextInput::make('name')
                        ->label('Nama Produk')
                        ->required()
                        ->maxLength(255),
                    // Deskripsi produk
                    Forms\Components\Textarea::make('description')
                        ->label('Deskripsi')
                        ->rows(3)
                        ->maxLength(65535),
                    // Harga produk
                    Forms\Components\TextInput::make('price')
                        ->label('Harga')
                        ->numeric()
                        ->required(),
                    // Stok produk (inventory)
                    Forms\Components\TextInput::make('inventory')
                        ->label('Stok')
                        ->numeric()
                        ->required(),
                    // Upload gambar produk
                    Forms\Components\FileUpload::make('image')
                        ->label('Gambar Produk')
                        ->image()
                        ->directory('products')
                        ->visibility('public')
                        ->imageResizeMode('cover')
                        ->imageCropAspectRatio('1:1')
                        ->imageResizeTargetWidth('500')
                        ->imageResizeTargetHeight('500')
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Kolom ID produk
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),
                // Kolom gambar produk
                Tables\Columns\ImageColumn::make('image')
                    ->label('Gambar')
                    ->circular(),
                // Kolom nama produk
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Produk')
                    ->sortable()
                    ->searchable(),
                // Kolom harga dengan format uang
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR', true)
                    ->sortable(),
                // Kolom stok produk
                Tables\Columns\TextColumn::make('inventory')
                    ->label('Stok')
                    ->sortable(),
                // Kolom tanggal pembuatan
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime('d M Y'),
            ])
            ->filters([
                // Tambahkan filter jika diperlukan
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
            // Tambahkan relation managers jika diperlukan
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit'   => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
