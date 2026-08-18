<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenilaianResource\Pages;
use App\Filament\Resources\PenilaianResource\RelationManagers;
use App\Models\Penilaian;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use App\Models\AspekPenilaian;

class PenilaianResource extends Resource
{
    protected static ?string $model = Penilaian::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationGroup = 'Penilaian';
    protected static ?string $pluralModelLabel = 'Penilaian Juri';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('cabang_lomba_id')
                    ->label('Cabang Lomba')
                    ->options([
                        'webdev' => 'Web Development',
                        'uiux' => 'UI/UX Design',
                    ])
                    ->live()
                    ->dehydrated(false)
                    ->afterStateUpdated(fn (Forms\Set $set) => $set('aspek_penilaian_id', null))
                    ->default(fn ($record) => $record?->webdev_progress_id ? 'webdev' : ($record?->ui_progress_id ? 'uiux' : null))
                    ->required(),

                Select::make('webdev_progress_id')
                    ->label('Tim Web Dev')
                    ->options(function () {
                        return \App\Models\WebdevProgress::with('tim')->get()->pluck('tim.nama', 'id');
                    })
                    ->visible(fn (Forms\Get $get) => $get('cabang_lomba_id') === 'webdev')
                    ->required(fn (Forms\Get $get) => $get('cabang_lomba_id') === 'webdev')
                    ->searchable(),

                Select::make('ui_progress_id')
                    ->label('Tim UI/UX')
                    ->options(function () {
                        return \App\Models\UiProgress::with('tim')->get()->pluck('tim.nama', 'id');
                    })
                    ->visible(fn (Forms\Get $get) => $get('cabang_lomba_id') === 'uiux')
                    ->required(fn (Forms\Get $get) => $get('cabang_lomba_id') === 'uiux')
                    ->searchable(),

                Select::make('aspek_penilaian_id')
                    ->label('Aspek Penilaian')
                    ->options(function (Forms\Get $get) {
                        $cabangLomba = $get('cabang_lomba_id');
                        if ($cabangLomba === 'webdev') {
                            return AspekPenilaian::whereHas('cabangLomba', fn($q) => $q->where('nama', 'WEB DEVELOPMENT'))->pluck('nama', 'id');
                        } elseif ($cabangLomba === 'uiux') {
                            return AspekPenilaian::whereHas('cabangLomba', fn($q) => $q->where('nama', 'UI/UX'))->pluck('nama', 'id');
                        }
                        return [];
                    })
                    ->required()
                    ->searchable(),

                Select::make('juri_id')
                    ->label('Juri')
                    ->relationship('juri', 'name')
                    ->required()
                    ->searchable(),

                TextInput::make('skor')
                    ->label('Skor (0-100)')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('juri.name')
                    ->label('Nama Juri')
                    ->sortable()
                    ->searchable(),
                    
                TextColumn::make('webdevProgress.tim.nama')
                    ->label('Tim Web Dev')
                    ->sortable()
                    ->searchable()
                    ->placeholder('-'),

                TextColumn::make('uiProgress.tim.nama')
                    ->label('Tim UI/UX')
                    ->sortable()
                    ->searchable()
                    ->placeholder('-'),

                TextColumn::make('aspekPenilaian.nama')
                    ->label('Aspek Penilaian')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('skor')
                    ->label('Skor')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        $state >= 80 => 'success',
                        $state >= 60 => 'warning',
                        default => 'danger',
                    }),
            ])
            ->filters([
                //
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenilaians::route('/'),
            'create' => Pages\CreatePenilaian::route('/create'),
            'edit' => Pages\EditPenilaian::route('/{record}/edit'),
        ];
    }
}
