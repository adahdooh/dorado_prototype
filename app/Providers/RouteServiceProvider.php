<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    // public const HOME = '/admin/home';
    // public const PATIENT_HOME = '/patient/home';
    public const MOBILE = '/mobile';
    // public const CONTROL_PANEL = '/c_panel';

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

        $this->mapWebRoutes();

        $this->mapMobileApiRoutes();

        // $this->mapCPanelApiRoutes();

        //
    }

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

    protected function mapMobileApiRoutes()
    {
        Route::prefix('api'.$this::MOBILE)
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api/mobile.api.php'));
    }

    // protected function mapCPanelApiRoutes()
    // {
    //     Route::prefix('api'.$this::CONTROL_PANEL)
    //          ->middleware('api')
    //          ->namespace($this->namespace)
    //          ->group(base_path('routes/api/c_panel.api.php'));
    // }
}
