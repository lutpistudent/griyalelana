<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContractResource\Pages;
use App\Models\Contract;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ContractResource extends Resource
{
    protected static ?string $model = Contract::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';
    protected static string | \UnitEnum | null $navigationGroup = 'Booking & Kontrak';
    protected static ?int $navigationSort = 2;
    protected static ?string $modelLabel = 'Kontrak';
    protected static ?string $pluralModelLabel = 'Kontrak';

    public static function form(Schema $form): Schema
    {
        return $form->schema([
            Section::make('Info Kontrak')
                ->schema([
                    TextInput::make('contract_number')
                        ->label('No. Kontrak')
                        ->disabled(),

                    Select::make('user_id')
                        ->label('Penyewa')
                        ->relationship('user', 'name')
                        ->disabled(),

                    Select::make('room_id')
                        ->label('Kamar')
                        ->relationship('room', 'room_number')
                        ->disabled(),

                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'active' => 'Aktif',
                            'completed' => 'Selesai',
                            'terminated' => 'Dihentikan',
                        ])
                        ->required(),
                ])->columns(2),

            Section::make('Periode & Biaya')
                ->schema([
                    DatePicker::make('start_date')
                        ->label('Mulai')
                        ->disabled(),

                    DatePicker::make('end_date')
                        ->label('Berakhir')
                        ->disabled(),

                    TextInput::make('duration_years')
                        ->label('Durasi (tahun)')
                        ->disabled(),

                    TextInput::make('total_amount')
                        ->label('Total (Rp)')
                        ->prefix('Rp')
                        ->disabled(),

                    Select::make('payment_option')
                        ->label('Opsi Bayar')
                        ->options([
                            'with_dp' => 'DP 30%',
                            'direct_checkin' => 'Direct Check-in 50%',
                        ])
                        ->disabled(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('contract_number')
                    ->label('No. Kontrak')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Penyewa')
                    ->searchable(),

                TextColumn::make('room.room_number')
                    ->label('Kamar')
                    ->sortable(),

                TextColumn::make('start_date')
                    ->label('Mulai')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('end_date')
                    ->label('Berakhir')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR', locale: 'id'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        'active' => 'Aktif',
                        'completed' => 'Selesai',
                        'terminated' => 'Dihentikan',
                        default => $state,
                    })
                    ->color(fn ($state) => match($state) {
                        'active' => 'success',
                        'completed' => 'info',
                        'terminated' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Aktif',
                        'completed' => 'Selesai',
                        'terminated' => 'Dihentikan',
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
            'index' => Pages\ListContracts::route('/'),
            'edit' => Pages\EditContract::route('/{record}/edit'),
        ];
    }
}
