<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * @date blade directive
         * use as @date($object->datefield)
         * or with a format @date($object->datefield,'m/d/Y')
         */
        Blade::directive('date', function ($expression) {
            $default = "'Y-m-d H:i:s'";           //set default format if not present in $expression

            $parts = str_getcsv($expression);
            $parts[1] = (isset($parts[1]))?$parts[1]:$default;
            return '<?php if(' . $parts[0] . '){ echo e(' . $parts[0] . '->format(' . $parts[1] . ')); } ?>';
        });

    }
}
