<?php

namespace Modules\TemplateSettings\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class TemplateSettingsServiceProvider extends ServiceProvider
{

         /**
     * @var string $moduleName
     */
    protected $moduleName = 'TemplateSettings';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'templatesettings';
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        // $this->registerFactories();
        $this->loadMigrationsFrom(module_path('TemplateSettings', 'Database/Migrations'));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path('TemplateSettings', 'Config/config.php') => config_path('templatesettings.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('TemplateSettings', 'Config/config.php'), 'templatesettings'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/templatesettings');

        $sourcePath = module_path('TemplateSettings', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/templatesettings';
        }, \Config::get('view.paths')), [$sourcePath]), 'templatesettings');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/templatesettings');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'templatesettings');
        } else {
            $this->loadTranslationsFrom(module_path('TemplateSettings', 'Resources/lang'), 'templatesettings');
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production') && $this->app->runningInConsole()) {
            app(Factory::class)->load(module_path('TemplateSettings', 'Database/factories'));
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
