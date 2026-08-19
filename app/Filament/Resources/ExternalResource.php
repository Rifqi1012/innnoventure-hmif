<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExternalResource\Pages;
use App\Filament\Resources\ExternalResource\RelationManagers;
use App\Models\External;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExternalResource extends Resource
{
    protected static ?string $model = External::class;

    protected static ?string $navigationIcon = 'heroicon-o-signal';

    protected static ?string $navigationGroup = 'Data Master';

    protected static ?int $navigationSort = 5;

    protected static ?string $pluralModelLabel = 'Medpart/Sponsor';

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
                Forms\Components\FileUpload::make('logo')
                    ->image()
                    ->directory('logos')
                    ->maxSize(2048) // Maksimum 2MB
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(External::class, 'email', ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\TextInput::make('no_hp')
                    ->tel()
                    ->required()
                    ->maxLength(20),
                Forms\Components\Toggle::make('is_aktif')
                    ->label('Aktif')
                    ->default(false),
                Forms\Components\Select::make('jenis')
                    ->options([
                        'medpart' => 'Media Partner',
                        'sponsor' => 'Sponsor',
                    ])
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('logo')
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('no_hp')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_aktif')
                    ->boolean()
                    ->label('Aktif'),
                Tables\Columns\TextColumn::make('jenis')
                    ->formatStateUsing(fn(?string $state): string => match ($state) {
                        'medpart' => 'Media Partner',
                        'sponsor' => 'Sponsor',
                        default => '-',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jenis')
                    ->options([
                        'medapart' => 'Media Partner',
                        'sponsor' => 'Sponsor',
                    ])
                    ->label('Jenis'),
                Tables\Filters\TernaryFilter::make('is_aktif')
                    ->label('Status Aktif'),
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
            // Tambahkan relasi jika ada, misalnya RelationManagers
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExternals::route('/'),
            'create' => Pages\CreateExternal::route('/create'),
            'edit' => Pages\EditExternal::route('/{record}/edit'),
        ];
    }
}
