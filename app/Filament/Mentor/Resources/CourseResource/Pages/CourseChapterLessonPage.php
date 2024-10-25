<?php

namespace App\Filament\Mentor\Resources\CourseResource\Pages;

use App\Filament\Mentor\Resources\CourseResource;
use App\Models\CourseChapter;
use App\Models\CourseLesson;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class CourseChapterLessonPage extends Page implements HasTable, HasForms, HasActions
{
    use InteractsWithTable, InteractsWithForms, InteractsWithActions;

    public $courseId;

    public function mount()
    {
        $this->courseId = request()->route('record');
    }

    protected static string $resource = CourseResource::class;

    protected static string $view = 'filament.mentor.resources.course-resource.pages.course-chapter-lesson-page';

    public function table(Table $table): Table
    {
        return $table
            ->query(CourseChapter::query()->whereCourseId($this->courseId))
            ->columns([
                TextColumn::make('title')
                    ->label('Judul Bab')
                    ->searchable(),
            ])
            ->actions([
                EditAction::make()
                    ->modalWidth('10xl')
                    ->slideOver()
                    ->form([
                        Section::make([
                            TextInput::make('title')
                                ->label('Judul')
                                ->required(),
                            Repeater::make('lessons')
                                ->relationship('courseLessons')
                                ->schema([
                                    TextInput::make('title')
                                        ->label('Judul')
                                        ->required(),
                                    TextInput::make('youtube_id')
                                        ->label('Youtube ID')
                                        ->required(),
                                    Toggle::make('is_locked')
                                        ->label('Terkunci')
                                        ->default(true)
                                ]),
                        ])
                ]),
                DeleteAction::make()
                    ->requiresConfirmation()
                    ->action(function(CourseChapter $courseChapter) {
                        $courseChapter->courseLessons()->delete();
                        $courseChapter->delete();
                    })
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->form([
                    Section::make([
                        TextInput::make('title')
                            ->label('Judul')
                            ->required(),
                        Repeater::make('lessons')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Judul')
                                    ->required(),
                                TextInput::make('youtube_id')
                                    ->label('Youtube ID')
                                    ->required(),
                                Toggle::make('is_locked')
                                    ->label('Terkunci')
                                    ->default(true)
                            ]),
                    ])
                ])
                ->using(function (array $data) {
                    DB::beginTransaction();
                    try {
                        $courseChapter = CourseChapter::create([
                            'course_id' => $this->courseId,
                            'title' => $data['title'],
                        ]);

                        foreach ($data['lessons'] as $lesson) {
                            $lesson['course_chapter_id'] = $courseChapter->id;
                            $lesson['created_at'] = now();
                            $lesson['is_locked'] = $lesson['is_locked'];
                            CourseLesson::insert($lesson);
                        }

                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollBack();
                        Notification::make()
                            ->title('Gagal karena ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                })
                ->modalWidth('10xl')
                ->slideOver()
                ->createAnother(false)
        ];
    }
}
