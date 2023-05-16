<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Table;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('unsubscribe_user', function (User $user, Table $table) {
            return $user->id === $table->organizer_id;
        });

        Gate::define('delete_table', function (User $user, Table $table) {
            return $user->id === $table->organizer_id || $user->admin ;
        });

        Gate::define('edit_table', function (User $user, Table $table) {
            return $user->id === $table->organizer_id || $user->admin ;
        });
    }
}
