<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class ComponentsServiceProviders extends ServiceProvider
{

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Blade::component('admin.components.partials.link', 'link');
        Blade::component('admin.components.libraries.statisticCard', 'statistic-card');
        Blade::component('admin.components.partials.empty-states', 'empty-states');

        Blade::component('admin.components.partials.heading-section', 'heading-section');
        Blade::component('admin.components.partials.export-pdf', 'export-pdf');

        Blade::component('admin.components.partials.invoice-form', 'invoice-form');
        Blade::component('admin.components.partials.inventory-form', 'inventory-form');
        Blade::component('admin.components.partials.history-feature', 'history-feature');
        Blade::component('admin.components.sidebar', 'sidebar');
        Blade::component('admin.components.header', 'header');

        Blade::component('admin.components.tabs', 'tabs');
        Blade::component('admin.components.tab-links', 'tab-links');

        Blade::component('user.components.sidebar-user', 'sidebar-user');
        Blade::component('user.components.header-user', 'header-user');

        Blade::component('admin.components.search', 'search');
        Blade::component('admin.components.select-option', 'select-option');
        Blade::component('admin.components.popup', 'popup');
        Blade::component('admin.components.alert', 'alert');
    }
}
