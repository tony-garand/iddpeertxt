<?php

namespace peertxt\Providers;

use peertxt\models\Contact;
use peertxt\Observers\ContactObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
		Schema::defaultStringLength(191);
        Contact::observe(ContactObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
