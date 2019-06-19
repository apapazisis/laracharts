<?php

namespace Apapazisis\Echarts;

use Illuminate\Support\ServiceProvider;

class EchartsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/Views', 'echarts');

        $this->publishes([
            __DIR__.'/js/worker.js' => public_path('vendor/charts'),
            __DIR__.'/js/charts.js' => public_path('vendor/charts'),
        ], 'public');

        $this->publishes([
            __DIR__.'/Views' => resource_path('views/vendor/echarts'),
        ], 'view');
    }
}
