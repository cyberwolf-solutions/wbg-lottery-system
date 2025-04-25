<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ProcessDatabaseBackup;

class BackupDatabaseCommand extends Command
{
    protected $signature = 'db:backup';
    protected $description = 'Run automated database backup';

    public function handle()
    {
        ProcessDatabaseBackup::dispatch();
        $this->info('Database backup job dispatched successfully!');
    }
}