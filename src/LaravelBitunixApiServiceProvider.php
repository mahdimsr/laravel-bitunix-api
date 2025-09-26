<?php

namespace Msr\LaravelBitunixApi;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Msr\LaravelBitunixApi\Commands\LaravelBitunixApiCommand;

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
}
