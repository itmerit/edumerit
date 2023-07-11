<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    // protected $router;
    // public function __construct()
    // {
    //      $router = RouteService();
    // }
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';
    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();
        $this->mapAdminRoutes();
        $this->mapStudentRoutes();
        $this->mapParentRoutes();
        $this->mapTeacherRoutes();
        
        $this->mapConfigureRoutes();

        //
    }

    // public function map(Router $router, Request $request)
    // {
    //     $locale = $request->segment(1);
    //     $this->app->setLocale($locale);

    //     $router->group(['namespace' => $this->namespace, 'prefix' => $locale], function($router) {
    //         require app_path('Http/routes.php');
    //     });
    // }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }
    protected function mapAdminRoutes()
    {
        Route::middleware(['web','2fa'])
             ->namespace($this->namespace)
             ->group(base_path('routes/admin.php'));
    }
    protected function mapStudentRoutes()
    {
        Route::middleware(['web','2fa','fees_due_check'])
             ->namespace($this->namespace)
             ->group(base_path('routes/student.php'));
    }
    protected function mapParentRoutes()
    {
        Route::middleware(['web','2fa','fees_due_check'])
             ->namespace($this->namespace)
             ->group(base_path('routes/parent.php'));
    }
    protected function mapTeacherRoutes()
    {
        Route::middleware(['web','2fa'])
             ->namespace($this->namespace)
             ->group(base_path('routes/teacher.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }

    // configuration route

    protected function mapConfigureRoutes()
    {
        Route::namespace($this->namespace)
             ->group(base_path('routes/configuration.php'));
    }
}
