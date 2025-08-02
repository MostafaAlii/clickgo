<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\AliasHelper;
class AppServiceProvider extends ServiceProvider {
    public function register(): void {
        //
    }

    public function boot(): void {
        /*$config = $this->app['config'];
        $modelAliases = AliasHelper::generateModelAliases();
        $allAliases = array_merge(
            $config->get('app.aliases', []),
            $modelAliases
        );
        dd($allAliases);
        $config->set('app.aliases', $allAliases);*/
    }
}
