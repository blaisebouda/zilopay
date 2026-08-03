<?php

namespace App\Providers;

use App\Models\PaymentMethod;
use App\Models\Wallet;
use App\Observers\PaymentMethodObserver;
use App\Observers\WalletObserver;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\Routing\UrlGenerator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(UrlGenerator $url): void
    {
        $this->configureDefaults();
        $this->configureObservers();

        if (env('APP_ENV') === 'production') {
            $url->forceScheme('https');
        }
    }



    /**
     * Configure model observers.
     */
    protected function configureObservers(): void
    {
        Wallet::observe(WalletObserver::class);
        PaymentMethod::observe(PaymentMethodObserver::class);
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        Carbon::setLocale('fr');

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(
            fn(): ?Password => app()->isProduction()
                ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
                : null
        );
    }
}
