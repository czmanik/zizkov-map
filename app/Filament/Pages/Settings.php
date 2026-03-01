<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Notifications\Notification;
use Filament\Actions\Action;

class Settings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'Nastavení';
    protected static ?string $title = 'Globální nastavení';
    protected static ?string $navigationLabel = 'Globální nastavení';
    protected static string $view = 'filament.pages.settings';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    public ?array $data = [];

    public function mount(): void
    {
        $this->data = [
            'event_name' => Setting::get('event_name'),
            'ico' => Setting::get('ico'),
            'event_start_date' => Setting::get('event_start_date'),
            'event_end_date' => Setting::get('event_end_date'),
        ];

        $this->form->fill($this->data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informace o akci')
                    ->schema([
                        TextInput::make('event_name')
                            ->label('Název akce')
                            ->required(),
                        TextInput::make('ico')
                            ->label('IČO'),
                        DatePicker::make('event_start_date')
                            ->label('Datum začátku akce')
                            ->required(),
                        DatePicker::make('event_end_date')
                            ->label('Datum konce akce')
                            ->required(),
                    ])->columns(2)
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Uložit změny')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        Notification::make()
            ->title('Nastavení uloženo')
            ->success()
            ->send();
    }
}
