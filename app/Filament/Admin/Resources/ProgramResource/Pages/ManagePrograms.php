<?php

namespace App\Filament\Admin\Resources\ProgramResource\Pages;

use App\Filament\Admin\Resources\ProgramResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePrograms extends ManageRecords
{
    protected static string $resource = ProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()    
                ->slideOver()
                ->modalWidth('10xl'),
        ];
    }
}
