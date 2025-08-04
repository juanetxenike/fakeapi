<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExamSessionResource\Pages;
use App\Filament\Resources\ExamSessionResource\RelationManagers;
use App\Models\ExamSession;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExamSessionResource extends Resource
{
    protected static ?string $model = ExamSession::class;
    protected static ?string $navigationLabel = 'Exam Sessions';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('filter_educational_center_id')
                    ->label('Educational Center')
                    ->searchable()
                    ->preload()
                    ->placeholder('Select an educational center')
                    ->options(fn () => \App\Models\EducationalCenter::pluck('name', 'id'))
                    ->reactive()
                    ->dehydrated(false),
                Forms\Components\Select::make('room_id')
                    ->label('Room')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->placeholder('Select a room')
                    ->disabled(fn (callable $get) => empty($get('filter_educational_center_id')))
                    ->options(function (callable $get) {
                        $centerId = $get('filter_educational_center_id');
                        if (!$centerId) {
                            return [];
                        }
                        return \App\Models\Room::where('educational_center_id', $centerId)
                            ->pluck('name', 'id');
                    }),
                Forms\Components\DateTimePicker::make('starttime')
                    ->required()
                    ->label('Start Time')
                    ->minutesStep(15),
                Forms\Components\DateTimePicker::make('endtime')
                    ->required()
                    ->label('End Time')
                    ->minutesStep(15),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('room.educationalCenter.name')
                    ->label('Educational Center')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('room.name')
                    ->label('Room')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('room.date')
                    ->label('Room')
                    ->dateTime('d/m/Y H:i')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('starttime')
                    ->time()
                    ->label('Start Time')
                    ->sortable(),
                Tables\Columns\TextColumn::make('endtime')
                    ->time()
                    ->label('End Time')
                    ->sortable(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListExamSessions::route('/'),
            /* 'create' => Pages\CreateExamSession::route('/create'),
            'edit' => Pages\EditExamSession::route('/{record}/edit'), */
        ];
    }
}
