<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TimResource\Pages;
use App\Filament\Resources\TimResource\RelationManagers;
use App\Models\Tim;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TimResource extends Resource
{
    protected static ?string $model = Tim::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Data Master';
    protected static ?int $navigationSort = 2;
    protected static ?string $pluralModelLabel = 'Tim';

    public static function canViewAny(): bool
    {
        return auth()->user()->role === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('cabang_lomba_id')
                    ->relationship('cabangLomba', 'nama')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->label('Cabang Lomba'),

                Forms\Components\Select::make('instansi_id')
                    ->relationship('instansi', 'nama')
                    ->required()
                    ->preload()
                    ->searchable(),

                Forms\Components\TextInput::make('nama')
                    ->label('Nama Tim')
                    ->required()
                    ->maxLength(255),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('No')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('cabangLomba.nama')
                    ->searchable()
                    ->sortable()
                    ->label('Cabang Lomba'),
                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->label('Nama Tim'),
                Tables\Columns\TextColumn::make('instansi.nama')
                    ->label('Instansi')
                    ->searchable(),
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
            'index' => Pages\ListTims::route('/'),
            'create' => Pages\CreateTim::route('/create'),
            'edit' => Pages\EditTim::route('/{record}/edit'),
        ];
    }
}
