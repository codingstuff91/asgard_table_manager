<?php

namespace App\Providers;

use App\Models\Day;
use App\Models\Table;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
        Gate::define('unsubscribe_user', function (User $user, Table $table) {
            return $user->id === $table->organizer_id;
        });

        Gate::define('delete_table', function (User $user, Table $table) {
            if ($user->admin) {
                return true;
            }

            if ($user->id === $table->organizer_id) {
                return true;
            }

            return false;
        });

        Gate::define('edit_table', function (User $user, Table $table) {
            if ($user->admin) {
                return true;
            }

            if ($user->id === $table->organizer_id) {
                return true;
            }

            return false;
        });

        Gate::define('edit_event', function (User $user) {
            return $user->admin;
        });

        Gate::define('delete_event', function (User $user) {
            return $user->admin;
        });

        Gate::define('cancel_day', function (User $user, Day $day) {
            return $user->admin && ! $day->is_cancelled;
        });

        Gate::define('warning_day', function (User $user, Day $day) {
            return $user->admin && ! $day->is_cancelled;
        });
    }
}
