<?php

namespace PlinCode\LaravelEmailFixer\Commands;

use Illuminate\Console\Command;

class LaravelEmailFixerCommand extends Command
{
    public $signature = 'laravel-email-fixer';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
