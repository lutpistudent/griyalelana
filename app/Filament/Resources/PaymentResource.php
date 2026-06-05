<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\PaymentSchedule;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PaymentResource extends Resource
{
    protected static ?string $model = PaymentSchedule::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-banknotes';
    protected static string | \UnitEnum | null $navigationGroup = 'Booking & Kontrak';
    protected static ?int $navigationSort = 3;
    protected static ?string $modelLabel = 'Jadwal Bayar';
    protected static ?string $pluralModelLabel = 'Jadwal Bayar';

    public static function form(Schema $form): Schema
    {
        return $form->schema([
            Select::make('contract_id')
                ->label('Kontrak')
                ->relationship('contract', 'contract_number')
                ->disabled(),

            TextInput::make('installment_number')
                ->label('Cicilan ke-')
                ->disabled(),

            TextInput::make('installment_type')
                ->label('Jenis')
                ->disabled(),

            TextInput::make('amount')
                ->label('Jumlah (Rp)')
                ->prefix('Rp')
                ->disabled(),

            DatePicker::make('due_date')
                ->label('Jatuh Tempo'),

            Select::make('status')
                ->label('Status')
                ->options([
                    'pending' => 'Belum Bayar',
                    'paid' => 'Lunas',
                    'overdue' => 'Terlambat',
                ])
                ->required(),

            DatePicker::make('paid_at')
                ->label('Tanggal Bayar'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('contract.contract_number')
                    ->label('No. Kontrak')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('contract.user.name')
                    ->label('Penyewa')
                    ->searchable(),

                TextColumn::make('installment_number')
                    ->label('#')
                    ->sortable(),

                TextColumn::make('installment_type')
                    ->label('Jenis')
                    ->formatStateUsing(fn ($state) => ucwords(str_replace('_', ' ', $state))),

                TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR', locale: 'id'),

                TextColumn::make('due_date')
                    ->label('Jatuh Tempo')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        'pending' => 'Belum Bayar',
                        'paid' => 'Lunas',
                        'overdue' => 'Terlambat',
                        default => $state,
                    })
                    ->color(fn ($state) => match($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'overdue' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->defaultSort('due_date')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Belum Bayar',
                        'paid' => 'Lunas',
                        'overdue' => 'Terlambat',
                    ]),
            ])
            ->actions([
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
