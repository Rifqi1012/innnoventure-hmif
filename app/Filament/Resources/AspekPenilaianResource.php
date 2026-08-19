<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AspekPenilaianResource\Pages;
use App\Filament\Resources\AspekPenilaianResource\RelationManagers;
use App\Models\AspekPenilaian;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AspekPenilaianResource extends Resource
{
    protected static ?string $model = AspekPenilaian::class;

    protected static ?string $navigationIcon = 'heroicon-o-calculator';

    protected static ?string $navigationGroup = 'Data Master';

    protected static ?string $navigationLabel = 'Aspek Penilaian';

    protected static ?string $modelLabel = 'Aspek Penilaian';

    protected static ?int $navigationSort = 4;

    public static function canViewAny(): bool
    {
        return auth()->user()->role === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('id_cabang_lomba')
                    ->relationship('cabangLomba', 'nama')
                    ->label('Cabang Lomba')
                    ->required(),
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('bobot_penilaian')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(255),
                Forms\Components\TextInput::make('keterangan')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cabangLomba.nama')
                    ->label('Cabang Lomba')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bobot_penilaian')
                    ->sortable(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('id_cabang_lomba')
                    ->relationship('cabangLomba', 'nama')
                    ->label('Cabang Lomba'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
            // Tambahkan RelationManagers jika diperlukan
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAspekPenilaians::route('/'),
            'create' => Pages\CreateAspekPenilaian::route('/create'),
            'edit' => Pages\EditAspekPenilaian::route('/{record}/edit'),
        ];
    }
}