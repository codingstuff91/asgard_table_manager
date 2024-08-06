<?php

namespace App\Actions;

use App\Models\Table;
use Illuminate\Support\Facades\Auth;

class UserSubscriptionAction
{
    public function execute(Table $table): void
    {
        $currentUser = Auth::user();

        $table->users()->attach($currentUser);
    }
}
