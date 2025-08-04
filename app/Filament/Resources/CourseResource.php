<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Filament\Resources\CourseResource\RelationManagers;
use App\Models\Course;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;


    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['educationalCenters.rooms']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label('Course Name'),
                        Forms\Components\Repeater::make('educationalCenter')
                            ->relationship('educationalCenters')
                            ->label('Educational Centers')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Educational Center Name')
                                    ->required(),
                                // Nested repeater for rooms
                                Forms\Components\Repeater::make('rooms')
                                    ->relationship('rooms')
                                    ->label('Rooms')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Room Name')
                                            ->required(),
                                        Forms\Components\TextInput::make('capacity')
                                            ->label('Capacity')
                                            ->required()
                                            ->numeric()
                                            ->minValue(1),
                                        Forms\Components\DatePicker::make('date')
                                            ->label('Date')
                                            ->required()
                                            ->default(now())
                                            ->dehydrateStateUsing(fn($state) => $state
                                                ? \Illuminate\Support\Carbon::parse($state, 'Europe/Athens')->startOfDay()->timestamp
                                                : null),
                                        Forms\Components\Repeater::make('examSessions')
                                            ->relationship('examSessions')
                                            ->label('Exam Sessions')
                                            ->schema([
                                                Forms\Components\TimePicker::make('starttime')
                                                    ->label('Start Time')
                                                    ->required()
                                                    ->seconds(false)
                                                    ->format('H:i')
                                                    ->default(now())
                                                    ->minutesStep(15),
                                                Forms\Components\TimePicker::make('endtime')
                                                    ->label('End Time')
                                                    ->required()
                                                    ->seconds(false)
                                                    ->format('H:i')
                                                    ->default(now())
                                                    ->minutesStep(15),
                                                // Add more exam session fields as needed
                                            ]),
                                        // Add more room fields as needed
                                    ]),
                            ]),
                    ])
            ]
        );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('educationalCenters.name')
                    ->label('Educational Centers and Rooms')
                    ->formatStateUsing(function ($record) {
                        $output = [];
                        foreach ($record->educationalCenters as $center) {
                            $rooms = $center->rooms->pluck('name')->join(' | ');
                            $output[] = '<span class="inline-block rounded bg-primary-100 px-2 py-1 text-xs font-semibold text-primary-700 border border-primary-300 my-1">' . $center->name . '</span>' . ($rooms ? ' ' . $rooms : '');
                        }
                        return implode('<br /><br />', $output);
                    })->html(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
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
            'index' => Pages\ListCourses::route('/'),
            //'create' => Pages\CreateCourse::route('/create'),
            //'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}
