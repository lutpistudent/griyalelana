<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomResource\Pages;
use App\Models\Room;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RoomResource extends Resource
{
    protected static ?string $model = Room::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-key';
    protected static string | \UnitEnum | null $navigationGroup = 'Manajemen Kamar';
    protected static ?int $navigationSort = 3;
    protected static ?string $modelLabel = 'Kamar';
    protected static ?string $pluralModelLabel = 'Kamar';

    public static function form(Schema $form): Schema
    {
        return $form->schema([
            Section::make('Info Kamar')
                ->schema([
                    Select::make('room_type_id')
                        ->label('Tipe Kamar')
                        ->relationship('roomType', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),

                    TextInput::make('room_number')
                        ->label('Nomor Kamar')
                        ->required()
                        ->maxLength(20)
                        ->unique(ignoreRecord: true)
                        ->placeholder('Contoh: A-01'),

                    TextInput::make('floor')
                        ->label('Lantai')
                        ->required()
                        ->numeric()
                        ->minValue(1),

                    TextInput::make('position')
                        ->label('Posisi')
                        ->maxLength(100)
                        ->placeholder('Depan / Tengah / Belakang'),

                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'available' => '✅ Tersedia',
                            'occupied' => '🔴 Ditempati',
                            'maintenance' => '🔧 Perbaikan',
                            'inactive' => '⚪ Tidak Aktif',
                        ])
                        ->required()
                        ->default('available'),

                    Textarea::make('notes')
                        ->label('Catatan')
                        ->rows(2)
                        ->columnSpanFull(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('room_number')
                    ->label('No. Kamar')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('roomType.name')
                    ->label('Tipe')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('floor')
                    ->label('Lantai')
                    ->sortable(),

                TextColumn::make('position')
                    ->label('Posisi'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        'available' => 'Tersedia',
                        'occupied' => 'Ditempati',
                        'maintenance' => 'Perbaikan',
                        'inactive' => 'Tidak Aktif',
                        default => $state,
                    })
                    ->color(fn ($state) => match($state) {
                        'available' => 'success',
                        'occupied' => 'danger',
                        'maintenance' => 'warning',
                        'inactive' => 'gray',
                        default => 'gray',
                    }),

                TextColumn::make('roomType.price_per_year')
                    ->label('Harga/Tahun')
                    ->money('IDR', locale: 'id')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('room_number')
            ->filters([
                SelectFilter::make('room_type_id')
                    ->label('Tipe Kamar')
                    ->relationship('roomType', 'name'),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'available' => 'Tersedia',
                        'occupied' => 'Ditempati',
                        'maintenance' => 'Perbaikan',
                        'inactive' => 'Tidak Aktif',
                    ]),

                SelectFilter::make('floor')
                    ->label('Lantai')
                    ->options([1 => 'Lantai 1', 2 => 'Lantai 2', 3 => 'Lantai 3']),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRooms::route('/'),
            'create' => Pages\CreateRoom::route('/create'),
            'edit' => Pages\EditRoom::route('/{record}/edit'),
        ];
    }
}
