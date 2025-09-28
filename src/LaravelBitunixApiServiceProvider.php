<?php

namespace Msr\LaravelBitunixApi;

use Msr\LaravelBitunixApi\Commands\LaravelBitunixApiCommand;
use Msr\LaravelBitunixApi\Requests\ChangeLeverageRequestContract;
use Msr\LaravelBitunixApi\Requests\ChangeMarginModeRequestContract;
use Msr\LaravelBitunixApi\Requests\FutureKLineRequestContract;
use Msr\LaravelBitunixApi\Requests\PlaceOrderRequestContract;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelBitunixApiServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-bitunix-api')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel_bitunix_api_table')
            ->hasCommand(LaravelBitunixApiCommand::class);
    }

    public function packageRegistered(): void
    {
        parent::packageRegistered();

        $this->app->bind(FutureKLineRequestContract::class, LaravelBitunixApi::class);
        $this->app->bind(ChangeLeverageRequestContract::class, LaravelBitunixApi::class);
        $this->app->bind(ChangeMarginModeRequestContract::class, LaravelBitunixApi::class);
        $this->app->bind(PlaceOrderRequestContract::class, LaravelBitunixApi::class);
    }
}
