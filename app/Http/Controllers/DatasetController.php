<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImportedData;
use App\Models\ImportedDataHistory;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\{
    Auth,
    Cache,
    DB,
    Log,
    Storage
};

class DatasetController extends Controller
{
    public function index()
    {
        $importedData = ImportedData::paginate(50);
        return view('pages.naive-bayes.dataset', compact('importedData'));
    }

    public function import(Request $request)
    {
        set_time_limit(300); // 5 minutes timeout

        // Validate
        $request->validate([
            'files.*' => 'required|mimes:xls,xlsx,csv|max:10240' // Max 10MB per file
        ]);

        // Concurrency lock
        if (Cache::get('import_lock')) {
            return $this->importResponse(
                false,
                'System sedang memproses import sebelumnya. Coba lagi dalam beberapa menit.'
            );
        }
        Cache::put('import_lock', true, now()->addHour());

        try {
            // 1. Backup existing data
            $backupPath = $this->createBackup();
            if (!$backupPath) {
                throw new \Exception("Gagal membuat backup data");
            }

            // 2. Process import
            $results = $this->processFiles($request->file('files'));

            // 3. Cleanup
            $this->deleteBackup($backupPath);
            Cache::forget('import_lock');

            return $this->importResponse(
                true,
                'Berhasil mengimpor ' . count($results['success']) . ' file',
                $results['success']
            );

        } catch (\Throwable $e) {
            // Restore on failure
            if (isset($backupPath)) {
                $this->restoreBackup($backupPath);
            }

            Cache::forget('import_lock');
            Log::error("Import Error: " . $e->getMessage());

            return $this->importResponse(
                false,
                'Error: ' . $e->getMessage(),
                $request->file('files')->pluck('getClientOriginalName')->toArray()
            );
        }
    }

    public function history()
    {
        $history = ImportedDataHistory::with('user')
                   ->orderBy('modified_at', 'desc')
                   ->paginate(50);

        return view('pages.naive-bayes.history', compact('history'));
    }

    public function restoreHistory($history_id)
    {
        try {
            $historyData = ImportedDataHistory::findOrFail($history_id);

            // Create current data backup
            $backupPath = $this->createBackup();

            // Restore history
            ImportedData::truncate();
            ImportedData::create([
                'user_email' => Auth::user()->email,
                'dataset_id' => $historyData->dataset_id,
                'row_data' => $historyData->row_data,
            ]);

            return redirect()
                ->route('naive-bayes.dataset.index')
                ->with('message', [
                    'type' => 'success',
                    'text' => 'Data berhasil dikembalikan dari history'
                ]);

        } catch (\Throwable $e) {
            // Restore backup if exists
            if (isset($backupPath)) {
                $this->restoreBackup($backupPath);
            }

            return redirect()
                ->back()
                ->with('message', [
                    'type' => 'error',
                    'text' => 'Gagal restore: ' . $e->getMessage()
                ]);
        }
    }

    // =============================================
    // PRIVATE HELPER METHODS
    // =============================================

    private function createBackup()
    {
        try {
            $filename = 'backup_' . now()->format('Ymd_His') . '.json';
            $path = "import_backups/{$filename}";

            Storage::put($path, json_encode(
                ImportedData::all()->map->toArray()
            ));

            return $path;
        } catch (\Throwable $e) {
            Log::error("Backup failed: " . $e->getMessage());
            return false;
        }
    }

    private function restoreBackup($path)
    {
        try {
            if (Storage::exists($path)) {
                $data = json_decode(Storage::get($path), true);

                ImportedData::truncate();
                foreach (array_chunk($data, 100) as $chunk) {
                    ImportedData::insert($chunk);
                }

                Storage::delete($path);
                return true;
            }
            return false;
        } catch (\Throwable $e) {
            Log::error("Restore failed: " . $e->getMessage());
            return false;
        }
    }

    private function deleteBackup($path)
    {
        try {
            if ($path && Storage::exists($path)) {
                Storage::delete($path);
            }
        } catch (\Throwable $e) {
            Log::error("Backup deletion failed: " . $e->getMessage());
        }
    }

    private function processFiles($files)
    {
        $results = ['success' => [], 'failed' => []];
        $userEmail = Auth::user()->email;

        // Clear existing data
        ImportedData::truncate();

        foreach ($files as $file) {
            try {
                $filename = $file->getClientOriginalName();
                $data = Excel::toArray([], $file)[0];
                $headers = array_shift($data);

                // Process in chunks
                foreach (array_chunk($data, 100) as $chunk) {
                    $batch = array_map(function ($row) use ($headers, $userEmail) {
                        return [
                            'user_email' => $userEmail,
                            'dataset_id' => 1,
                            'row_data' => json_encode(array_combine($headers, $row)),
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }, $chunk);

                    ImportedData::insert($batch);
                }

                $results['success'][] = $filename;

                // Log to history
                ImportedDataHistory::create([
                    'user_email' => $userEmail,
                    'dataset_id' => 1,
                    'row_data' => json_encode(['action' => 'import', 'files' => $filename]),
                    'modified_at' => now()
                ]);

            } catch (\Throwable $e) {
                $results['failed'][] = [
                    'file' => $file->getClientOriginalName(),
                    'error' => $e->getMessage()
                ];
                Log::error("File {$filename} failed: " . $e->getMessage());
            }
        }

        return $results;
    }

    private function importResponse($success, $message, $files = [])
    {
        return redirect()
            ->route('naive-bayes.dataset.index')
            ->with('message', [
                'type' => $success ? 'success' : 'error',
                'text' => $message
            ])
            ->with('uploaded_files', $files);
    }
}