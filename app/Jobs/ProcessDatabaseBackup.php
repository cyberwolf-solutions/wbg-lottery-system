<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;

use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;
use Ifsnop\Mysqldump\Mysqldump;

class ProcessDatabaseBackup implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        try {
            $db = config('database.connections.mysql.database');
            $user = config('database.connections.mysql.username');
            $pass = config('database.connections.mysql.password');
            $host = config('database.connections.mysql.host');
    
            $timestamp = now()->format('Y-m-d_H-i-s');
            $fileName = "auto_backup_{$timestamp}.sql";
            $directory = 'backups';
            $filePath = storage_path("app/{$directory}/{$fileName}");
    
            if (!file_exists(storage_path("app/{$directory}"))) {
                mkdir(storage_path("app/{$directory}"), 0755, true);
            }
    
            $dumpSettings = [
                'compress' => 'None',
                'no-data' => false,
                'add-drop-table' => true,
                'single-transaction' => true,
                'lock-tables' => false,
            ];
    
            Log::info("Attempting to create backup at: {$filePath}");
    
            $dump = new Mysqldump(
                "mysql:host={$host};dbname={$db}",
                $user,
                $pass,
                $dumpSettings
            );
    
            $dump->start($filePath);
    
            if (!file_exists($filePath)) {
                throw new \Exception("Backup file was not created at {$filePath}");
            }
    
            $fileSize = filesize($filePath);
            if ($fileSize === 0) {
                unlink($filePath);
                throw new \Exception("Backup file was created but is empty (0 bytes)");
            }
    
            Log::info("Database backup created successfully: {$fileName} (Size: " . round($fileSize/1024/1024, 2) . " MB)");
    
            $this->cleanupOldBackups();
        } catch (\Exception $e) {
            Log::error('Automatic backup failed: ' . $e->getMessage());
            throw $e;
        }
    }
    protected function cleanupOldBackups()
    {
        $directory = storage_path('app/backups');
        $cutoff = now()->subDays(7)->getTimestamp();

        if (!file_exists($directory)) {
            return;
        }

        $files = glob("{$directory}/auto_backup_*.sql*");
        foreach ($files as $file) {
            if (filemtime($file) < $cutoff) {
                unlink($file);
                Log::info("Deleted old backup: " . basename($file));
            }
        }
    }
}