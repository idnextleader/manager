<?php

namespace App\Filament\Resources\CategoryTeamResource\Pages;

use App\Filament\Resources\CategoryTeamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCategoryTeam extends EditRecord
{
    protected static string $resource = CategoryTeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
