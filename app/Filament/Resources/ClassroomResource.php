<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassroomResource\Pages;
use App\Filament\Resources\ClassroomResource\RelationManagers\SubjectsRelationManager;
use App\Models\Classroom;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ClassroomResource extends Resource
{
    protected static ?string $model = Classroom::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

//    protected static ?string $navigationLabel = 'Classroom';

    protected static ?string $navigationGroup = 'Akademik';
    protected static ?int $navigationSort = 13;

    public static function shouldRegisterNavigation(): bool
    {
        if(Auth::user()->can('classroom'))
            return true;
        else
            return false;
    }

//    public static function canViewAny(): bool
//    {
//        if(Auth::user()->can('classroom'))
//            return true;
//        else
//            return false;
//    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->live()
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', \Str::slug($state)))
                    ->required(),
                TextInput::make('slug')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')->state(
                    static function (Tables\Contracts\HasTable $livewire, \stdClass $rowLoop): string {
                        return (string) (
                            $rowLoop->iteration +
                            ($livewire->getTableRecordsPerPage() * (
                                    $livewire->getTablePage() - 1
                                ))
                        );
                    }
                ),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('slug')->searchable(),
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
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            SubjectsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageClassrooms::route('/'),
            'edit' => Pages\EditClassroms::route('/{record}/edit'),
            'view' => Pages\ManageClassrooms::route('/{record}'),
        ];
    }
    public static function getLabel(): ?string
    {
        $locale = app()->getLocale();
        if ($locale == 'id') {
            return "Kelas";
        } else
            return "Classroom";

    }

}
