<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComplaintResource\Pages;
use App\Models\Complaint;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ComplaintResource extends Resource
{
    protected static ?string $model = Complaint::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static string | \UnitEnum | null $navigationGroup = 'Pengelolaan';
    protected static ?int $navigationSort = 1;
    protected static ?string $modelLabel = 'Keluhan';
    protected static ?string $pluralModelLabel = 'Keluhan';

    public static function form(Schema $form): Schema
    {
        return $form->schema([
            Section::make('Detail Keluhan')
                ->schema([
                    Select::make('user_id')
                        ->label('Penyewa')
                        ->relationship('user', 'name')
                        ->disabled(),

                    Select::make('room_id')
                        ->label('Kamar')
                        ->relationship('room', 'room_number')
                        ->disabled(),

                    TextInput::make('title')
                        ->label('Judul')
                        ->disabled(),

                    Textarea::make('description')
                        ->label('Deskripsi')
                        ->disabled()
                        ->rows(4)
                        ->columnSpanFull(),
                ])->columns(2),

            Section::make('Respons Owner')
                ->schema([
                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'open' => '🟡 Menunggu',
                            'in_progress' => '🔵 Diproses',
                            'resolved' => '🟢 Selesai',
                            'closed' => '⚫ Ditutup',
                        ])
                        ->required(),

                    Textarea::make('owner_notes')
                        ->label('Catatan Owner')
                        ->rows(3)
                        ->placeholder('Tulis respons atau catatan untuk penyewa...')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Penyewa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('room.room_number')
                    ->label('Kamar')
                    ->sortable(),

                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->limit(40),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        'open' => 'Menunggu',
                        'in_progress' => 'Diproses',
                        'resolved' => 'Selesai',
                        'closed' => 'Ditutup',
                        default => $state,
                    })
                    ->color(fn ($state) => match($state) {
                        'open' => 'warning',
                        'in_progress' => 'info',
                        'resolved' => 'success',
                        'closed' => 'gray',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'open' => 'Menunggu',
                        'in_progress' => 'Diproses',
                        'resolved' => 'Selesai',
                        'closed' => 'Ditutup',
                    ]),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComplaints::route('/'),
            'edit' => Pages\EditComplaint::route('/{record}/edit'),
        ];
    }
}
