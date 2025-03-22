<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Filament\Resources\PaymentResource\RelationManagers;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('order_id')
                    ->relationship('order', 'id')
                    ->required(),
                Forms\Components\TextInput::make('payment_method')
                    ->default('manual')
                    ->required(),
                Forms\Components\Select::make('payment_status')
                    ->options([
                        'pending' => 'Menunggu Pembayaran',
                        'processing' => 'Menunggu Konfirmasi',
                        'completed' => 'Pembayaran Berhasil',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('stripe_payment_id')
                    ->hidden(fn ($record) => $record && $record->payment_method !== 'stripe'),
                Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('order.id')
                    ->label('Order ID'),
                Tables\Columns\TextColumn::make('payment_method'),
                BadgeColumn::make('payment_status')
                    ->colors([
                        'danger' => 'pending',
                        'warning' => 'processing',
                        'success' => 'completed',
                    ])
                    ->label('Status Pembayaran'),
                // Memperbaiki konfigurasi kolom gambar - menghapus method directory() yang tidak ada
                ImageColumn::make('payment_proof')
                    ->label('Bukti Pembayaran')
                    ->disk('public')  // Pastikan menggunakan disk publik
                    ->square()        // Mengatur gambar dalam bentuk kotak
                    ->size(100)       // Ukuran gambar
                    ->visibility('public') // Memastikan gambar dapat diakses secara publik
                    ->extraImgAttributes(['class' => 'object-cover']),

                Tables\Columns\TextColumn::make('amount')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y H:i'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Memperbaiki URL untuk bukti pembayaran
                Action::make('view_proof')
                    ->label('Lihat Bukti')
                    ->color('secondary')
                    ->icon('heroicon-o-photo')
                    ->url(fn (Payment $record) => $record->payment_proof ? asset('storage/' . $record->payment_proof) : null)
                    ->openUrlInNewTab()
                    ->visible(fn (Payment $record) => !empty($record->payment_proof)),
                Action::make('approve_payment')
                    ->label('Terima')
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->action(function (Payment $record) {
                        // Update status pembayaran
                        $record->update(['payment_status' => 'completed']);

                        // Update status order juga jadi completed
                        $order = $record->order;
                        if ($order) {
                            $order->update(['status' => 'completed']);

                            // Tampilkan notifikasi
                            Notification::make()
                                ->title('Pembayaran dan Order berhasil diperbarui')
                                ->success()
                                ->send();
                        }
                    })
                    ->visible(fn (Payment $record) => $record->payment_status === 'processing'),
                Action::make('process_payment')
                    ->label('Proses')
                    ->color('warning')
                    ->icon('heroicon-o-clock')
                    ->action(fn (Payment $record) => $record->update(['payment_status' => 'processing']))
                    ->visible(fn (Payment $record) => $record->payment_status === 'pending'),
                Action::make('pending_payment')
                    ->label('Pending')
                    ->color('danger')
                    ->icon('heroicon-o-x-mark')
                    ->action(function (Payment $record) {
                        // Update status pembayaran
                        $record->update(['payment_status' => 'pending']);

                        // Update status order juga jadi pending
                        $order = $record->order;
                        if ($order) {
                            $order->update(['status' => 'pending']);

                            // Tampilkan notifikasi
                            Notification::make()
                                ->title('Status diubah ke Pending')
                                ->warning()
                                ->send();
                        }
                    })
                    ->visible(fn (Payment $record) => $record->payment_status === 'processing' || $record->payment_status === 'completed'),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
            'view' => Pages\ViewPayment::route('/{record}'),
        ];
    }
}
