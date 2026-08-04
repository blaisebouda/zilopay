<?php

use App\Providers\AppServiceProvider;
use App\Providers\AuthServiceProvider;
use App\Providers\Filament\AdminPanelProvider;
use App\Providers\TelescopeServiceProvider;

return [
    AppServiceProvider::class,
    AuthServiceProvider::class,
    AdminPanelProvider::class,
    ...(class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)
        ? [App\Providers\TelescopeServiceProvider::class]
        : []),
];
