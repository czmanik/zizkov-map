<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProgramSlotResource\Pages;
use App\Models\ProgramSlot;
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

class ProgramSlotResource extends Resource
{
    protected static ?string $model = ProgramSlot::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'Program';
    protected static ?string $modelLabel = 'Programový slot';
    protected static ?string $pluralModelLabel = 'Programové sloty';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informace o aktivitě')
                    ->schema([
                        Forms\Components\Select::make('stage_id')
                            ->label('Stage')
                            ->relationship('stage', 'name', function (Builder $query) {
                                if (!Auth::user()->isSuperAdmin()) {
                                    $query->whereHas('venue', fn($q) => $q->where('owner_id', Auth::id()));
                                }
                                return $query;
                            })
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('activity_type_id')
                            ->label('Typ aktivity')
                            ->relationship('activityType', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('name')
                            ->label('Název vystoupení/aktivity')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\RichEditor::make('description')
                            ->label('Popis')
                            ->columnSpanFull(),
                        SpatieMediaLibraryFileUpload::make('image')
                            ->label('Obrázek')
                            ->collection('image')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Čas a dostupnost')
                    ->schema([
                        Forms\Components\DateTimePicker::make('start_time')
                            ->label('Od')
                            ->required()
                            ->afterOrEqual(fn() => Setting::get('event_start_date') ? Carbon::parse(Setting::get('event_start_date'))->startOfDay() : now()->subYears(10))
                            ->beforeOrEqual(fn() => Setting::get('event_end_date') ? Carbon::parse(Setting::get('event_end_date'))->addDay()->endOfDay() : now()->addYears(10)),
                        Forms\Components\DateTimePicker::make('end_time')
                            ->label('Do')
                            ->required()
                            ->after('start_time')
                            ->beforeOrEqual(fn() => Setting::get('event_end_date') ? Carbon::parse(Setting::get('event_end_date'))->addDay()->endOfDay() : now()->addYears(10)),
                        Forms\Components\Select::make('accessibility')
                            ->label('Přístupnost')
                            ->options([
                                'all' => 'Pro všechny',
                                'family' => 'Celá rodina',
                                'youth' => 'Mládež',
                                'adults' => 'Pro dospělé',
                            ])
                            ->required()
                            ->default('all'),
                        Forms\Components\TextInput::make('external_url')
                            ->label('Odkaz')
                            ->url(),
                    ])->columns(2),

                Forms\Components\Section::make('Schválení')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'draft' => 'Draft',
                                'pending' => 'Ke schválení',
                                'approved' => 'Schváleno',
                            ])
                            ->required()
                            ->default('pending')
                            ->disableOptionWhen(fn (string $value): bool =>
                                $value === 'approved' && !Auth::user()->isSuperAdmin()
                            )
                            ->visible(fn () => Auth::user()->role !== 'user'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('image')
                    ->label('Foto')
                    ->collection('image')
                    ->conversion('thumb'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Název')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('stage.venue.name')
                    ->label('Místo')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stage.name')
                    ->label('Stage')
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->label('Čas')
                    ->formatStateUsing(fn ($record) => $record->start_time->format('d.m. H:i') . ' - ' . $record->end_time->format('H:i'))
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'gray' => 'draft',
                        'warning' => 'pending',
                        'success' => 'approved',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft' => 'Draft',
                        'pending' => 'Ke schválení',
                        'approved' => 'Schváleno',
                        default => $state,
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'pending' => 'Ke schválení',
                        'approved' => 'Schváleno',
                    ]),
                Tables\Filters\SelectFilter::make('activity_type_id')
                    ->label('Typ aktivity')
                    ->relationship('activityType', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('approve')
                    ->label('Schválit')
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->action(fn (ProgramSlot $record) => $record->update(['status' => 'approved']))
                    ->visible(fn (ProgramSlot $record) => Auth::user()->isSuperAdmin() && $record->status !== 'approved'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (Auth::user()->isSuperAdmin()) {
            return $query;
        }

        return $query->whereHas('stage.venue', fn ($q) => $q->where('owner_id', Auth::id()));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProgramSlots::route('/'),
            'create' => Pages\CreateProgramSlot::route('/create'),
            'edit' => Pages\EditProgramSlot::route('/{record}/edit'),
        ];
    }
}
