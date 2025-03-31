<?php

namespace App\Filament\Resources\GameResource\Pages;

use App\Filament\Resources\GameResource;
use App\Models\Category;
use App\Storages\AssociationStorage;
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
        $categories = Category::query()
            ->where('association_id', AssociationStorage::current()->id)
            ->get()
            ->toArray();

        $types = [];

        foreach ($categories as $category) {
            $types[$category['name']] = Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('category_id', $category['id']));
        }

        return $types;
    }
}
