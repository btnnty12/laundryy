<?php

namespace App\Providers\Filament;

use App\Filament\Customer\Pages\Auth\Login;
use App\Filament\Customer\Pages\Auth\Register;
use App\Filament\Customer\Pages\Dashboard;
use App\Filament\Customer\Resources\PaymentResource;
use App\Filament\Customer\Resources\ServiceResource;
use App\Filament\Customer\Widgets\CustomerStatsWidget;
use App\Filament\Customer\Widgets\ServicesOverviewWidget;
use App\Filament\Customer\Widgets\RecentPaymentsWidget;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class CustomerPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('customer')
            ->path('customer')
            ->login(Login::class)
            ->registration(Register::class)
            ->colors([
                'primary' => Color::Blue,
            ])
            ->resources([
                ServiceResource::class,
                PaymentResource::class,
            ])
            ->discoverResources(in: app_path('Filament/Customer/Resources'), for: 'App\\Filament\\Customer\\Resources')
            ->discoverPages(in: app_path('Filament/Customer/Pages'), for: 'App\\Filament\\Customer\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Customer/Widgets'), for: 'App\\Filament\\Customer\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                CustomerStatsWidget::class,
                ServicesOverviewWidget::class,
                RecentPaymentsWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->brandName('Customer Portal')
            ->favicon(asset('favicon.ico'))
            ->darkMode(false);
    }
}