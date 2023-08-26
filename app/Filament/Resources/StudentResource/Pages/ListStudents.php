<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Imports\ImportStudent;
use App\Models\Student;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return "Student";
    }

    public function getHeader(): ?View
    {
        $data = Actions\CreateAction::make();
        return view('filament.custom.upload-file', compact('data'));
    }

    public $file = '';

    public function save()
    {
        if ($this->file != '') {
            Excel::import(new ImportStudent(), $this->file);
        }
//        Student::create([
//            'nis' => '333',
//            'name' => 'Adi',
//            'gender' => 'Male',
//        ]);
    }

    public function getTabs(): array
    {
        return [
            'all' => ListRecords\Tab::make(),
            'active' => ListRecords\Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Active')),
            'inactive' => ListRecords\Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Off')),
        ];
    }
}
