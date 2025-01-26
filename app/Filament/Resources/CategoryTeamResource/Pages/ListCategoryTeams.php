<?php

namespace App\Filament\Resources\CategoryTeamResource\Pages;

use App\Filament\Resources\CategoryTeamResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCategoryTeams extends ListRecords
{
    protected static string $resource = CategoryTeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
