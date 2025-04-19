<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Models\ImportedData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PredictionController extends Controller
{
    public function index(Request $request)
    {
        $formInput = $request->all();

        $data = ImportedData::all();
        $labels = $data->pluck('keterangan')->unique();

        // Hitung total label
        $labelCount = $data->groupBy('keterangan')->map->count();
        $total = $data->count();

        $stats = [];
        $probabilities = [];

        if ($request->isMethod('post')) {
            // Fitur yang dimasukkan (kecuali CSRF, _token, dan kosong)
            $features = collect($formInput)->filter(
                fn($v, $k) =>
                $v !== null && !in_array($k, ['_token', 'keterangan'])
            );

            // Hitung probabilitas kondisional
            foreach ($features as $field => $value) {
                $grouped = $data->where($field, $value)->groupBy('keterangan')->map->count();

                foreach ($labels as $label) {
                    $stats[$field][$label] = $grouped[$label] ?? 0;
                }
            }

            // Hitung probabilitas total untuk masing-masing label
            foreach ($labels as $label) {
                $score = $labelCount[$label] / $total;

                foreach ($features as $field => $value) {
                    $count = $stats[$field][$label] ?? 0;
                    $score *= ($count + 1) / ($labelCount[$label] + count($features)); // Laplace smoothing
                }

                $probabilities[$label] = $score;
            }

            // Normalisasi skor prediksi
            $totalScore = array_sum($probabilities);
            foreach ($probabilities as $label => $score) {
                $probabilities[$label] = round($score / $totalScore * 100, 2); // dalam persen
            }

            arsort($probabilities);
            $predictedLabel = array_key_first($probabilities);
        }

        // Ambil semua nilai unik per kolom sebagai suggestion
        $suggestions = [];

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
            'kategori_luas_bangunan'
        ];

        foreach ($columns as $col) {
            $suggestions[$col] = ImportedData::select($col)
                ->distinct()
                ->orderBy($col)
                ->pluck($col)
                ->filter()
                ->values()
                ->toArray();
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'prediction',
            'created_at' => now(),
        ]);

        return view('pages.naive-bayes.prediction', compact('formInput', 'stats', 'labelCount', 'total', 'probabilities', 'suggestions'));
    }
}
