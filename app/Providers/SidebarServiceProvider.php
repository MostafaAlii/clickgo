<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
class SidebarServiceProvider extends ServiceProvider {
    public function register(): void {
        //
    }

    public function boot(): void {
        Blade::directive('authGuard', function () {
            return "<?php \$guard = check_guard(); if (\$guard): ?>";
        });
        Blade::directive('endAuthGuard', function () {
            return "<?php endif; ?>";
        });
    }
}
