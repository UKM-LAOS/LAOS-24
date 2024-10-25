<?php

namespace App\Filament\Mentor\Resources;

use App\Filament\Mentor\Resources\CourseResource\Pages;
use App\Filament\Mentor\Resources\CourseResource\RelationManagers;
use App\Models\Course;
use App\Models\CourseChapter;
use App\Models\CourseLesson;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Hidden::make('mentor_id')
                        ->default(auth()->id()),
                    Forms\Components\TextInput::make('title')
                        ->label('Judul')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
                        ->maxLength(255),
                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->readOnly()
                        ->maxLength(255),
                    Select::make('course-stacks')
                        ->label('Stacks')
                        ->relationship('courseStacks', 'name')
                        ->multiple()
                        ->searchable()
                        ->preload()
                        ->required()
                        ->createOptionForm([
                            Forms\Components\TextInput::make('name')
                                ->label('Nama Stack')
                                ->unique(ignoreRecord: true)
                                ->required()
                                ->maxLength(255),
                            SpatieMediaLibraryFileUpload::make('course-stack')
                                ->collection('course-stack-image')
                                ->label('Gambar Stack')
                                ->image()
                                ->rules('max:1024|image|mimes:jpeg,png,jpg')
                                ->required(),

                        ]),
                    SpatieMediaLibraryFileUpload::make('thumbnail')
                        ->label('Thumbnail')
                        ->required()
                        ->image()
                        ->rules('image', 'max:1024', 'mimes:png,jpg,jpeg')
                        ->collection('course-thumbnail'),
                    Select::make('course_category_id')
                        ->label('Kategori')
                        ->relationship('courseCategory', 'name')
                        ->required()
                        ->createOptionForm([
                            Forms\Components\TextInput::make('name')
                                ->label('Nama Kategori')
                                ->unique(ignoreRecord: true)
                                ->required()
                                ->maxLength(255),
                            SpatieMediaLibraryFileUpload::make('course-category')
                                ->collection('course-category-image')
                                ->label('Gambar Kategori (SVG)')
                                ->image()
                                ->rules('max:1024|image|mimes:svg')
                                ->required(),

                        ]),
                    Select::make('type')
                        ->label('Tipe')
                        ->options([
                            'free' => 'Gratis',
                            'premium' => 'Premium',
                        ])
                        ->required()
                        ->live()
                        ->afterStateUpdated(fn(Set $set, ?string $state) => $set('price', $state === 'free' ? 0 : null)),
                    Select::make('level')
                        ->label('Level')
                        ->options([
                            'all-level' => 'Semua Level',
                            'beginner' => 'Pemula',
                            'intermediate' => 'Menengah',
                            'advanced' => 'Lanjutan',
                        ])
                        ->required(),
                    Forms\Components\TextInput::make('price')
                        ->label('Harga')
                        ->required()
                        ->numeric()
                        ->minValue(0)
                        ->prefix('Rp'),
                    Forms\Components\RichEditor::make('content')
                        ->label('Konten')
                        ->required(),
                    Forms\Components\TextInput::make('drive_url_resource')
                        ->label('Drive Resource')
                        ->placeholder('https://drive.google.com/...')
                        ->required(),
                    Forms\Components\Toggle::make('is_draft')
                        ->label('Draft')
                        ->required(),
                    SpatieMediaLibraryFileUpload::make('Galeri')
                        ->label('Lampiran (opsional)')
                        ->multiple()
                        ->reorderable()
                        ->image()
                        ->rules('max:5120', 'image', 'mimes:png,jpg,jpeg')
                        ->collection('course-gallery'),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Course::query()->whereMentorId(auth()->id()))
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable(),
                Tables\Columns\TextColumn::make('courseCategory.name')
                    ->label('Kategori'),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->getStateUsing(fn(Course $course) => match ($course->type) {
                        'free' => 'Gratis',
                        'premium' => 'Premium',
                    }),
                Tables\Columns\TextColumn::make('level')
                    ->label('Level')
                    ->getStateUsing(fn(Course $course) => match ($course->level) {
                        'all-level' => 'Semua Level',
                        'beginner' => 'Pemula',
                        'intermediate' => 'Menengah',
                        'advance' => 'Lanjutan',
                    }),
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR')
                    ->weight(FontWeight::SemiBold)
                    ->sortable(),
                Tables\Columns\TextColumn::make('student')
                    ->label('Student')
                    ->getStateUsing(fn(Course $course) => $course->myCourses()->count()),
                ToggleColumn::make('is_draft')
                    ->label('Draft'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->relationship('courseCategory', 'name')
                    ->label('Kategori'),
                SelectFilter::make('type')
                    ->options([
                        'free' => 'Gratis',
                        'premium' => 'Premium',
                    ])
                    ->label('Tipe'),
                SelectFilter::make('level')
                    ->options([
                        'all-level' => 'Semua Level',
                        'beginner' => 'Pemula',
                        'intermediate' => 'Menengah',
                        'advance' => 'Lanjutan',
                    ])
                    ->label('Level'),
                SelectFilter::make('is_draft')
                    ->options([
                        '1' => 'Draft',
                        '0' => 'Published',
                    ])
                    ->label('Status'),
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                Action::make('Review')
                    ->label('Review')
                    ->color('info')
                    ->icon('heroicon-o-star')
                    ->url(fn(Course $course) => Pages\CourseReviewPage::getUrl(['record' => $course->id])),
                Action::make('Manage')
                    ->label('Kelola')
                    ->color('warning')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->url(fn(Course $course) => Pages\CourseChapterLessonPage::getUrl(['record' => $course->id])),
                Tables\Actions\EditAction::make()
                    ->slideOver()
                    ->modalWidth('10xl'),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->action(function (Course $course) {
                        if ($course->myCourses()->exists()) {
                            Notification::make()
                                ->title('Course masih digunakan')
                                ->danger()
                                ->send();
                        } else {
                            CourseLesson::whereHas('chapter', function (Builder $query) use ($course) {
                                $query->whereCourseId($course->id);
                            })->delete();
                            CourseChapter::whereCourseId($course->id)->delete();
                            DB::table('course_course_stacks')->whereCourseId($course->id)->delete();
                            $course->delete();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    BulkAction::make('publish')
                        ->color(Color::Cyan)
                        ->icon('heroicon-o-eye')
                        ->label('Publish')
                        ->action(fn(Collection $records) => $records->each->update(['is_draft' => false])),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCourses::route('/'),
            'reviews' => Pages\CourseReviewPage::route('/{record}/reviews'),
            'chapters-lessons' => Pages\CourseChapterLessonPage::route('/{record}/chapters-lessons'),
        ];
    }
}
