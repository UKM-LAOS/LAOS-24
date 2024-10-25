<?php

namespace App\Filament\Admin\Resources\CourseReviewResource\Pages;

use App\Filament\Admin\Resources\CourseReviewResource;
use App\Models\CourseReview;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use IbrahimBougaoua\FilamentRatingStar\Columns\Components\RatingStar;

class CourseReviewPage extends Page implements HasTable
{
    use InteractsWithTable;

    public $courseId;

    public function mount()
    {
        $this->courseId = request()->route('record');
    }

    protected static string $resource = CourseReviewResource::class;

    protected static string $view = 'filament.admin.resources.course-review-resource.pages.course-review-page';

    public function table(Table $table): Table
    {
        return $table
            ->query(CourseReview::query()->with('user')->whereCourseId($this->courseId))
            ->columns([
                Split::make([
                    TextColumn::make('user.name')
                        ->label('Nama Pembeli'),
                    Stack::make([
                        RatingStar::make('rating')
                            ->size('sm'),
                        TextColumn::make('note')
                    ])
                ])
            ]);
    }
}
