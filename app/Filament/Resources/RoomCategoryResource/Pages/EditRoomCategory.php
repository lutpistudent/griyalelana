<?php

namespace App\Filament\Resources\RoomCategoryResource\Pages;

use App\Filament\Resources\RoomCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRoomCategory extends EditRecord
{
    protected static string $resource = RoomCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
