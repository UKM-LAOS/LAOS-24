<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProgramResource\Pages;
use App\Filament\Admin\Resources\ProgramResource\RelationManagers;
use App\Models\Division;
use App\Models\Program;
use Dotswan\MapPicker\Fields\Map;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
class ProgramResource extends Resource
{
    protected static ?string $model = Program::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Detail')->schema([
                    Forms\Components\TextInput::make('program_title')
                        ->required()
                        ->label('Judul Program')
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->unique(ignoreRecord: true)
                        ->afterStateUpdated(fn(Set $set, ?string $state) => $set('program_slug', Str::slug($state))),
                    Forms\Components\TextInput::make('program_slug')
                        ->required()
                        ->readOnly()
                        ->label('Slug Program')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('activity_title')
                        ->required()
                        ->label('Judul Kegiatan')
                        ->maxLength(255),
                    Select::make('division_id')
                        ->required()
                        ->options(Division::pluck('name', 'id'))
                        ->label('Divisi')
                        ->placeholder('Pilih Divisi'),
                    Map::make('location')
                        ->label('Lokasi')
                        ->defaultLocation(latitude: -8.165516031480806, longitude: 113.71727423131937)
                        ->afterStateUpdated(function (Set $set, ?array $state): void {
                            $set('latitude', $state['lat']);
                            $set('longitude', $state['lng']);
                        })
                        ->liveLocation(true, true, 5000)
                        ->showMarker()
                        ->markerColor("#22c55eff")
                        ->showFullscreenControl()
                        ->showZoomControl()
                        ->draggable()
                        ->tilesUrl("https://tile.openstreetmap.de/{z}/{x}/{y}.png")
                        ->zoom(15)
                        ->detectRetina()
                        ->showMyLocationButton()
                        ->extraTileControl([])
                        ->extraControl([
                            'zoomDelta'           => 1,
                            'zoomSnap'            => 2,
                        ]),
                    Forms\Components\TextInput::make('latitude')
                        ->required()
                        ->readOnly()
                        ->numeric(),
                    Forms\Components\TextInput::make('longitude')
                        ->required()
                        ->readOnly()
                        ->numeric(),
                    RichEditor::make('content')
                        ->required(),
                    SpatieMediaLibraryFileUpload::make('thumbnail')
                        ->required()
                        ->image()
                        ->collection('program-thumbnail'),
                    SpatieMediaLibraryFileUpload::make('documentation')
                        ->collection('program-documentation')
                        ->multiple()
                        ->image()
                        ->label('Dokumentasi (Multiple & Nullable)')
                        ->reorderable(),
                ]),

                Section::make('Waktu')
                    ->schema([
                        Forms\Components\DatePicker::make('open_regis_panitia')
                            ->required(),
                        Forms\Components\DatePicker::make('close_regis_panitia')
                            ->required(),
                        Forms\Components\DatePicker::make('open_regis_peserta')
                            ->required(),
                        Forms\Components\DatePicker::make('close_regis_peserta')
                            ->required(),
                        Forms\Components\TextInput::make('gform_panitia')
                            ->required()
                            ->label('Google Form Panitia')
                            ->placeholder('Masukkan link Google Form, contoh: https://docs.google.com/forms/d/e/1FAIpQLSd'),
                        Forms\Components\TextInput::make('gform_peserta')
                            ->required()
                            ->label('Google Form Peserta')
                            ->placeholder('Masukkan link Google Form, contoh: https://docs.google.com/forms/d/e/1FAIpQLSd'),
                        Forms\Components\Repeater::make('program_schedules')
                            ->label('Jadwal Kegiatan')
                            ->required()
                            ->schema([
                                Forms\Components\TextInput::make('schedule_title')
                                    ->label('Judul Jadwal')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\DatePicker::make('start_date')
                                    ->label('Tanggal Mulai')
                                    ->required(),
                                Forms\Components\DatePicker::make('end_date')
                                    ->label('Tanggal Selesai')
                                    ->required(),
                            ])
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('program_title')
                    ->label('Judul Program')
                    ->searchable(),
                Tables\Columns\TextColumn::make('division.name')
                    ->sortable(),
                SpatieMediaLibraryImageColumn::make('program-thumbnail')
                    ->collection('program-thumbnail')
                    ->label('Thumbnail'),
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
                SelectFilter::make('division_id')
                    ->label('Divisi')
                    ->relationship('division', 'name'),
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
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePrograms::route('/'),
        ];
    }
}
