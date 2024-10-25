<?php

namespace App\Filament\Mentor\Resources\TransactionResource\Pages;

use App\Filament\Mentor\Resources\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTransactions extends ManageRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
