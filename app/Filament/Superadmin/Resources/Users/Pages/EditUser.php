<?php

namespace App\Filament\Superadmin\Resources\Users\Pages;

use App\Filament\Superadmin\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\Facades\Auth;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected string $view = 'filament.superadmin.resources.users.pages.edit-user';

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
                ->label('Hapus Akun')
                ->color('danger')
                ->icon('heroicon-m-trash')
                ->requiresConfirmation()
                ->modalHeading('Hapus Akun Pengguna')
                ->modalDescription('Apakah Anda yakin ingin menghapus akun ini dari sistem Seven Kost? Tindakan ini tidak dapat dibatalkan.')
                ->modalSubmitActionLabel('Ya, Hapus Akun')
                ->visible(fn (): bool => $this->getRecord()->id !== Auth::id() && ! $this->getRecord()->hasRole('superadmin')),

            $this->getSaveFormAction()
                ->label('Simpan Perubahan')
                ->color('primary')
                ->icon('heroicon-m-check'),
        ];
    }
}
