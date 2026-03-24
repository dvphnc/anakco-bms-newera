<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BackupController extends Controller
{
    private string $backupDisk = 'local';
    private string $backupPath = 'backups';

    // -------------------------------------------------------
    // INDEX — list all backups
    // -------------------------------------------------------
    public function index()
    {
        $files = collect(Storage::disk($this->backupDisk)->files($this->backupPath))
            ->map(function ($file) {
                return [
                    'name'     => basename($file),
                    'path'     => $file,
                    'size'     => $this->formatSize(Storage::disk($this->backupDisk)->size($file)),
                    'created'  => \Carbon\Carbon::createFromTimestamp(
                        Storage::disk($this->backupDisk)->lastModified($file)
                    )->format('F d, Y h:i A'),
                    'timestamp'=> Storage::disk($this->backupDisk)->lastModified($file),
                ];
            })
            ->sortByDesc('timestamp')
            ->values();

        return view('backup.index', compact('files'));
    }

    // -------------------------------------------------------
    // CREATE — run mysqldump
    // -------------------------------------------------------
    public function create()
    {
        try {
            $db       = config('database.connections.mysql.database');
            $host     = config('database.connections.mysql.host');
            $port     = config('database.connections.mysql.port');
            $user     = config('database.connections.mysql.username');
            $pass     = config('database.connections.mysql.password');

            $filename = 'backup_' . now()->format('Y-m-d_H-i-s') . '.sql';
            $fullPath = storage_path('app/' . $this->backupPath . '/' . $filename);

            // Ensure backup directory exists
            if (!file_exists(storage_path('app/' . $this->backupPath))) {
                mkdir(storage_path('app/' . $this->backupPath), 0755, true);
            }

            // Build mysqldump command
            $command = sprintf(
                'mysqldump --host=%s --port=%s --user=%s --password=%s --single-transaction --routines --triggers %s > %s 2>&1',
                escapeshellarg($host),
                escapeshellarg($port),
                escapeshellarg($user),
                escapeshellarg($pass),
                escapeshellarg($db),
                escapeshellarg($fullPath)
            );

            exec($command, $output, $returnCode);

            if ($returnCode !== 0 || !file_exists($fullPath) || filesize($fullPath) < 100) {
                return back()->with('error', 'Backup failed. Check mysqldump is in your PATH.');
            }

            // Keep only last 10 backups
            $this->pruneOldBackups();

            return back()->with('success', "Backup created: {$filename}");

        } catch (\Exception $e) {
            return back()->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    // -------------------------------------------------------
    // DOWNLOAD
    // -------------------------------------------------------
    public function download(string $filename)
    {
        $path = $this->backupPath . '/' . $filename;

        if (!Storage::disk($this->backupDisk)->exists($path)) {
            abort(404, 'Backup file not found.');
        }

        return Storage::disk($this->backupDisk)->download($path, $filename);
    }

    // -------------------------------------------------------
    // DELETE
    // -------------------------------------------------------
    public function delete(string $filename)
    {
        $path = $this->backupPath . '/' . $filename;

        if (Storage::disk($this->backupDisk)->exists($path)) {
            Storage::disk($this->backupDisk)->delete($path);
            return back()->with('success', "Backup deleted: {$filename}");
        }

        return back()->with('error', 'File not found.');
    }

    // -------------------------------------------------------
    // RESTORE
    // -------------------------------------------------------
    public function restore(Request $request)
    {
        $request->validate([
            'filename' => 'required|string',
        ]);

        $filename = $request->input('filename');
        $path     = storage_path('app/' . $this->backupPath . '/' . $filename);

        if (!file_exists($path)) {
            return back()->with('error', 'Backup file not found.');
        }

        try {
            $db   = config('database.connections.mysql.database');
            $host = config('database.connections.mysql.host');
            $port = config('database.connections.mysql.port');
            $user = config('database.connections.mysql.username');
            $pass = config('database.connections.mysql.password');

            $command = sprintf(
                'mysql --host=%s --port=%s --user=%s --password=%s %s < %s 2>&1',
                escapeshellarg($host),
                escapeshellarg($port),
                escapeshellarg($user),
                escapeshellarg($pass),
                escapeshellarg($db),
                escapeshellarg($path)
            );

            exec($command, $output, $returnCode);

            if ($returnCode !== 0) {
                return back()->with('error', 'Restore failed: ' . implode(' ', $output));
            }

            return back()->with('success', "Database restored from: {$filename}");

        } catch (\Exception $e) {
            return back()->with('error', 'Restore failed: ' . $e->getMessage());
        }
    }

    // -------------------------------------------------------
    // UPLOAD & RESTORE from uploaded file
    // -------------------------------------------------------
    public function upload(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:sql,txt|max:51200',
        ]);

        $file     = $request->file('backup_file');
        $filename = 'uploaded_' . now()->format('Y-m-d_H-i-s') . '.sql';
        $file->storeAs($this->backupPath, $filename, $this->backupDisk);

        return redirect()->route('backup.index')->with('success', "File uploaded: {$filename} — click Restore to apply.");
    }

    // -------------------------------------------------------
    // HELPERS
    // -------------------------------------------------------
    private function formatSize(int $bytes): string
    {
        if ($bytes >= 1048576) return round($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024)    return round($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }

    private function pruneOldBackups(): void
    {
        $files = Storage::disk($this->backupDisk)->files($this->backupPath);
        if (count($files) > 10) {
            $sorted = collect($files)->sortBy(fn($f) =>
                Storage::disk($this->backupDisk)->lastModified($f)
            );
            foreach ($sorted->take(count($files) - 10) as $old) {
                Storage::disk($this->backupDisk)->delete($old);
            }
        }
    }
}