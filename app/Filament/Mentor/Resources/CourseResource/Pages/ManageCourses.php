<?php

namespace App\Filament\Mentor\Resources\CourseResource\Pages;

use App\Filament\Mentor\Resources\CourseResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCourses extends ManageRecords
{
    protected static string $resource = CourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->slideOver()
                ->modalWidth('10xl'),
        ];
    }
}