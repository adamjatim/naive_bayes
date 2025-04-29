<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\ImportedData;
use App\Exports\PredictionExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DashboardController extends Controller
{
    // public function index(Request $request)
    // {
    //     $logs = ActivityLog::with('user')
    //         ->orderByDesc('created_at')
    //         ->get();

    //     $kriteriaStats = ImportedData::where('keterangan', 'penerima')
    //         ->select('kriteria', DB::raw('count(*) as total'))
    //         ->groupBy('kriteria')
    //         ->pluck('total', 'kriteria');

    //     $rwStats = ImportedData::where('keterangan', 'penerima')
    //         ->select('rw', DB::raw('count(*) as total'))
    //         ->groupBy('rw')
    //         ->pluck('total', 'rw');

    //     $jumlahPenerima = ImportedData::where('keterangan', 'penerima')->count();
    //     $jumlahBukan = ImportedData::where('keterangan', 'bukan_penerima')->count();

    //     $fileStats = ImportedData::select('file_name', DB::raw('count(*) as total'))
    //         ->whereNotNull('file_name')
    //         ->groupBy('file_name')
    //         ->pluck('total', 'file_name');

    //     $availableFiles = ImportedData::select('file_name')
    //         ->whereNotNull('file_name')
    //         ->distinct()
    //         ->pluck('file_name');

    //     return view('pages.dashboard', compact('logs', 'kriteriaStats', 'rwStats', 'availableFiles', 'jumlahPenerima', 'jumlahBukan', 'fileStats'));
    // }

    public function index(Request $request)
    {
        $logs = ActivityLog::with('user')
            ->orderByDesc('created_at')
            ->get();

        $kriteriaStats = ImportedData::where('keterangan', 'penerima')
            ->select('kriteria', DB::raw('count(*) as total'))
            ->groupBy('kriteria')
            ->pluck('total', 'kriteria');

        $rwStats = ImportedData::where('keterangan', 'penerima')
            ->select('rw', DB::raw('count(*) as total'))
            ->groupBy('rw')
            ->pluck('total', 'rw');

        // 🔥 Tambahan: Bukan Penerima
        $kriteriaStatsBukan = ImportedData::where('keterangan', 'bukan_penerima')
            ->select('kriteria', DB::raw('count(*) as total'))
            ->groupBy('kriteria')
            ->pluck('total', 'kriteria');

        $rwStatsBukan = ImportedData::where('keterangan', 'bukan_penerima')
            ->select('rw', DB::raw('count(*) as total'))
            ->groupBy('rw')
            ->pluck('total', 'rw');

        $jumlahPenerima = ImportedData::where('keterangan', 'penerima')->count();
        $jumlahBukan = ImportedData::where('keterangan', 'bukan_penerima')->count();

        $fileStats = ImportedData::select('file_name', DB::raw('count(*) as total'))
            ->whereNotNull('file_name')
            ->groupBy('file_name')
            ->pluck('total', 'file_name');

        $availableFiles = ImportedData::select('file_name')
            ->whereNotNull('file_name')
            ->distinct()
            ->pluck('file_name');

        return view('pages.dashboard', compact(
            'logs',
            'kriteriaStats',
            'rwStats',
            'kriteriaStatsBukan',
            'rwStatsBukan',
            'availableFiles',
            'jumlahPenerima',
            'jumlahBukan',
            'fileStats'
        ));
    }


    public function exportPdf(Request $request)
    {
        // Validasi awal (jika request kosong)
        $request->validate([
            'percentage' => 'required|integer|min:1|max:100',
        ], [
            'percentage.required' => 'Silakan pilih persentase data terlebih dahulu sebelum mengekspor.'
        ]);

        $percentage = (int) $request->get('percentage');

        // Ambil data dari file yang dipilih
        $data = ImportedData::orderBy('id')->get();

        if ($data->isEmpty()) {
            return redirect()->back()->with('error', 'Data untuk file tersebut tidak ditemukan.');
        }

        $total = $data->count();
        $trainingCount = (int) round($total * ($percentage / 100));
        $dataTraining = $data->slice(0, $trainingCount);
        $dataTesting = $data->slice($trainingCount);

        // Lakukan prediksi (sederhana)
        $predictions = [];
        foreach ($dataTesting as $item) {
            $prediksi = $item->kriteria === 'usia_produktif' ? 'penerima' : 'bukan_penerima';
            $predictions[] = [
                'data' => $item,
                'actual' => $item->keterangan,
                'predicted' => $prediksi,
                'correct' => $prediksi === $item->keterangan,
            ];
        }

        $summary = [
            'persentase' => $percentage,
            'jumlah_penerima' => collect($predictions)->where('actual', 'penerima')->count(),
            'jumlah_bukan' => collect($predictions)->where('actual', 'bukan_penerima')->count(),
            'total_testing' => count($predictions),
            'akurasi' => count($predictions) > 0
                ? round((collect($predictions)->where('correct', true)->count() / count($predictions)) * 100, 2)
                : 0,
        ];

        return Excel::download(new \App\Exports\PredictionExport($predictions, $summary), "prediction-{$percentage}persen.xlsx");
    }
}
