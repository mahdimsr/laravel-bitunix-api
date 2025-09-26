<?php

namespace Msr\LaravelBitunixApi\Commands;

use Illuminate\Console\Command;

class LaravelBitunixApiCommand extends Command
{
    public $signature = 'laravel-bitunix-api';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
