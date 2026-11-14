<?php

namespace App\Filament\Superadmin\Resources\Bookings\Pages;

use App\Filament\Superadmin\Resources\Bookings\BookingResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Alignment;

class CreateBooking extends CreateRecord
{
    protected static string $resource = BookingResource::class;

    protected string $view = 'filament.superadmin.resources.bookings.pages.create-booking';

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

            $this->getCreateFormAction()
                ->label('Simpan Booking Baru')
                ->color('primary')
                ->icon('heroicon-m-check'),
        ];
    }
}
