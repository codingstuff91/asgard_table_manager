<?php

namespace App\Filament\Resources\GameResource\Pages;

use App\Filament\Resources\GameResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Session;

class CreateGame extends CreateRecord
{
    protected static string $resource = GameResource::class;

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Nouveau jeu ajouté';
    }

    protected function getRedirectUrl(): string
    {
        if (Session::get('create_table_url')) {
            return Session::get('create_table_url');
        }

        return $this->getResource()::getUrl('index');
    }
}
