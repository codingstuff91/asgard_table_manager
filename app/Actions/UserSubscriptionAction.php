<?php

namespace App\Actions;

use App\Models\Table;
use App\Models\User;

class UserSubscriptionAction
{
    public function execute(Table $table, User $user): void
    {
        $table->users()->attach($user);
    }
}
