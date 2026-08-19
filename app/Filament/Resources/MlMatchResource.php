<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MlMatchResource\Pages;
use App\Models\MlMatch;
use App\Models\Tim;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Columns\TextInputColumn;

class MlMatchResource extends Resource
{
    protected static ?string $model = MlMatch::class;

    protected static ?string $navigationIcon = 'heroicon-o-fire';
    protected static ?string $navigationGroup = 'Perlombaan';
    protected static ?int $navigationSort = 3;
    protected static ?string $pluralModelLabel = 'Mobile Legend';

    public static function canViewAny(): bool
    {
        return auth()->user()->role === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('round')
                    ->label('Round / Stage')
                    ->required()
                    ->numeric(),

                Forms\Components\Select::make('tim1_id')
                    ->label('Tim 1')
                    ->required()
                    ->relationship('tim1', 'nama')
                    ->options(function () {
                        return Tim::whereHas('cabangLomba', function ($query) {
                            $query->where('nama', 'Mobile Legend');
                        })->pluck('nama', 'id');
                    })
                    ->searchable(),

                Forms\Components\Select::make('tim2_id')
                    ->label('Tim 2')
                    ->required()
                    ->relationship('tim2', 'nama')
                    ->options(function () {
                        return Tim::whereHas('cabangLomba', function ($query) {
                            $query->where('nama', 'Mobile Legend');
                        })->pluck('nama', 'id');
                    })
                    ->searchable(),

                Forms\Components\Radio::make('best_of')
                    ->label('Best Of')
                    ->options([
                        1 => 'BO1',
                        3 => 'BO3',
                        5 => 'BO5',
                    ])
                    ->default(1)
                    ->inline()
                    ->required(),

                Forms\Components\TextInput::make('tim1_score')
                    ->numeric()
                    ->default(0)
                    ->required(),

                Forms\Components\TextInput::make('tim2_score')
                    ->numeric()
                    ->default(0)
                    ->required(),

                Forms\Components\TextInput::make('winner_id')
                    ->disabled()
                    ->numeric()
                    ->label('Winner (Auto)'),

                Forms\Components\Radio::make('status')
                    ->options([
                        'upcoming' => 'Upcoming',
                        'live'     => 'Live',
                        'finished' => 'Finished',
                    ])
                    ->inline()
                    ->required(),

                Forms\Components\DateTimePicker::make('start_time')
                    ->native(false),

                Forms\Components\DateTimePicker::make('end_time')
                    ->native(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->groups([
                Group::make('round')
                    ->collapsible()
                    ->label('Stage'),
            ])
            ->defaultGroup('round')
            ->columns([
                Tables\Columns\TextColumn::make('tim1.nama')->label('Tim 1')->color('danger'),
                Tables\Columns\TextColumn::make('tim2.nama')->label('Tim 2')->color('info'),
                Tables\Columns\TextColumn::make('best_of')->label('BO')->badge()->color('gray'),
                Tables\Columns\TextInputColumn::make('tim1_score')
                    ->disabled(fn(MlMatch $record): bool => $record->status === 'finished'),
                Tables\Columns\TextInputColumn::make('tim2_score')
                    ->disabled(fn(MlMatch $record): bool => $record->status === 'finished'),
                Tables\Columns\TextColumn::make('winner.nama')->label('Winner')->badge()->color('success'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'upcoming',
                        'danger' => 'live',
                        'success' => 'finished',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'upcoming' => 'Upcoming',
                        'live'     => 'Live',
                        'finished' => 'Finished',
                        default => $state,
                    }),
            ])
            ->defaultSort('round')
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('toggleStatus')
                    ->label('Ganti Status')
                    ->disabled(fn (MlMatch $record): bool => $record->status === 'finished' || $record->status === 'live')
                    ->icon('heroicon-m-arrow-path')
                    ->color('primary')
                    ->action(function (MlMatch $record) {
                        $currentStatus = $record->status;
                        $nextStatus = match ($currentStatus) {
                            'upcoming' => 'live',
                            'live' => 'finished',
                            'finished' => 'upcoming',
                            default => 'upcoming'
                        };

                        $record->update(['status' => $nextStatus]);

                        return redirect()->back()->with('success', 'Status berhasil diubah menjadi ' . ucfirst($nextStatus));
                    })
                    ->requiresConfirmation()
                    // ->modalHeading('Konfirmasi Perubahan Status')
                    ->modalHeading(
                        fn(MlMatch $record) =>
                        "Ubah status dari {$record->status} ke " .
                            match ($record->status) {
                                'upcoming' => 'live',
                                'live' => 'finished',
                                default => 'upcoming'
                            } . "?"
                    )
                    ->modalSubmitActionLabel('Ya, Ubah Status'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMlMatches::route('/'),
            'create' => Pages\CreateMlMatch::route('/create'),
            'edit' => Pages\EditMlMatch::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->orderBy('round');
    }
}
