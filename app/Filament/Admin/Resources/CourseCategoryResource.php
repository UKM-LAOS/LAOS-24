<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CourseCategoryResource\Pages;
use App\Filament\Admin\Resources\CourseCategoryResource\RelationManagers;
use App\Models\CourseCategory;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CourseCategoryResource extends Resource
{
    protected static ?string $model = CourseCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                    SpatieMediaLibraryFileUpload::make('course-category-image')
                        ->collection('course-category-image')
                        ->label('Gambar Kategori (SVG)')
                        ->image()
                        ->required()
                        ->rules(['required', 'image', 'mimes:svg'])
                        ->rules('image')
                        ->maxSize('1024')
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Kategori')
                    ->searchable(),
                SpatieMediaLibraryImageColumn::make('course-category-image')
                    ->collection('course-category-image')
                    ->label('Gambar Kategori'),
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->slideOver()
                    ->closeModalByClickingAway(false)
                    ->modalWidth('10xl'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCourseCategories::route('/'),
        ];
    }
}
