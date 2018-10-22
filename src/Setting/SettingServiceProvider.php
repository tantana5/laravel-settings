<?php

namespace Tantana5\Setting;

use Illuminate\Support\ServiceProvider;

/**
 * Class SettingServiceProvider.
 */
class SettingServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $filename = '2018_10_22_141216_create_settings_table.php';

        $this->publishes([
            __DIR__.'/../../database/migrations/'.$filename => base_path('/database/migrations/'.$filename),
        ], 'settings');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Setting', Setting::class);
        $this->app->bind(SettingStorageContract::class, EloquentStorage::class);
    }
}
