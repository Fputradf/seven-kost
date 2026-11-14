<?php

namespace App\Filament\Superadmin\Resources\Bookings\Pages;

use App\Filament\Superadmin\Resources\Bookings\BookingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Alignment;

class EditBooking extends EditRecord
{
    protected static string $resource = BookingResource::class;

    protected string $view = 'filament.superadmin.resources.bookings.pages.edit-booking';

    public function getHeading(): string
    {
        return '';
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getFormActionsAlignment(): Alignment
    {
        return Alignment::Start;
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCancelFormAction()
                ->label('Batal')
                ->color('gray')
                ->url(static::getResource()::getUrl('index')),

            DeleteAction::make()
                ->label('Hapus Booking')
                ->color('danger')
                ->icon('heroicon-m-trash')
                ->requiresConfirmation()
                ->modalHeading('Hapus Data Booking')
                ->modalDescription('Apakah Anda yakin ingin menghapus data booking sewa ini?')
                ->modalSubmitActionLabel('Ya, Hapus'),

            $this->getSaveFormAction()
                ->label('Simpan Perubahan')
                ->color('primary')
                ->icon('heroicon-m-check'),
        ];
    }
}
