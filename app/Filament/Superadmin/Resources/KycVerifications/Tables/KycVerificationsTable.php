<?php

namespace App\Filament\Superadmin\Resources\KycVerifications\Tables;

use App\Models\KycVerification;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class KycVerificationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('submitted_at', 'desc')
            ->striped()
            ->emptyStateHeading('Belum ada pengajuan verifikasi KYC')
            ->emptyStateDescription('Pengajuan verifikasi KTP dan selfie dari pengguna akan muncul di sini.')
            ->columns([
                TextColumn::make('user.name')
                    ->label('Pengguna')
                    ->searchable(['name', 'email'])
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (KycVerification $record): string => Str::limit($record->user?->email ?? '-', 24)),

                TextColumn::make('role')
                    ->label('Peran Akun')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'mitra' => 'Mitra Layanan',
                        'penghuni' => 'Penghuni Kos',
                        'pemilik' => 'Pemilik Kos',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'mitra' => 'info',
                        'penghuni' => 'success',
                        'pemilik' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('nik')
                    ->label('NIK (16 Digit)')
                    ->fontFamily('mono')
                    ->weight('bold')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('NIK disalin ke clipboard'),

                TextColumn::make('full_name')
                    ->label('Nama KTP')
                    ->searchable()
                    ->limit(20)
                    ->tooltip(fn (KycVerification $record): string => $record->full_name),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Menunggu Review',
                        'verified' => 'Terverifikasi',
                        'rejected' => 'Ditolak',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'verified' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('submitted_at')
                    ->label('Diajukan Pada')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Verifikasi')
                    ->options([
                        'pending' => 'Menunggu Review',
                        'verified' => 'Terverifikasi',
                        'rejected' => 'Ditolak',
                    ]),

                SelectFilter::make('role')
                    ->label('Peran Akun')
                    ->options([
                        'mitra' => 'Mitra Layanan',
                        'penghuni' => 'Penghuni Kos',
                        'pemilik' => 'Pemilik Kos',
                    ]),
            ])
            ->recordActions([
                ActionGroup::make([
                    // 1. LIHAT BERKAS KYC
                    Action::make('view_documents')
                        ->label('Lihat Berkas KYC')
                        ->icon(Heroicon::OutlinedEye)
                        ->modalHeading(fn (KycVerification $record): string => 'Dokumen KYC: '.$record->full_name.' (NIK: '.$record->nik.')')
                        ->modalDescription('Periksa keaslian foto KTP, selfie wajah, dan berkas pendukung.')
                        ->modalContent(fn (KycVerification $record): HtmlString => new HtmlString('
                            <div class="space-y-4 text-xs">
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 p-3 rounded-xl bg-slate-50 border border-slate-200">
                                    <div>
                                        <span class="text-[10px] font-bold uppercase text-slate-400 block">Nama KTP</span>
                                        <span class="font-bold text-slate-800">'.e($record->full_name).'</span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] font-bold uppercase text-slate-400 block">NIK</span>
                                        <span class="font-mono font-bold text-slate-800">'.e($record->nik).'</span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] font-bold uppercase text-slate-400 block">Akun / Email</span>
                                        <span class="font-semibold text-slate-700">'.e($record->user?->email ?? '-').'</span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] font-bold uppercase text-slate-400 block">Status</span>
                                        <span class="font-bold text-slate-800">'.e(match ($record->status) {
                            'pending' => 'Menunggu Review',
                            'verified' => 'Terverifikasi',
                            'rejected' => 'Ditolak',
                            default => ucfirst($record->status),
                        }).'</span>
                                    </div>
                                </div>
                                '.($record->rejection_reason ? '
                                <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800">
                                    <span class="font-bold block mb-0.5">Alasan Penolakan Sebelumnya:</span>
                                    <span>'.e($record->rejection_reason).'</span>
                                </div>
                                ' : '').'
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="p-3 border rounded-xl bg-slate-50">
                                        <p class="font-bold text-slate-700 mb-2">Foto KTP Asli:</p>
                                        <a href="'.$record->ktp_url.'" target="_blank">
                                            <img src="'.$record->ktp_url.'" class="w-full h-48 object-cover rounded-lg border hover:opacity-90 transition">
                                        </a>
                                    </div>
                                    <div class="p-3 border rounded-xl bg-slate-50">
                                        <p class="font-bold text-slate-700 mb-2">Foto Selfie Wajah / Pegang KTP:</p>
                                        <a href="'.$record->selfie_url.'" target="_blank">
                                            <img src="'.$record->selfie_url.'" class="w-full h-48 object-cover rounded-lg border hover:opacity-90 transition">
                                        </a>
                                    </div>
                                </div>
                                '.($record->business_document_path ? '
                                <div class="p-3 border rounded-xl bg-blue-50/60">
                                    <p class="font-bold text-blue-900 mb-1">Berkas Pendukung Usaha / Keabsahan:</p>
                                    <p class="text-slate-600 mb-2">'.e($record->business_document_name ?: 'Berkas Pendukung').'</p>
                                    <a href="'.$record->business_document_url.'" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 text-white rounded-lg font-bold text-xs hover:bg-blue-700">
                                        Buka / Unduh Berkas Pendukung &rarr;
                                    </a>
                                </div>
                                ' : '<p class="text-slate-400 italic">Tidak ada berkas pendukung tambahan.</p>').'
                            </div>
                        '))
                        ->modalSubmitAction(false),

                    // 2. SETUJUI VERIFIKASI
                    Action::make('approve_kyc')
                        ->label('Setujui Verifikasi')
                        ->icon(Heroicon::OutlinedCheckCircle)
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Konfirmasi Persetujuan KYC')
                        ->modalDescription('Apakah Anda yakin dokumen identitas pengguna ini sah dan memenuhi syarat verifikasi?')
                        ->visible(fn (KycVerification $record): bool => $record->status !== 'verified')
                        ->action(function (KycVerification $record): void {
                            $record->update([
                                'status' => 'verified',
                                'rejection_reason' => null,
                                'reviewed_at' => now(),
                                'reviewed_by' => Auth::id(),
                            ]);

                            if ($record->user?->servicePartner) {
                                $record->user->servicePartner->update([
                                    'verification_status' => 'verified',
                                ]);
                            }

                            Notification::make()
                                ->title('KYC Berhasil Disetujui')
                                ->body('Akun '.$record->full_name.' resmi terverifikasi.')
                                ->success()
                                ->send();
                        }),

                    // 3. TOLAK VERIFIKASI
                    Action::make('reject_kyc')
                        ->label('Tolak Verifikasi')
                        ->icon(Heroicon::OutlinedXCircle)
                        ->color('danger')
                        ->form([
                            Textarea::make('rejection_reason')
                                ->label('Alasan Penolakan')
                                ->placeholder('Contoh: Foto KTP buram, nama tidak sesuai dengan NIK, atau selfie tidak jelas.')
                                ->required(),
                        ])
                        ->visible(fn (KycVerification $record): bool => $record->status !== 'rejected')
                        ->action(function (KycVerification $record, array $data): void {
                            $record->update([
                                'status' => 'rejected',
                                'rejection_reason' => $data['rejection_reason'],
                                'reviewed_at' => now(),
                                'reviewed_by' => Auth::id(),
                            ]);

                            if ($record->user?->servicePartner) {
                                $record->user->servicePartner->update([
                                    'verification_status' => 'pending',
                                ]);
                            }

                            Notification::make()
                                ->title('KYC Telah Ditolak')
                                ->body('Pemberitahuan penolakan dikirim ke pengguna.')
                                ->warning()
                                ->send();
                        }),
                ]),
            ]);
    }
}
