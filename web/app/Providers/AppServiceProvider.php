<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        \App\Models\Node::updating(function ($row) {
            //echo "updating";
        });
        \App\Models\Node::updated(function ($row) {
            //echo "updated";
            //dd($row);
        });
        \App\Models\Node::saving(function ($row) {
            //echo "saving";
        });
        \App\Models\Node::saved(function ($row) {
            //echo "saved";
            //dd($row);
        });
        \App\Models\Node::creating(function ($row) {
            //echo "creating";
            //dd($row);
        });
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
