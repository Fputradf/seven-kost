<?php

namespace App\Filament\Superadmin\Resources\Banners\Pages;

use App\Filament\Superadmin\Resources\Banners\BannerResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\Facades\Auth;

class CreateBanner extends CreateRecord
{
    protected static string $resource = BannerResource::class;

    protected string $view = 'filament.superadmin.resources.banners.pages.create-banner';

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

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();
        $data['advertiser_name'] = 'Super Admin';

        return $data;
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCancelFormAction()
                ->label('Batal')
                ->color('gray')
                ->url(static::getResource()::getUrl('index')),

            $this->getCreateFormAction()
                ->label('Simpan Banner')
                ->color('primary')
                ->icon('heroicon-m-check'),
        ];
    }
}
