<?php

namespace App\Filament\Superadmin\Resources\Transactions\Pages;

use App\Filament\Superadmin\Resources\Transactions\TransactionResource;
use Filament\Resources\Pages\ListRecords;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    protected string $view = 'filament.superadmin.resources.transactions.pages.list-transactions';

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
}
