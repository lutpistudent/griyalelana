<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomTypeResource\Pages;
use App\Models\RoomType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class RoomTypeResource extends Resource
{
    protected static ?string $model = RoomType::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-home-modern';
    protected static string | \UnitEnum | null $navigationGroup = 'Manajemen Kamar';
    protected static ?int $navigationSort = 2;
    protected static ?string $modelLabel = 'Tipe Kamar';
    protected static ?string $pluralModelLabel = 'Tipe Kamar';

    public static function form(Schema $form): Schema
    {
        return $form->schema([
            Section::make('Informasi Dasar')
                ->schema([
                    TextInput::make('name')
                        ->label('Nama Tipe')
                        ->required()
                        ->maxLength(100)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, Set $set) => $set('slug', Str::slug($state))),

                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->unique(ignoreRecord: true),

                    Select::make('category_id')
                        ->label('Kategori')
                        ->relationship('category', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),

                    TextInput::make('price_per_year')
                        ->label('Harga per Tahun (Rp)')
                        ->numeric()
                        ->prefix('Rp')
                        ->required(),
                ])->columns(2),

            Section::make('Spesifikasi Kamar')
                ->schema([
                    Toggle::make('has_ac')
                        ->label('AC')
                        ->default(false),

                    Select::make('bathroom_type')
                        ->label('Tipe Kamar Mandi')
                        ->options([
                            'inside' => 'KM Dalam',
                            'outside' => 'KM Luar',
                        ])
                        ->required(),

                    TextInput::make('room_size')
                        ->label('Luas Kamar (m²)')
                        ->numeric()
                        ->placeholder('12.00'),

                    TextInput::make('bed_size')
                        ->label('Ukuran Kasur')
                        ->maxLength(50)
                        ->placeholder('Single / Double'),
                ])->columns(2),

            Section::make('Detail Tambahan')
                ->schema([
                    Textarea::make('facilities')
                        ->label('Fasilitas')
                        ->rows(3)
                        ->helperText('Format JSON, contoh: ["WiFi","Meja Belajar","Lemari"]'),

                    Textarea::make('description')
                        ->label('Deskripsi')
                        ->rows(3),

                    Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable(),

                TextColumn::make('price_per_year')
                    ->label('Harga/Tahun')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                IconColumn::make('has_ac')
                    ->label('AC')
                    ->boolean(),

                TextColumn::make('bathroom_type')
                    ->label('KM')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        'inside' => 'KM Dalam',
                        'outside' => 'KM Luar',
                        default => $state,
                    })
                    ->color(fn ($state) => match($state) {
                        'inside' => 'success',
                        'outside' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('room_size')
                    ->label('Luas (m²)')
                    ->suffix(' m²')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('rooms_count')
                    ->label('Jml Kamar')
                    ->counts('rooms'),
            ])
            ->defaultSort('name')
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name'),

                TernaryFilter::make('has_ac')
                    ->label('AC'),

                SelectFilter::make('bathroom_type')
                    ->label('Tipe KM')
                    ->options([
                        'inside' => 'KM Dalam',
                        'outside' => 'KM Luar',
                    ]),
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

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoomTypes::route('/'),
            'create' => Pages\CreateRoomType::route('/create'),
            'edit' => Pages\EditRoomType::route('/{record}/edit'),
        ];
    }
}
