<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomResource\Pages;
use App\Filament\Resources\RoomResource\RelationManagers;
use App\Models\Room;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoomResource extends Resource
{
    protected static ?string $model = Room::class;

    protected static ?string $navigationLabel = 'Rooms';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('educational_center_id')
                    ->label('Educational Center')
                    ->relationship('educationalcenter', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->placeholder('Select an educational center'),
                Forms\Components\DatePicker::make('date')
                    ->label('Date')
                    ->required()
                    ->default(now())
                    ->dehydrateStateUsing(fn($state) => $state
                        ? \Illuminate\Support\Carbon::parse($state, 'Europe/Athens')->startOfDay()->timestamp
                        : null),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]
        )->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Room Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('educationalcenter.name')
                    ->label('Educational Center')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->dateTime('d/m/Y H:i')
                    ->timezone('Europe/Athens')
                    ->label('Date')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('educational_center_id')
                    ->label('Educational Center')
                    ->relationship('educationalcenter', 'name')
                    ->searchable()
                    ->preload()
                    ->placeholder('All Educational Centers'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRooms::route('/'),
            /* 'create' => Pages\CreateRoom::route('/create'),
            'edit' => Pages\EditRoom::route('/{record}/edit'), */
        ];
    }
}
