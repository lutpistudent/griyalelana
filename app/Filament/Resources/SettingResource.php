<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static string | \UnitEnum | null $navigationGroup = 'Pengaturan';
    protected static ?int $navigationSort = 2;
    protected static ?string $modelLabel = 'Pengaturan';
    protected static ?string $pluralModelLabel = 'Pengaturan';

    public static function form(Schema $form): Schema
    {
        return $form->schema([
            TextInput::make('key')
                ->label('Key')
                ->required()
                ->unique(ignoreRecord: true)
                ->disabled(fn (string $operation) => $operation === 'edit'),

            Textarea::make('value')
                ->label('Nilai')
                ->rows(3),

            Select::make('type')
                ->label('Tipe')
                ->options([
                    'string' => 'String',
                    'integer' => 'Integer',
                    'boolean' => 'Boolean',
                    'json' => 'JSON',
                ])
                ->default('string'),

            Textarea::make('description')
                ->label('Deskripsi')
                ->rows(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')
                    ->label('Key')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('value')
                    ->label('Nilai')
                    ->limit(50)
                    ->searchable(),

                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge(),

                TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(40)
                    ->toggleable(),
            ])
            ->defaultSort('key')
            ->actions([
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
