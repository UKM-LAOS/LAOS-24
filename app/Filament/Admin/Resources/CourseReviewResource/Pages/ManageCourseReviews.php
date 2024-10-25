<?php

namespace App\Filament\Admin\Resources\CourseReviewResource\Pages;

use App\Filament\Admin\Resources\CourseReviewResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCourseReviews extends ManageRecords
{
    protected static string $resource = CourseReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
