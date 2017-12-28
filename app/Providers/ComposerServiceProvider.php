<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use View;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()//
    {
      View::composer(
        ['partials.admin.top-nav', 'partials.admin.nav', 'partials.user.top-nav', 'partials.user.nav'], 'App\Http\ViewComposers\Dashboard'
      );

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
