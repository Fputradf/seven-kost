<?php

namespace App\Filament\Superadmin\Resources\Transactions\Tables;

use App\Models\Transaction;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reference_id')
                    ->label('Kode Invoice')
                    ->searchable()
                    ->copyable()
                    ->weight('bold')
                    ->description(fn (Transaction $record): string => $record->created_at ? $record->created_at->format('d M Y, H:i') : '-'),

                TextColumn::make('user.name')
                    ->label('Penghuni / Pembayar')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold')
                    ->description(fn (Transaction $record): ?string => $record->user?->phone ?: $record->user?->email),

                TextColumn::make('type')
                    ->label('Kategori')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'service' => 'Layanan Kamar',
                        'booking' => 'Sewa Kamar',
                        'wallet' => 'Topup Dompet',
                        'banner' => 'Iklan Banner',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'service' => 'info',
                        'booking' => 'primary',
                        'wallet' => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('title')
                    ->label('Item / Pesanan')
                    ->limit(24)
                    ->tooltip(fn (Transaction $record): string => $record->title),

                TextColumn::make('amount')
                    ->label('Total Biaya')
                    ->money('IDR', locale: 'id')
                    ->weight('black')
                    ->sortable(),

                TextColumn::make('payment_channel')
                    ->label('Metode')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'manual' => 'Transfer / QRIS Manual',
                        'wallet' => 'Saldo Dompet',
                        'xendit' => 'Xendit Otomatis',
                        default => ucfirst($state ?? 'Manual'),
                    })
                    ->color(fn (?string $state): string => match ($state) {
                        'wallet' => 'success',
                        'manual' => 'warning',
                        'xendit' => 'info',
                        default => 'gray',
                    }),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state, Transaction $record): string => match ($state) {
                        'paid' => 'Lunas',
                        'pending' => (($record->payload['payment_confirmation_status'] ?? null) === 'awaiting_confirmation') ? 'Perlu Verifikasi' : 'Menunggu Bayar',
                        'failed' => (($record->payload['service_order_status'] ?? null) === 'cancelled') ? 'Dibatalkan (Refund)' : 'Gagal',
                        'expired' => 'Kedaluwarsa',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state, Transaction $record): string => match ($state) {
                        'paid' => 'success',
                        'pending' => (($record->payload['payment_confirmation_status'] ?? null) === 'awaiting_confirmation') ? 'warning' : 'gray',
                        'failed' => 'danger',
                        default => 'gray',
                    }),

                ImageColumn::make('payload.payment_proof_image')
                    ->label('Bukti Transfer')
                    ->disk('public')
                    ->size(40)
                    ->circular(false)
                    ->defaultImageUrl(null),

                TextColumn::make('payload.payment_confirmation_status')
                    ->label('Verifikasi Bukti')
                    ->badge()
                    ->formatStateUsing(fn (?string $state, Transaction $record): string => match ($state) {
                        'awaiting_confirmation' => 'Menunggu Verifikasi',
                        'verified' => 'Terverifikasi',
                        'rejected' => 'Bukti Ditolak',
                        default => $record->isPaid() ? 'Otomatis Lunas' : 'Belum Ada Bukti',
                    })
                    ->color(fn (?string $state, Transaction $record): string => match ($state) {
                        'awaiting_confirmation' => 'warning',
                        'verified' => 'success',
                        'rejected' => 'danger',
                        default => $record->isPaid() ? 'success' : 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Pembayaran')
                    ->options([
                        'pending' => 'Menunggu Bayar / Verifikasi',
                        'paid' => 'Lunas',
                        'failed' => 'Gagal / Dibatalkan',
                        'expired' => 'Kedaluwarsa',
                    ]),
                SelectFilter::make('type')
                    ->label('Kategori')
                    ->options([
                        'service' => 'Layanan Kamar Kos',
                        'booking' => 'Sewa Kamar',
                        'wallet' => 'Topup Dompet',
                    ]),
            ])
            ->recordActions([
                Action::make('verifyPayment')
                    ->label('Verifikasi Lunas')
                    ->icon(Heroicon::OutlinedCheckCircle)
                    ->color('success')
                    ->button()
                    ->size('sm')
                    ->requiresConfirmation()
                    ->modalHeading('Verifikasi Pembayaran Manual')
                    ->modalDescription(fn (Transaction $record): string => "Konfirmasi pelunasan pembayaran transaksi #{$record->reference_id} sebesar Rp ".number_format($record->amount, 0, ',', '.').'? Status pesanan akan otomatis berubah menjadi Lunas dan diproses.')
                    ->modalSubmitActionLabel('Ya, Konfirmasi Lunas')
                    ->visible(fn (Transaction $record): bool => ! $record->isPaid())
                    ->action(function (Transaction $record): void {
                        $payload = $record->payload ?? [];
                        $payload['payment_confirmation_status'] = 'verified';
                        $payload['verified_at'] = now()->toDateTimeString();
                        $payload['verified_by'] = auth()->user()?->name ?? 'Superadmin';

                        $record->update([
                            'status' => 'paid',
                            'paid_at' => now(),
                            'payload' => $payload,
                        ]);

                        Notification::make()
                            ->title('Pembayaran Berhasil Diverifikasi')
                            ->body("Transaksi #{$record->reference_id} berhasil disetujui dan berstatus LUNAS.")
                            ->success()
                            ->send();
                    }),

                Action::make('rejectProof')
                    ->label('Tolak Bukti')
                    ->icon(Heroicon::OutlinedXCircle)
                    ->color('danger')
                    ->button()
                    ->size('sm')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Bukti Pembayaran')
                    ->modalDescription(fn (Transaction $record): string => "Tolak bukti transfer untuk transaksi #{$record->reference_id}? Penghuni akan diminta mengunggah ulang bukti pembayaran yang valid.")
                    ->modalSubmitActionLabel('Tolak Bukti')
                    ->visible(fn (Transaction $record): bool => ! $record->isPaid() && (($record->payload['payment_confirmation_status'] ?? null) === 'awaiting_confirmation'))
                    ->action(function (Transaction $record): void {
                        $payload = $record->payload ?? [];
                        $payload['payment_confirmation_status'] = 'rejected';
                        $payload['rejected_at'] = now()->toDateTimeString();
                        $payload['rejected_by'] = auth()->user()?->name ?? 'Superadmin';

                        $record->update([
                            'payload' => $payload,
                        ]);

                        Notification::make()
                            ->title('Bukti Pembayaran Ditolak')
                            ->body("Bukti transfer untuk transaksi #{$record->reference_id} telah ditolak.")
                            ->warning()
                            ->send();
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->searchPlaceholder('Cari invoice, nama penghuni, item...')
            ->defaultPaginationPageOption(10)
            ->paginationPageOptions([10])
            ->emptyStateHeading('Belum Ada Transaksi')
            ->emptyStateDescription('Daftar transaksi pembayaran layanan dan booking sewa akan tercantum di sini.');
    }
}
