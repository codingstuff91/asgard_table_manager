<?php

namespace App\Console\Commands;

use App\Models\Association;
use App\Models\User;
use Illuminate\Console\Command;

class AssociateUsersToAssociation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:associate-to-association';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Associate current users to an association';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $users = User::all();
        $association = Association::first();

        $users->each(function ($user) use ($association) {
            $user->associations()->attach($association);
        });

        $this->line('Users linking to association finished');
    }
}
