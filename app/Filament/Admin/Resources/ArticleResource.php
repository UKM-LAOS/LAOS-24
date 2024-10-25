<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ArticleResource\Pages;
use App\Filament\Admin\Resources\ArticleResource\RelationManagers;
use App\Models\Article;
use App\Models\Division;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Forms\Components\Select::make('division_id')
                            ->label('Divisi')
                            ->required()
                            ->options(Division::pluck('name', 'id')),
                        Forms\Components\TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
                            ->maxLength(255),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->readOnly()
                            ->maxLength(255),
                        Forms\Components\Select::make('category')
                            ->label('Kategori')
                            ->options([
                                // ['informasi', 'tutorial', 'mitos-fakta', 'tips-trik', 'press-release']
                                'informasi' => 'Informasi',
                                'tutorial' => 'Tutorial',
                                'mitos-fakta' => 'Mitos & Fakta',
                                'tips-trik' => 'Tips & Trik',
                                'press-release' => 'Press Release',
                            ])
                            ->required(),
                        SpatieMediaLibraryFileUpload::make('thumbnail')
                            ->image()
                            ->required()
                            ->maxSize('1024')
                            ->collection('article-thumbnail'),
                        Forms\Components\RichEditor::make('content')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('is_unggulan')
                            ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('division.name')
                    ->label('Divisi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->searchable(),
                SpatieMediaLibraryImageColumn::make('thumbnail')
                    ->label('Thumbnail')
                    ->collection('article-thumbnail'),
                Tables\Columns\ToggleColumn::make('is_unggulan')
                    ->label('Unggulan'),
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
                    ->label('Kategori')
                    ->options([
                        'informasi' => 'Informasi',
                        'tutorial' => 'Tutorial',
                        'mitos-fakta' => 'Mitos & Fakta',
                        'tips-trik' => 'Tips & Trik',
                        'press-release' => 'Press Release',
                    ]),
                SelectFilter::make('division_id')
                    ->label('Divisi')
                    ->relationship('division', 'name'),
                Filter::make('is_unggulan')
                    ->label('Unggulan')
                    ->query(fn(Builder $query) => $query->where('is_unggulan', true)),
                ], layout: FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\EditAction::make()
                    ->slideOver()
                    ->modalWidth('10xl'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    BulkAction::make('mark-as-unggulan')
                        ->color('warning')
                        ->label('Tandai sebagai Unggulan')
                        ->action(fn(Collection $records) => $records->each->update(['is_unggulan' => true]))
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion()
                        ->icon('heroicon-o-star'),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageArticles::route('/'),
        ];
    }
}
