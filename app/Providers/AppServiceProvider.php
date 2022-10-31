<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\Column;
use Illuminate\Support\ServiceProvider;

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
        Column::configureUsing(function (Column $column): void {
            $column
                ->toggleable()
                ->sortable();
        });

        BooleanColumn::configureUsing(function (Column $column): void {
            $column->extraAttributes(['class' => 'flex justify-center']);
        });

        Filament::serving(function () {
            Filament::registerNavigationGroups([
                NavigationGroup::make()
                    ->label('Reservation')
                    ->icon('heroicon-s-ticket'),
                NavigationGroup::make()
                    ->label('User')
                    ->icon('heroicon-s-user-circle'),
                NavigationGroup::make()
                    ->label('Others')
                    ->icon('heroicon-s-cog'),
            ]);
        });

        if(config('app.env') === 'production') {
            \URL::forceScheme('https');
        }
    }
}
