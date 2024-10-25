<?php

namespace App\Filament\Mentor\Resources\CourseResource\Pages;

use App\Filament\Mentor\Resources\CourseResource;
use App\Models\CourseReview;
use Filament\Resources\Pages\Page;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
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

    protected static string $resource = CourseResource::class;

    protected static string $view = 'filament.mentor.resources.course-resource.pages.course-review-page';

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
                            ->size('sm')
                            ->sortable(),
                        TextColumn::make('note')
                    ])
                ])
            ]);
    }
}
