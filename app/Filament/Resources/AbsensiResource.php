<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AbsensiResource\Pages;
use App\Models\Absensi;
use App\Models\DaftarSeminar; // Pastikan Anda mengimpor model relasinya
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IndexColumn; // 1. Impor IndexColumn
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AbsensiResource extends Resource
{
    protected static ?string $model = Absensi::class;

    protected static ?string $navigationIcon = 'heroicon-o-qr-code'; // Ganti ikon agar lebih relevan
    protected static ?string $navigationGroup = 'Seminar'; // Ganti ikon agar lebih relevan
    protected static ?int $navigationSort = 10; // Ganti ikon agar lebih relevan

    public static function canViewAny(): bool
    {
        return auth()->user()->role === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // PERUBAHAN 1: Menambahkan kolom nomor urut (row index)
                TextColumn::make('No')
                    ->rowIndex(),

                // PERUBAHAN 2: Mengubah ID menjadi nama dari relasi
                TextColumn::make('daftarSeminar.nama') // Asumsi relasi: Absensi -> DaftarSeminar -> Peserta
                    ->label('Nama Peserta')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('waktu_absen')
                    ->label('Waktu Absen')
                    ->dateTime('d M Y, H:i') // Format tanggal agar lebih mudah dibaca
                    ->sortable(),

                TextColumn::make('keterangan'),
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
            'index' => Pages\ListAbsensis::route('/'),
            'create' => Pages\CreateAbsensi::route('/create'), // Halaman ini tetap ada, tapi tidak akan diakses dari tombol utama
            'edit' => Pages\EditAbsensi::route('/{record}/edit'),
        ];
    }
}
