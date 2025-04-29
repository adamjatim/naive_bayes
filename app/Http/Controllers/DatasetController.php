<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Models\ImportedData;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DatasetController extends Controller
{
    // Mapping kolom Excel ke kolom database
    const COLUMN_MAPPING = [
        'RW' => 'rw',
        'R W' => 'rw',
        'KRITERIA' => 'kriteria',
        'Kriteria' => 'kriteria',
        'USIA' => 'usia',
        'Usia' => 'usia',
        'NAMA' => 'nama',
        'Nama' => 'nama',
        'JUMLAH TANGGUNGAN KEPALA KELUARGA' => 'tanggungan_kepala_keluarga',
        'Jumlah Tanggungan Kepala Keluarga' => 'tanggungan_kepala_keluarga',
        'Tanggungan' => 'tanggungan_kepala_keluarga',
        'LANSIA' => 'lansia',
        'Lansia' => 'lansia',
        'ADA LANSIA' => 'lansia',
        'ANAK WAJIB SEKOLAH' => 'anak_wajib_sekolah',
        'Anak Wajib Sekolah' => 'anak_wajib_sekolah',
        'Anak Sekolah' => 'anak_wajib_sekolah',
        'PENGHASILAN KEPALA KELUARGA' => 'penghasilan_kepala_keluarga',
        'Penghasilan Kepala Keluarga' => 'penghasilan_kepala_keluarga',
        'Penghasilan' => 'penghasilan_kepala_keluarga',
        'STATUS BPJS ANGGOTA KELUARGA' => 'status_bpjs',
        'Status BPJS Anggota Keluarga' => 'status_bpjs',
        'BPJS' => 'status_bpjs',
        'TIPE KENDARAAN' => 'tipe_kendaraan',
        'Tipe Kendaraan' => 'tipe_kendaraan',
        'Kendaraan' => 'tipe_kendaraan',
        'SUMBER AIR BERSIH' => 'sumber_air',
        'Sumber Air Bersih' => 'sumber_air',
        'Air Bersih' => 'sumber_air',
        'TIPE JAMBAN' => 'tipe_jamban',
        'Tipe Jamban' => 'tipe_jamban',
        'Jamban' => 'tipe_jamban',
        'STATUS KEPEMILIKAN BANGUNAN' => 'status_kepemilikan_bangunan',
        'Status Kepemilikan Bangunan' => 'status_kepemilikan_bangunan',
        'Kepemilikan Bangunan' => 'status_kepemilikan_bangunan',
        'BAHAN DASAR LANTAI' => 'bahan_lantai',
        'Bahan Dasar Lantai' => 'bahan_lantai',
        'Lantai' => 'bahan_lantai',
        'BAHAN DASAR DINDING' => 'bahan_dinding',
        'Bahan Dasar Dinding' => 'bahan_dinding',
        'Dinding' => 'bahan_dinding',
        'KATEGORI LUAS BANGUNAN' => 'kategori_luas_bangunan',
        'Kategori Luas Bangunan' => 'kategori_luas_bangunan',
        'Luas Bangunan' => 'kategori_luas_bangunan',
        'KETERANGAN' => 'keterangan',
        'Keterangan' => 'keterangan',
        'Status' => 'keterangan'
    ];

    public function index()
    {
        $columns = [
            'rw',
            'kriteria',
            'usia',
            'nama',
            'tanggungan_kepala_keluarga',
            'lansia',
            'anak_wajib_sekolah',
            'penghasilan_kepala_keluarga',
            'status_bpjs',
            'tipe_kendaraan',
            'sumber_air',
            'tipe_jamban',
            'status_kepemilikan_bangunan',
            'bahan_lantai',
            'bahan_dinding',
            'kategori_luas_bangunan',
            'keterangan'
        ];

        $importedData = ImportedData::select($columns)->paginate(50);

        // Ambil file aktif (file yang masih punya data)
        $activeFiles = ImportedData::select('file_name', 'file_size')
            ->distinct()
            ->whereNotNull('file_name')
            ->where('user_id', Auth::user()->id)
            ->get();

        return view('pages.naive-bayes.dataset', compact('importedData', 'activeFiles'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'files.*' => 'required|mimes:xlsx,xls'
        ]);

        try {
            $queue = collect(); // ➔ tempat antrian sementara
            $importedCount = 0;

            foreach ($request->file('files') as $file) {
                $fileName = $file->getClientOriginalName();
                $fileSize = $file->getSize();

                $data = Excel::toArray([], $file)[0];
                $headers = array_shift($data);

                $normalizedHeaders = array_map(fn($h) => preg_replace('/\s+/', ' ', trim(strtoupper($h))), $headers);

                $required = ['RW', 'USIA', 'KETERANGAN'];
                $missing = array_diff($required, $normalizedHeaders);

                if (!empty($missing)) {
                    throw new \Exception("File {$fileName} tidak memiliki kolom wajib: " . implode(', ', $missing));
                }

                // Cari index RW
                $rwIndex = array_search('RW', $normalizedHeaders);
                if ($rwIndex === false) {
                    throw new \Exception("File {$fileName} tidak memiliki kolom RW.");
                }

                foreach ($data as $row) {
                    $mapped = [];

                    foreach ($normalizedHeaders as $i => $header) {
                        if (isset(self::COLUMN_MAPPING[$header])) {
                            $dbCol = self::COLUMN_MAPPING[$header];
                            $val = $row[$i] ?? null;
                            $mapped[$dbCol] = $this->transformValue($dbCol, $val);
                        }
                    }

                    if (empty($mapped['rw']) || empty($mapped['usia']) || empty($mapped['keterangan'])) {
                        continue;
                    }

                    $mapped['kriteria'] = $this->determineKriteria($mapped['usia']);
                    $mapped['user_id'] = Auth::id();
                    $mapped['file_name'] = $fileName;
                    $mapped['file_size'] = $fileSize;

                    $queue->push($mapped); // ➔ masukkan ke queue
                }
            }

            // 🔥 Sort queue berdasarkan RW numerik
            $sortedQueue = $queue->sortBy(function ($item) {
                return (int) preg_replace('/[^0-9]/', '', $item['rw']);
            })->values(); // reset index setelah sort

            // 🔥 Setelah dipastikan semua rapi, bulk insert sekaligus
            ImportedData::insert($sortedQueue->toArray());
            $importedCount = $sortedQueue->count();

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'import',
                'file_name' => $fileName
            ]);

            return redirect()
                ->route('naive-bayes.dataset.index')
                ->with('success', "Berhasil mengimpor {$importedCount} data. Semua RW sudah terurut sempurna!");
        } catch (\Exception $e) {
            Log::error('Import error: ' . $e->getMessage());

            return redirect()
                ->route('naive-bayes.dataset.index')
                ->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }


    protected function transformValue($column, $value)
    {
        $value = trim($value);
        if ($value === null || $value === '') return null;

        switch ($column) {
            case 'lansia':
                return (str_contains(strtolower($value), 'tidak') ? 'tidak_ada' : 'ada');
            case 'anak_wajib_sekolah':
                return (str_contains(strtolower($value), 'tidak') ? 'tidak_ada' : 'ada');
            case 'keterangan':
                return (str_contains(strtolower($value), 'bukan') ? 'bukan_penerima' : 'penerima');
            case 'usia':
                return is_numeric($value) ? (int)$value : null;
            default:
                return $value;
        }
    }

    protected function determineKriteria($usia)
    {
        if ($usia > 55) return 'lansia';
        if ($usia <= 18) return 'anak_sekolah';
        return 'usia_produktif';
    }

    public function deleteAll(Request $request)
    {
        ImportedData::truncate(); // menghapus semua data dari tabel

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'delete semua data'
        ]);

        return redirect()->route('naive-bayes.dataset.index')
            ->with('success', 'Seluruh data dataset berhasil dihapus.');
    }
}
