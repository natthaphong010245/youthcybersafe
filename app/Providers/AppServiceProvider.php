<?php
// app/Provider/AppServiceProvider.php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckRoleUser;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register LocalFileService แทน CloudinaryService
        $this->app->singleton(\App\Services\LocalFileService::class, function ($app) {
            return new \App\Services\LocalFileService();
        });
    }

    public function boot(): void
    {
        Route::aliasMiddleware('check.role', CheckRoleUser::class);
    }
}
