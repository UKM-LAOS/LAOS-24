<?php

namespace App\Filament\Admin\Resources\CourseCategoryResource\Pages;

use App\Filament\Admin\Resources\CourseCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCourseCategories extends ManageRecords
{
    protected static string $resource = CourseCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->slideOver()
                ->closeModalByClickingAway(false)
                ->modalWidth('10xl'),
        ];
    }
}
