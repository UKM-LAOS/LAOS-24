<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\DiscountResource\Pages;
use App\Filament\Admin\Resources\DiscountResource\RelationManagers;
use App\Models\Discount;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DiscountResource extends Resource
{
    protected static ?string $model = Discount::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Forms\Components\TextInput::make('code')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                    Forms\Components\TextInput::make('percentage')
                        ->required()
                        ->numeric(),
                    Select::make('type')
                        ->options([
                            'limit_by_date' => 'Limit by date',
                            'limit_by_usage' => 'Limit by usage',
                        ]) 
                        ->selectablePlaceholder(false)
                        ->default('limit_by_date')
                        ->live()
                        ->required(),
                    Forms\Components\DateTimePicker::make('expired_at')
                        ->live()
                        ->hidden(fn(Get $get) => $get('type') !== 'limit_by_date')
                        ->required(fn(Get $get) => $get('type') === 'limit_by_date'),
                    Forms\Components\TextInput::make('usage_limit')
                        ->numeric()
                        ->live()
                        ->hidden(fn(Get $get) => $get('type') !== 'limit_by_usage')
                        ->required(fn(Get $get) => $get('type') === 'limit_by_usage'),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('percentage')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->getStateUsing(function(Discount $discount) {
                        return match($discount->type) {
                            'limit_by_date' => 'Limit by date',
                            'limit_by_usage' => 'Limit by usage',
                        };
                    }),
                Tables\Columns\TextColumn::make('expired_at')
                    ->getStateUsing(function(Discount $discount) {
                        if($discount->expired_at)
                        {
                            return Carbon::parse($discount->expired_at)->format('d M Y H:i');
                        }

                        return '-';
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('usage_limit')
                    ->numeric()
                    ->getStateUsing(function(Discount $discount) {
                        if($discount->usage_limit)
                        {
                            return $discount->transactions->count() . '/' . $discount->usage_limit;
                        }

                        return '-';
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('is_active')
                    ->label('Status')
                    ->badge()
                    ->color(function(Discount $discount) {
                        if($discount->expired_at)
                        {
                            if($discount->expired_at < now())
                            {
                                return 'danger';
                            }
                        }

                        if($discount->usage_limit)
                        {
                            if($discount->usage_limit <= $discount->transactions->count())
                            {
                                return 'danger';
                            }
                        }
                    })
                    ->getStateUsing(function(Discount $discount) {
                        if($discount->expired_at)
                        {
                            if($discount->expired_at < now())
                            {
                                return 'inactive';
                            }
                        }

                        if($discount->usage_limit)
                        {
                            if($discount->usage_limit <= $discount->transactions->count())
                            {
                                return 'inactive';
                            }
                        }

                        return 'active';
                    }),
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
            'index' => Pages\ManageDiscounts::route('/'),
        ];
    }
}
