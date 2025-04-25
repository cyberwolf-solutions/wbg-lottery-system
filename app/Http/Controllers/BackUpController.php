<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Ifsnop\Mysqldump\Mysqldump;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackUpController extends Controller
{
    //
    public function index()
    {

        return Inertia::render('AdminDashboard/bacup');
    }
    




    public function backup()
    {
        try {
            $db = config('database.connections.mysql.database');
            $user = config('database.connections.mysql.username');
            $pass = config('database.connections.mysql.password');
            $host = config('database.connections.mysql.host');

            $timestamp = now()->format('Y-m-d_H-i-s');
            $fileName = "manual_backup_{$timestamp}.sql";
            $filePath = storage_path("app/backups/{$fileName}");

            if (!file_exists(storage_path('app/backups'))) {
                mkdir(storage_path('app/backups'), 0755, true);
            }

            $dumpSettings = [
                'compress' => 'None',
                'no-data' => false,
                'add-drop-table' => true,
                'single-transaction' => true,
                'lock-tables' => false,
            ];

            $dump = new Mysqldump(
                "mysql:host={$host};dbname={$db}",
                $user,
                $pass,
                $dumpSettings
            );

            $dump->start($filePath);

            return response()->download($filePath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Backup failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
