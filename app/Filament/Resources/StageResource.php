<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StageResource\Pages;
use App\Models\Stage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class StageResource extends Resource
{
    protected static ?string $model = Stage::class;
    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    protected static ?string $navigationGroup = 'Správa míst';
    protected static ?string $modelLabel = 'Stage';
    protected static ?string $pluralModelLabel = 'Stages';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('venue_id')
                    ->label('Místo')
                    ->relationship('venue', 'name', function (Builder $query) {
                        if (!Auth::user()->isSuperAdmin()) {
                            $query->where('owner_id', Auth::id());
                        }
                        return $query;
                    })
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('name')
                    ->label('Název stage')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('Popis')
                    ->columnSpanFull(),
                SpatieMediaLibraryFileUpload::make('gallery')
                    ->label('Fotogalerie')
                    ->collection('gallery')
                    ->multiple()
                    ->reorderable()
                    ->image()
                    ->imageEditor()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('gallery')
                    ->label('Galerie')
                    ->collection('gallery')
                    ->conversion('thumb')
                    ->limit(3)
                    ->stacked(),
                Tables\Columns\TextColumn::make('venue.name')
                    ->label('Místo')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Název stage')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Vytvořeno')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('venue_id')
                    ->label('Místo')
                    ->relationship('venue', 'name'),
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


    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (Auth::user()->isSuperAdmin()) {
            return $query;
        }

        return $query->whereHas('venue', fn ($q) => $q->where('owner_id', Auth::id()));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStages::route('/'),
            'create' => Pages\CreateStage::route('/create'),
            'edit' => Pages\EditStage::route('/{record}/edit'),
        ];
    }
}
