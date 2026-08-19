<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CabangLombaResource\Pages;
use App\Filament\Resources\CabangLombaResource\RelationManagers;
use App\Models\CabangLomba;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CabangLombaResource extends Resource
{
    protected static ?string $model = CabangLomba::class;

    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';
    protected static ?string $navigationGroup = 'Data Master';
    protected static ?int $navigationSort = 0;
    protected static ?string $pluralModelLabel = 'Cabang Lomba';


    public static function canViewAny(): bool
    {
        return auth()->user()->role === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Radio::make('jenis_penilaian')
                    ->required()
                    ->options([
                        'vs' => 'VS',
                        'juri' => 'Juri',
                    ])
                    ->inline(),
                Forms\Components\DatePicker::make('tanggal_mulai')
                    ->required()
                    ->native(false),
                Forms\Components\DatePicker::make('tanggal_berakhir')
                    ->required()
                    ->native(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis_penilaian')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'vs' => 'success',
                        'juri' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'vs' => 'VS',
                        'juri' => 'Juri',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('tanggal_mulai')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_berakhir')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListCabangLombas::route('/'),
            'create' => Pages\CreateCabangLomba::route('/create'),
            'edit' => Pages\EditCabangLomba::route('/{record}/edit'),
        ];
    }
}
