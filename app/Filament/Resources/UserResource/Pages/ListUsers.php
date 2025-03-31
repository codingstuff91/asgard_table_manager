<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\Association;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Nouvel Utilisateur'),
        ];
    }

    public function getTabs(): array
    {
        $associations = Association::query()
            ->get()
            ->toArray();

        foreach ($associations as $association) {
            $types[$association['name']] = Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('association_id', $association['id']));
        }

        return $types;
    }
}
