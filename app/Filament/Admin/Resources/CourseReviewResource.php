<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CourseReviewResource\Pages;
use App\Filament\Admin\Resources\CourseReviewResource\Pages\CourseReviewPage;
use App\Filament\Admin\Resources\CourseReviewResource\RelationManagers;
use App\Models\Course;
use App\Models\CourseReview;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CourseReviewResource extends Resource
{
    protected static ?string $model = CourseReview::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Course::query()->with(['courseReviews', 'courseCategory']))
            ->columns([
                TextColumn::make('title')
                    ->label('Nama Kursus'),
                TextColumn::make('courseCategory.name')
                    ->label('Kategori Kursus'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('reviews')
                    ->label('Reviews')
                    ->icon('heroicon-o-star')
                    ->color('primary')
                    ->url(fn(CourseReview $record) => CourseReviewPage::getUrl(['record' => $record->id])),
            ])
            ->bulkActions([
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCourseReviews::route('/'),
            'reviews' => Pages\CourseReviewPage::route('/{record}/reviews'),
        ];
    }
}
