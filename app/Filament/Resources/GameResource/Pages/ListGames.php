<?php

namespace App\Filament\Resources\GameResource\Pages;

use App\Filament\Resources\GameResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListGames extends ListRecords
{
    protected static string $resource = GameResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Nouveau jeu'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'Tout types' => Tab::make(),
            'Cartes' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('category_id', 1)),
            'Plateau' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('category_id', 2)),
            'Rôles' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('category_id', 3)),
            'Wargame' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('category_id', 4)),
        ];
    }
}
