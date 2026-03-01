<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VenueResource\Pages;
use App\Filament\Resources\VenueResource\RelationManagers;
use App\Models\Venue;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class VenueResource extends Resource
{
    protected static ?string $model = Venue::class;
    protected static ?string $navigationIcon = 'heroicon-o-home-modern';
    protected static ?string $navigationGroup = 'Správa míst';
    protected static ?string $modelLabel = 'Místo';
    protected static ?string $pluralModelLabel = 'Místa';

    public static function canCreate(): bool
    {
        return auth()->user()->isSuperAdmin();
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()->isSuperAdmin();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Základní informace')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Název místa')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('venue_type_id')
                            ->label('Typ místa')
                            ->relationship('venueType', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\RichEditor::make('description')
                            ->label('Popis')
                            ->columnSpanFull(),
                        SpatieMediaLibraryFileUpload::make('logo')
                            ->label('Logo podniku')
                            ->collection('logo')
                            ->image()
                            ->imageEditor(),
                        SpatieMediaLibraryFileUpload::make('gallery')
                            ->label('Fotogalerie')
                            ->collection('gallery')
                            ->multiple()
                            ->reorderable()
                            ->image()
                            ->imageEditor()
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Lokace')
                    ->schema([
                        Forms\Components\TextInput::make('address_street')
                            ->label('Ulice'),
                        Forms\Components\TextInput::make('address_number')
                            ->label('Čp/orientační'),
                        Forms\Components\TextInput::make('address_city')
                            ->label('Město')
                            ->default('Praha'),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('lat')
                                    ->label('GPS Lat')
                                    ->numeric(),
                                Forms\Components\TextInput::make('lng')
                                    ->label('GPS Lng')
                                    ->numeric(),
                            ]),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'public' => 'Veřejný',
                                'secret' => 'Tajný',
                                'private' => 'Neveřejný',
                            ])
                            ->required()
                            ->default('public'),
                    ])->columns(2),

                Forms\Components\Section::make('Zodpovědná osoba')
                    ->schema([
                        Forms\Components\TextInput::make('contact_name')
                            ->label('Jméno a příjmení'),
                        Forms\Components\TextInput::make('contact_phone')
                            ->label('Telefon')
                            ->tel(),
                        Forms\Components\TextInput::make('contact_email')
                            ->label('Email')
                            ->email(),
                        Forms\Components\Select::make('owner_id')
                            ->label('Uživatel (Správce)')
                            ->relationship('owner', 'name', fn (Builder $query) => $query->whereIn('role', ['admin', 'superadmin']))
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label('Jméno')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->required()
                                    ->unique('users', 'email'),
                                Forms\Components\TextInput::make('password')
                                    ->label('Heslo')
                                    ->password()
                                    ->required()
                                    ->dehydrateStateUsing(fn ($state) => \Illuminate\Support\Facades\Hash::make($state)),
                                Forms\Components\Hidden::make('role')
                                    ->default('admin'),
                            ])
                            ->visible(fn () => auth()->user()->isSuperAdmin()),
                    ])->columns(2),

                Forms\Components\Section::make('Odkazy')
                    ->schema([
                        Forms\Components\TextInput::make('web_url')
                            ->label('Web')
                            ->url(),
                        Forms\Components\TextInput::make('instagram_url')
                            ->label('Instagram')
                            ->url(),
                        Forms\Components\TextInput::make('facebook_url')
                            ->label('Facebook')
                            ->url(),
                    ])->columns(3),

                Forms\Components\Section::make('Otevírací doba během akce')
                    ->schema(function () {
                        $start = Setting::get('event_start_date');
                        $end = Setting::get('event_end_date');

                        if (!$start || !$end) {
                            return [
                                Forms\Components\Placeholder::make('note')
                                    ->content('Nejdříve nastavte termín akce v globálním nastavení.')
                            ];
                        }

                        $period = CarbonPeriod::create($start, $end);
                        $fields = [];

                        foreach ($period as $date) {
                            $dateStr = $date->format('Y-m-d');
                            $label = $date->translatedFormat('l d.m.');

                            $fields[] = Forms\Components\Fieldset::make($label)
                                ->schema([
                                    Forms\Components\TimePicker::make("opening_hours.{$dateStr}.from")
                                        ->label('Otevřeno od'),
                                    Forms\Components\TimePicker::make("opening_hours.{$dateStr}.to")
                                        ->label('Otevřeno do'),
                                ])->columns(2);
                        }

                        return $fields;
                    })
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('logo')
                    ->label('Logo')
                    ->collection('logo')
                    ->conversion('thumb'),
                SpatieMediaLibraryImageColumn::make('gallery')
                    ->label('Galerie')
                    ->collection('gallery')
                    ->conversion('thumb')
                    ->limit(3)
                    ->stacked(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Název')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('venueType.name')
                    ->label('Typ')
                    ->sortable(),
                Tables\Columns\TextColumn::make('address_street')
                    ->label('Adresa')
                    ->formatStateUsing(fn ($record) => "{$record->address_street} {$record->address_number}, {$record->address_city}")
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'public',
                        'warning' => 'secret',
                        'danger' => 'private',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'public' => 'Veřejný',
                        'secret' => 'Tajný',
                        'private' => 'Neveřejný',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('owner.name')
                    ->label('Správce')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('venue_type_id')
                    ->label('Typ místa')
                    ->relationship('venueType', 'name'),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'public' => 'Veřejný',
                        'secret' => 'Tajný',
                        'private' => 'Neveřejný',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            RelationManagers\StagesRelationManager::class,
        ];
    }

    public static function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\ProgramTimeline::class,
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (Auth::user()->isSuperAdmin()) {
            return $query;
        }

        return $query->where('owner_id', Auth::id());
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVenues::route('/'),
            'create' => Pages\CreateVenue::route('/create'),
            'edit' => Pages\EditVenue::route('/{record}/edit'),
        ];
    }
}
