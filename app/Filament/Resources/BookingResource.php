<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Mail\BookingApproved;
use App\Models\Booking;
use App\Services\BookingService;
use App\Services\ContractPdfService;
use Illuminate\Support\Facades\Mail;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-calendar-days';
    protected static string | \UnitEnum | null $navigationGroup = 'Booking & Kontrak';
    protected static ?int $navigationSort = 1;
    protected static ?string $modelLabel = 'Booking';
    protected static ?string $pluralModelLabel = 'Booking';

    public static function form(Schema $form): Schema
    {
        return $form->schema([
            Section::make('Info Booking')
                ->schema([
                    Select::make('user_id')
                        ->label('Penyewa')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),

                    Select::make('room_id')
                        ->label('Kamar')
                        ->relationship('room', 'room_number')
                        ->searchable()
                        ->preload()
                        ->required(),

                    DatePicker::make('check_in_date')
                        ->label('Tanggal Check-in')
                        ->required(),

                    TextInput::make('duration_years')
                        ->label('Durasi (tahun)')
                        ->numeric()
                        ->default(1)
                        ->required(),

                    Select::make('payment_option')
                        ->label('Opsi Pembayaran')
                        ->options([
                            'with_dp' => 'Opsi A — Booking + DP 30%',
                            'direct_checkin' => 'Opsi B — Direct Check-in',
                        ])
                        ->required(),

                    TextInput::make('total_amount')
                        ->label('Total (Rp)')
                        ->numeric()
                        ->prefix('Rp')
                        ->required(),

                    TextInput::make('dp_amount')
                        ->label('DP (Rp)')
                        ->numeric()
                        ->prefix('Rp'),
                ])->columns(2),

            Section::make('Identitas')
                ->schema([
                    Select::make('identity_type')
                        ->label('Jenis Identitas')
                        ->options([
                            'ktp' => 'KTP',
                            'ktm' => 'KTM',
                            'other' => 'Lainnya',
                        ])
                        ->required(),

                    TextInput::make('identity_number')
                        ->label('Nomor Identitas')
                        ->required(),

                    TextInput::make('emergency_contact')
                        ->label('Kontak Darurat')
                        ->tel(),
                ])->columns(2),

            Section::make('Status')
                ->schema([
                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'pending' => '⏳ Menunggu',
                            'approved' => '✅ Disetujui',
                            'rejected' => '❌ Ditolak',
                            'cancelled' => '🚫 Dibatalkan',
                            'expired' => '⏰ Kedaluwarsa',
                        ])
                        ->required()
                        ->default('pending'),

                    DateTimePicker::make('dp_expires_at')
                        ->label('Batas Bayar DP'),

                    DateTimePicker::make('approved_at')
                        ->label('Tanggal Disetujui'),

                    Textarea::make('rejected_reason')
                        ->label('Alasan Ditolak')
                        ->rows(2)
                        ->columnSpanFull(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Penyewa')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('room.room_number')
                    ->label('Kamar')
                    ->sortable(),

                TextColumn::make('check_in_date')
                    ->label('Check-in')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR', locale: 'id'),

                TextColumn::make('payment_option')
                    ->label('Opsi')
                    ->formatStateUsing(fn ($state) => $state === 'with_dp' ? 'DP 30%' : 'Direct')
                    ->badge()
                    ->color(fn ($state) => $state === 'with_dp' ? 'info' : 'success'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        'cancelled' => 'Dibatalkan',
                        'expired' => 'Kedaluwarsa',
                        default => $state,
                    })
                    ->color(fn ($state) => match($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'cancelled' => 'gray',
                        'expired' => 'gray',
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
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        'cancelled' => 'Dibatalkan',
                        'expired' => 'Kedaluwarsa',
                    ]),
            ])
            ->actions([
                // Approve action — only visible for pending bookings
                Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Booking')
                    ->modalDescription('Booking akan disetujui dan kamar akan di-lock. Untuk Opsi A, penyewa memiliki 12 jam untuk membayar DP.')
                    ->action(function (Booking $record) {
                        $service = new BookingService();
                        $service->approve($record);

                        // For direct_checkin, immediately create contract
                        if ($record->payment_option === 'direct_checkin') {
                            $contract = $service->createContract($record);
                            try {
                                $pdfService = new ContractPdfService();
                                $pdfService->generate($contract);
                            } catch (\Exception $e) {
                                // PDF generation not critical — can retry later
                            }
                        }

                        Notification::make()
                            ->title('Booking disetujui!')
                            ->success()
                            ->send();

                        // Send email to tenant
                        if ($record->user && $record->user->email) {
                            try {
                                Mail::to($record->user->email)->send(new BookingApproved($record));
                            } catch (\Throwable $e) {
                                Notification::make()
                                    ->title('Email gagal dikirim')
                                    ->body($e->getMessage())
                                    ->warning()
                                    ->send();
                            }
                        }
                    })
                    ->visible(fn (Booking $record) => $record->status === 'pending'),

                // Reject action
                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Booking')
                    ->form([
                        Textarea::make('rejected_reason')
                            ->label('Alasan Penolakan')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (Booking $record, array $data) {
                        $service = new BookingService();
                        $service->reject($record, $data['rejected_reason']);

                        Notification::make()
                            ->title('Booking ditolak')
                            ->warning()
                            ->send();
                    })
                    ->visible(fn (Booking $record) => $record->status === 'pending'),

                // Create contract for approved DP bookings (after DP is paid)
                Action::make('create_contract')
                    ->label('Buat Kontrak')
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    ->requiresConfirmation()
                    ->modalHeading('Buat Kontrak')
                    ->modalDescription('Kontrak dan jadwal pembayaran akan digenerate otomatis. Pastikan DP sudah dibayar.')
                    ->action(function (Booking $record) {
                        $service = new BookingService();
                        $contract = $service->createContract($record);

                        try {
                            $pdfService = new ContractPdfService();
                            $pdfService->generate($contract);
                        } catch (\Exception $e) {
                            // PDF generation can be retried
                        }

                        Notification::make()
                            ->title('Kontrak berhasil dibuat!')
                            ->body("No: {$contract->contract_number}")
                            ->success()
                            ->send();
                    })
                    ->visible(fn (Booking $record) => $record->status === 'approved' && !$record->contract),

                ViewAction::make(),
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
