<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
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
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Navigation\NavigationItem;
use Filament\Navigation\MenuItem;
use App\Filament\Resources\CustomerResource;
use App\Models\Customer;



class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {

        $counts = Customer::query()
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->all();

        return $panel
            ->default()
            ->id('admin')
            ->path('filament/admin')
            ->login()
            ->maxContentWidth('1280px')
            ->colors([
                'primary' => Color::Amber,
            ])
            //->sidebarFullyCollapsibleOnDesktop()
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])





            ->navigationItems([

                NavigationItem::make('New Customers')
                    ->url(fn (): string => CustomerResource::getUrl('index', ['tableFilters' => ['status' => ['value' => 'new']]]))
                    ->icon('heroicon-o-user-plus')
                    ->group('Customers')
                    ->badge($counts['new'] ?? 0),
                NavigationItem::make('Active Customers')
                    ->url(fn (): string => CustomerResource::getUrl('index', ['tableFilters' => ['status' => ['value' => 'active']]]))
                    ->icon('heroicon-o-user-group')
                    ->group('Customers')
                    ->badge($counts['active'] ?? 0),
                NavigationItem::make('Inactive Customers')
                    ->url(fn (): string => CustomerResource::getUrl('index', ['tableFilters' => ['status' => ['value' => 'inactive']]]))
                    ->icon('heroicon-o-user-group')
                    ->group('Customers')
                    ->badge($counts['inactive'] ?? 0),
                NavigationItem::make('Pending Customers')
                    ->url(fn (): string => CustomerResource::getUrl('index', ['tableFilters' => ['status' => ['value' => 'pending']]]))
                    ->icon('heroicon-o-clock')
                    ->group('Customers')
                    ->badge($counts['pending'] ?? 0),

                NavigationItem::make('Noppals Blog')
                     ->url('https:/noppal.de', shouldOpenInNewTab: true)
                     ->icon('heroicon-o-shopping-cart')
                     ->group('External')
                     ->sort(3)
                     ->hidden(fn(): bool => auth()->user()->can('view'))
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->label('Settings')
                    ->url('')
                    ->icon('heroicon-o-cog-6-tooth')

            ])
            ->font('Poppins')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
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
            ]);
    }
}
