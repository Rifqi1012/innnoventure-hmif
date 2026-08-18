<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WebdevProgressResource\Pages;
use App\Models\WebdevProgress;
use App\Models\Tim;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;

class WebdevProgressResource extends Resource
{
    protected static ?string $model = WebdevProgress::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';
    protected static ?string $navigationGroup = 'Perlombaan';
    protected static ?int $navigationSort = 9;
    protected static ?string $pluralModelLabel = 'Web Dev';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('tim_id')
                    ->label('Tim')
                    ->relationship('tim', 'nama')
                    ->options(function () {
                        return Tim::whereHas('cabangLomba', function ($query) {
                            $query->where('nama', 'Web Development');
                        })->pluck('nama', 'id');
                    })
                    ->searchable()
                    ->required(),

                TextInput::make('email_ketua')
                    ->label('Email Ketua')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),

                TextInput::make('judul_proyek')
                    ->label('Judul Proyek')
                    ->required()
                    ->maxLength(255),

                TextArea::make('catatan')
                    ->label('Catatan Juri')
                    ->required()
                    ->default('Catatan :'),

                TextInput::make('link_github')
                    ->label('Link Repository (GitHub/Drive)')
                    ->url()
                    ->maxLength(255)
                    ->placeholder('https://github.com/username/repository'),

                TextInput::make('link_demo')
                    ->label('Link Demo (YouTube/Drive)')
                    ->url()
                    ->maxLength(255)
                    ->placeholder('https://youtube.com/watch?v=...'),

                TextInput::make('link_hosting')
                    ->label('Link Hosting')
                    ->url()
                    ->maxLength(255)
                    ->placeholder('https://yourwebsite.com'),

                TextInput::make('ppt')
                    ->label('Link Drive PPT')
                    ->url()
                    ->maxLength(255)
                    ->placeholder('https://ppt.com'),

                // FileUpload::make('ppt')
                //     ->label('File Presentasi (PPT/PPTX)')
                //     ->acceptedFileTypes([
                //         'application/vnd.ms-powerpoint',
                //         'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                //         'application/pdf'
                //     ])
                //     ->maxSize(5120) // 5MB
                //     ->directory('webdev/presentasi')
                //     ->downloadable()
                //     ->openable()
                //     ->nullable(),
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

                TextColumn::make('tim.instansi.nama')
                    ->label('Instansi')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email_ketua')
                    ->label('Email Ketua')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('judul_proyek')
                    ->label('Judul Proyek')
                    ->searchable()
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    }),

                IconColumn::make('pdf')
                    ->label('PDF')
                    ->boolean()
                    ->trueIcon('heroicon-o-document-text')
                    ->falseIcon('heroicon-o-x-mark')
                    ->colors([
                        'success' => true,
                        'danger' => false,
                    ])
                    ->url(fn($record) => $record->pdf ? Storage::url($record->pdf) : null)
                    ->openUrlInNewTab(),

                IconColumn::make('link_github')
                    ->label('Repo')
                    ->boolean()
                    ->trueIcon('heroicon-o-link')
                    ->falseIcon('heroicon-o-x-mark')
                    ->colors([
                        'success' => true,
                        'danger' => false,
                    ])
                    ->url(fn($record) => $record->link_github)
                    ->openUrlInNewTab(),

                IconColumn::make('link_demo')
                    ->label('Demo')
                    ->boolean()
                    ->trueIcon('heroicon-o-play-circle')
                    ->falseIcon('heroicon-o-x-mark')
                    ->colors([
                        'success' => true,
                        'danger' => false,
                    ])
                    ->url(fn($record) => $record->link_demo)
                    ->openUrlInNewTab(),

                IconColumn::make('link_hosting')
                    ->label('Hosting')
                    ->boolean()
                    ->trueIcon('heroicon-o-globe-alt')
                    ->falseIcon('heroicon-o-x-mark')
                    ->colors([
                        'success' => true,
                        'danger' => false,
                    ])
                    ->url(fn($record) => $record->link_hosting)
                    ->openUrlInNewTab(),

                IconColumn::make('ppt')
                    ->label('PPT')
                    ->boolean()
                    ->trueIcon('heroicon-o-presentation-chart-line')
                    ->falseIcon('heroicon-o-x-mark')
                    ->colors([
                        'success' => true,
                        'danger' => false,
                    ])
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
                    ExportBulkAction::make()->exports([
                        ExcelExport::make('export')->fromTable()->withColumns([
                            Column::make('link_github')->heading('Link GitHub')->getStateUsing(fn ($record) => $record->link_github ? (filter_var($record->link_github, FILTER_VALIDATE_URL) ? $record->link_github : url(\Illuminate\Support\Facades\Storage::url($record->link_github))) : null),
                            Column::make('link_demo')->heading('Link Demo')->getStateUsing(fn ($record) => $record->link_demo ? (filter_var($record->link_demo, FILTER_VALIDATE_URL) ? $record->link_demo : url(\Illuminate\Support\Facades\Storage::url($record->link_demo))) : null),
                            Column::make('link_hosting')->heading('Link Hosting')->getStateUsing(fn ($record) => $record->link_hosting ? (filter_var($record->link_hosting, FILTER_VALIDATE_URL) ? $record->link_hosting : url(\Illuminate\Support\Facades\Storage::url($record->link_hosting))) : null),
                            Column::make('ppt')->heading('Link PPT')->getStateUsing(fn ($record) => $record->ppt ? (filter_var($record->ppt, FILTER_VALIDATE_URL) ? $record->ppt : url(\Illuminate\Support\Facades\Storage::url($record->ppt))) : null),
                            Column::make('pdf')->heading('Link PDF')->getStateUsing(fn ($record) => $record->pdf ? (filter_var($record->pdf, FILTER_VALIDATE_URL) ? $record->pdf : url(\Illuminate\Support\Facades\Storage::url($record->pdf))) : null),
                        ]),
                    ]),
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
            'index' => Pages\ListWebdevProgress::route('/'),
            'create' => Pages\CreateWebdevProgress::route('/create'),
            'edit' => Pages\EditWebdevProgress::route('/{record}/edit'),
        ];
    }
}
