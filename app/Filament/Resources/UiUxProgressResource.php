<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UiUxProgressResource\Pages;
use App\Models\UiProgress;
use App\Models\Tim;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class UiUxProgressResource extends Resource
{
    protected static ?string $model = UiProgress::class;

    protected static ?string $navigationIcon = 'heroicon-o-swatch';
    protected static ?string $navigationGroup = 'Perlombaan';
    protected static ?int $navigationSort = 10;
    protected static ?string $pluralModelLabel = 'UI/UX Design';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('tim_id')
                    ->label('Tim')
                    ->relationship('tim', 'nama')
                    ->options(function () {
                        return Tim::whereHas('cabangLomba', function ($query) {
                            $query->where('nama', 'UI/UX Design');
                        })->pluck('nama', 'id');
                    })
                    ->searchable()
                    ->required(),

                TextInput::make('email_ketua')
                    ->label('Email Ketua')
                    ->email()
                    ->required(),

                TextInput::make('judul_proyek')
                    ->label('Judul Proyek')
                    ->required()
                    ->maxLength(255),

                Textarea::make('catatan')
                    ->label('Catatan Juri')
                    ->nullable()
                    ->default('Catatan :'),

                TextInput::make('link_figma')
                    ->label('Link Figma')
                    ->url()
                    ->maxLength(255),

                TextInput::make('pdf')
                    ->label('Link PDF')
                    ->url()
                    ->maxLength(255),

                TextInput::make('ppt')
                    ->label('Link PPT')
                    ->url()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tim.nama')
                    ->label('Nama Tim')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email_ketua')
                    ->label('Email Ketua')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('judul_proyek')
                    ->label('Judul Proyek')
                    ->searchable()
                    ->limit(50),

                IconColumn::make('link_figma')
                    ->label('Figma')
                    ->boolean()
                    ->trueIcon('heroicon-o-link')
                    ->falseIcon('heroicon-o-x-mark')
                    ->url(fn($record) => $record->link_figma)
                    ->openUrlInNewTab(),

                IconColumn::make('pdf')
                    ->label('PDF')
                    ->boolean()
                    ->trueIcon('heroicon-o-document-text')
                    ->falseIcon('heroicon-o-x-mark')
                    ->url(fn($record) => $record->pdf ? Storage::url($record->pdf) : null)
                    ->openUrlInNewTab(),

                IconColumn::make('ppt')
                    ->label('PPT')
                    ->boolean()
                    ->trueIcon('heroicon-o-presentation-chart-line')
                    ->falseIcon('heroicon-o-x-mark')
                    ->url(fn($record) => $record->ppt ? Storage::url($record->ppt) : null)
                    ->openUrlInNewTab(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListUiUxProgress::route('/'),
            'create' => Pages\CreateUiUxProgress::route('/create'),
            'edit' => Pages\EditUiUxProgress::route('/{record}/edit'),
        ];
    }
}
