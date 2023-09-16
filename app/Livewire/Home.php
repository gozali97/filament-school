<?php

namespace App\Livewire;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;

class Home extends Component implements HasForms
{
    use InteractsWithForms;

    public $name= '';
    public $gender= '';
    public $birthday= '';
    public $religion= '';
    public $contact= '';
    public $profile= '';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Siswa')->required(),
                        Select::make('gender')
                            ->options([
                                "Male" => "Male",
                                "Female" => "Female"
                            ]),
                        Select::make('religion')
                            ->options([
                                "Islam" => "Islam",
                                "Katolik" => "Katolik",
                                "Protestan" => "Protestan",
                                "Hindu" => "Hindu",
                                "Buddha" => "Buddha",
                                "Khonghucu" => "Khonghucu",
                            ]),
                        DatePicker::make('birthday')
                            ->label('Tanggal lahir'),
                        TextInput::make('contact')
                            ->required(),
                        FileUpload::make('profile')
                            ->directory('Students')
                    ])
            ]);
    }

    public function render()
    {
        return view('livewire.home');
    }

    public function save():Void
    {
        dd($this->form()->getState());
    }
}
