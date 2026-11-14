<?php

namespace App\Filament\Superadmin\Resources\Users\Pages;

use App\Filament\Superadmin\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Alignment;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected string $view = 'filament.superadmin.resources.users.pages.create-user';

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
                ->label('Simpan Pengguna Baru')
                ->color('primary')
                ->icon('heroicon-m-check'),
        ];
    }
}
