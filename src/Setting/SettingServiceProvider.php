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
        $this->publishes([
            __DIR__.'/../../database/migrations/2018_10_22_141216_create_settings_table.php.stub' => base_path('/database/migrations/'.date('Y_m_d_His', time()).'_create_settings_table.php'),
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
