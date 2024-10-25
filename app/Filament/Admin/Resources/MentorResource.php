<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MentorResource\Pages;
use App\Filament\Admin\Resources\MentorResource\Pages\MentorCoursePage;
use App\Filament\Admin\Resources\MentorResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MentorResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Mentors';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    TextInput::make('name')
                        ->label('Nama Mentor')
                        ->required(),
                    TextInput::make('email')
                        ->label('Email')
                        ->required()
                        ->email()
                        ->unique(ignoreRecord: true),
                    TextInput::make('password')
                        ->label('Password')
                        ->password()
                        ->dehydrateStateUsing(fn($state) => bcrypt($state))
                        ->dehydrated(fn($state) => filled($state))
                        ->required(fn(string $context): bool => $context === 'create'),
                    Select::make('roles')
                        ->relationship('roles', 'name')
                        ->multiple()
                        ->label('Roles')
                        ->preload()
                        ->searchable()
                        ->required()
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(User::query()->whereHas('roles', fn(Builder $query) => $query->where('name', 'mentor')))
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Mentor')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('custom_fields.occupation')
                    ->label('Pekerjaan')
                    ->getStateUsing(fn(User $user) => json_decode($user->custom_fields, true)['occupation'] ?? "-")
                    ->searchable(),
                TextColumn::make('total_course')
                    ->label('Jumlah Course')
                    ->getStateUsing(fn(Model $record) => $record->courses()->count())
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('Detail Course')
                    ->label('Detail Course')
                    ->color('info')
                    ->icon('heroicon-o-academic-cap')
                    ->url(fn(User $record) => MentorCoursePage::getUrl(['record' => $record->id])),
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
            'index' => Pages\ManageMentors::route('/'),
            'course' => Pages\MentorCoursePage::route('/{record}/course'),
        ];
    }
}
