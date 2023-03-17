<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // 開発者のみ許可
        Gate::define('root-only', function ($user) {
            return ($user->role == config('const.Roles.ROOT_ADMIN'));
        });
        // 非凍結アカウント 
        Gate::define('not-freezed', function($user){
            return ($user->active);
        });
        // 施設管理者アカウント以上に許可
        Gate::define('admin-higher', function ($user) {
            //return ($user->role == config('const.Roles.ROOT_ADMIN') || $user->role == config('const.Roles.ADMIN'));
            return ($user->role <= config('const.Roles.ADMIN'));
        });
        // 施設管理者アカウントのみに許可
        Gate::define('admin-only', function ($user) {
            return ($user->role == config('const.Roles.ADMIN'));
        });
        // 職員アカウント以上に許可
        Gate::define('worker-higher', function ($user) {
            return ($user->role > 0 && $user->role <= config('const.Roles.WORKER'));
        });
        // 職員アカウントのみに許可
        Gate::define('worker-only', function ($user) {
            return ($user->role == config('const.Roles.WORKER'));
        });
        // 保護者アカウント以上に許可
        Gate::define('parent-higher', function ($user) {
            return ($user->role > 0 && $user->role <= config('const.Roles.PARENT'));
        });
        // 保護者アカウントのみに許可
        Gate::define('parent-only', function ($user) {
            return ($user->role == config('const.Roles.PARENT'));
        });
        // 学校(教員)アカウントのみに許可
        Gate::define('teacher-only', function ($user) {
            return ($user->role == config('const.Roles.TEACHER'));
        });
    }
}
