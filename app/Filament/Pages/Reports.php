<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Reports extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static string | \UnitEnum | null $navigationGroup = 'Laporan';
    protected static ?int $navigationSort = 10;
    protected static ?string $navigationLabel = 'Export Laporan';
    protected static ?string $title = 'Export Laporan';

    public function getView(): string
    {
        return 'filament.pages.reports';
    }
}
