<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use function Laravel\Prompts\select;


class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

//    protected static ?string $navigationLabel = 'Student';
    protected static ?string $navigationGroup = 'Akademik';
    protected static ?int $navigationSort = 12;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('nis')
                            ->label('NIS')->required(),
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Siswa')->required(),
                        Forms\Components\Select::make('gender')
                            ->options([
                                "Male" => "Male",
                                "Female" => "Female"
                            ]),
                        Forms\Components\Select::make('religion')
                            ->options([
                                "Islam" => "Islam",
                                "Katolik" => "Katolik",
                                "Protestan" => "Protestan",
                                "Hindu" => "Hindu",
                                "Buddha" => "Buddha",
                                "Khonghucu" => "Khonghucu",
                            ]),
                        Forms\Components\DatePicker::make('birthday')
                            ->label('Tanggal lahir'),
                        Forms\Components\TextInput::make('contact')
                            ->required(),
                        Forms\Components\FileUpload::make('profile')
                            ->directory('Students')
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('Nomor')->state(
                    static function (Tables\Contracts\HasTable $livewire, \stdClass $rowLoop): string {
                        return (string)(
                            $rowLoop->iteration +
                            ($livewire->getTableRecordsPerPage() * (
                                    $livewire->getTablePage() - 1
                                ))
                        );
                    }
                ),
                Tables\Columns\TextColumn::make('nis')
                    ->label('NIS')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gender')
                    ->label('Jenis Kelamin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('religion')
                    ->label('Agama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('birthday')
                    ->label('Tanggal Lahir')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('contact')
                    ->label('No HP')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\ImageColumn::make('profile')
                    ->label('Foto Profile')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->formatStateUsing(fn (string $state): string => ucwords("$state")),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                ->multiple()
                ->options([
                    'Active' =>'Active',
                    'Off' =>'Off',
                    'Move' =>'Move',
                    'Grade' =>'Grade',
                ])
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('Change Status')
                    ->icon('heroicon-m-check')
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\Select::make('Status')
                        ->label('Status')
                        ->options(['Active' =>'Active', 'Off' =>'Off','Move' =>'Move','Grade' =>'Grade'])
                        ->required(),
                    ])
                    ->action(function (Collection $records, array $data){
                        return $records->each(function ($record) use ($data){
                            Student::where('id', $record->id)->update(['status' => $data['Status']]);
                        });
                    }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
//            ->headerActions([
//                Tables\Actions\CreateAction::make(),
//            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
            'view' => Pages\ViewStudent::route('/{record}'),
        ];
    }

    public static function getLabel(): ?string
    {
        $locale = app()->getLocale();
        if ($locale == 'id') {
            return "Siswa";
        } else
            return "Student";
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make()
                    ->schema([
                        Fieldset::make('Biodata')
                        ->schema([
                            Split::make([
                                ImageEntry::make('profile')
                                ->hiddenLabel()
                                ->grow(false),
                                Grid::make(2)
                                ->schema([
                                    Group::make([
                                        TextEntry::make('nis'),
                                        TextEntry::make('name'),
                                        TextEntry::make('gender'),
                                        TextEntry::make('birthday'),
                                    ])
                                    ->inlineLabel()
                                    ->columns(1),

                                    Group::make([
                                        TextEntry::make('religion'),
                                        TextEntry::make('contact'),
                                        TextEntry::make('status')
                                        ->badge()
                                        ->color(fn (string $state): string => match($state){
                                            'Active' => 'success',
                                            'Off' => 'danger',
                                            'Move' => 'info',
                                            'Grade' => 'primary',
                                        }),
                                        ViewEntry::make('QRCode')
                                        ->view('filament.resources.students.qrcode'),
                                    ])
                                    ->inlineLabel()
                                    ->columns(1),
                                ])
                            ])->from('lg')
                        ])->columns(1)
                ])->columns(2)
            ]);
    }

}
