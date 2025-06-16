<?php

namespace App\Providers;

use App\Models\Contact;
use App\Models\User;
use Livewire\Livewire;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if($this->app->environment('production')){
            // URL::forceScheme('https');
            $this->app->usePublicPath(base_path('public_html'));
        }
        require_once base_path('src/Support/Helpers/helpers.php');
        require_once base_path('src/Support/Helpers/render.php');
        require_once base_path('src/Support/Helpers/dateHelpers.php');

        Livewire::setUpdateRoute(function ($handle) {
            return Route::post(config('livewire.assets_path').'/livewire/update', $handle)
            ->name('livewire-update')
            ->middleware(['web','auth']);
        });
        Livewire::setScriptRoute(function ($handle) {
            return Route::get(config('livewire.assets_path').'/livewire/livewire.js', $handle);
        });
        Paginator::useBootstrap();

        // Relation::enforceMorphMap([
        //     'user' => User::class,
        //     'contact' => Contact::class,
        // ]);


    }
}
