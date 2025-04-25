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
use App\Filament\Resources\UserResource;
use BezhanSalleh\FilamentShield\Resources\RoleResource;
use App\Filament\Resources\FilTableFieldsResource;
use App\Models\Customer;
use App\Filament\Pages\CustomNotificationsPage;



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
                'primary' => '#14b8a6',
            ])
            ->resources([
                UserResource::class,
                RoleResource::class,
                FilTableFieldsResource::class,
            ])
            //->sidebarFullyCollapsibleOnDesktop()
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
                CustomNotificationsPage::class,
            ])

            ->navigationItems([
                NavigationItem::make('All Customers')
                    ->url(fn (): string => CustomerResource::getUrl('index'))
                    ->icon('heroicon-o-users')
                    ->group('Customers')
                    ->badge(array_sum($counts))
                    ->isActiveWhen(function () {
                        $currentUrl = url()->full();
                        $targetUrl = CustomerResource::getUrl('index');

                        // Nur aktiv, wenn exakt gleiche URL ohne Filter
                        return $currentUrl === $targetUrl || parse_url($currentUrl, PHP_URL_QUERY) === null;
                    }),
                NavigationItem::make('Existing Accounts')
                    ->url(fn (): string => CustomerResource::getUrl('index', ['tableFilters' => ['status' => ['value' => 'exacc']]]))
                    ->icon('heroicon-o-user-plus')
                    ->group('Customers')
                    ->badge($counts['exacc'] ?? 0)
                    ->isActiveWhen(fn () => request()->fullUrlIs(CustomerResource::getUrl('index', ['tableFilters' => ['status' => ['value' => 'exacc']]]) . '*')),
                NavigationItem::make('Deals')
                    ->url(fn (): string => CustomerResource::getUrl('index', ['tableFilters' => ['status' => ['value' => 'deal']]]))
                    ->icon('heroicon-o-user-group')
                    ->group('Customers')
                    ->badge($counts['deal'] ?? 0)
                    ->isActiveWhen(fn () => request()->fullUrlIs(CustomerResource::getUrl('index', ['tableFilters' => ['status' => ['value' => 'deal']]]) . '*')),
                NavigationItem::make('Leads')
                    ->url(fn (): string => CustomerResource::getUrl('index', ['tableFilters' => ['status' => ['value' => 'lead']]]))
                    ->icon('heroicon-o-users')
                    ->group('Customers')
                    ->badge($counts['lead'] ?? 0)
                    ->isActiveWhen(fn () => request()->fullUrlIs(CustomerResource::getUrl('index', ['tableFilters' => ['status' => ['value' => 'lead']]]) . '*')),
                NavigationItem::make('Contacts')
                    ->url(fn (): string => CustomerResource::getUrl('index', ['tableFilters' => ['status' => ['value' => 'contact']]]))
                    ->icon('heroicon-o-user')
                    ->group('Customers')
                    ->badge($counts['contact'] ?? 0)
                    ->isActiveWhen(fn () => request()->fullUrlIs(CustomerResource::getUrl('index', ['tableFilters' => ['status' => ['value' => 'contact']]]) . '*')),

                NavigationItem::make('ASSD')
                     ->url('https:/assd.com', shouldOpenInNewTab: true)
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
            ->navigationGroups([
                'Customers',      // Erscheint zuerst
                'Angebote',
                'Shop',          // Erscheint als zweites
                'User Management', // Definiere die Navigationsgruppe
                'Settings',
            ])
            ->font('Poppins')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                //Widgets\AccountWidget::class,
                //Widgets\FilamentInfoWidget::class,
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
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make()
            ])
            ;
    }
}
