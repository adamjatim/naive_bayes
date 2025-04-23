<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\ImportedData;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // History aktivitas admin
        $logs = ActivityLog::with('user')
            ->orderByDesc('created_at')
            ->get();

        // Statistik penerima berdasarkan kriteria (kategori)
        $kriteriaStats = ImportedData::where('keterangan', 'penerima')
            ->select('kriteria', DB::raw('count(*) as total'))
            ->groupBy('kriteria')
            ->pluck('total', 'kriteria');

        // Statistik penerima berdasarkan wilayah (rw)
        $rwStats = ImportedData::where('keterangan', 'penerima')
            ->select('rw', DB::raw('count(*) as total'))
            ->groupBy('rw')
            ->pluck('total', 'rw');

        $jumlahPenerima = ImportedData::where('keterangan', 'penerima')->count();
        $jumlahBukan = ImportedData::where('keterangan', 'bukan_penerima')->count();

        $fileStats = ImportedData::select('file_name', DB::raw('count(*) as total'))
            ->whereNotNull('file_name')
            ->groupBy('file_name')
            ->pluck('total', 'file_name');

        // Untuk filter export
        $availableFiles = ImportedData::select('file_name')
            ->whereNotNull('file_name')
            ->distinct()
            ->pluck('file_name');

        return view('pages.dashboard', compact('logs', 'kriteriaStats', 'rwStats', 'availableFiles', 'jumlahPenerima', 'jumlahBukan', 'fileStats'));
    }

    public function exportPdf(Request $request)
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 120);

        $percentage = (int) $request->get('percentage');
        $data = ImportedData::orderBy('id')->get();

        $total = $data->count();
        $trainingCount = (int) round($total * ($percentage / 100));
        $dataTraining = $data->slice(0, $trainingCount);
        $dataTesting = $data->slice($trainingCount);

        $grouped = $dataTraining->groupBy('keterangan');
        $probabilities = [];
        foreach ($grouped as $label => $items) {
            $probabilities[$label] = $items->count() / $dataTraining->count();
        }

        $predictions = [];
        foreach ($dataTesting as $item) {
            // Misal: pakai tur 'kriteria' sebagai indikator utama
            $prediksi = $item->kriteria === 'usia_produktif' ? 'penerima' : 'bukan_penerima';
            $predictions[] = [
                'data' => $item,
                'actual' => $item->keterangan,
                'predicted' => $prediksi,
                'correct' => $prediksi === $item->keterangan, // ✅ tambahkan ini!
            ];
        }

        $correct = collect($predictions)->filter(function ($p) {
            return $p['actual'] === $p['predicted'];
            })->count();
        $accuracy = $correct / count($predictions) * 100;

        // dd($data);
        // return view('exports.report', compact('testData', 'summary'));

        $pdf = Pdf::loadView('exports.report', [
            'predictions' => $predictions,   // hasil proses testing
            'summary' => [
                'persentase' => $percentage,
                'jumlah_penerima' => collect($predictions)->where('actual', 'penerima')->count(),
                'jumlah_bukan' => collect($predictions)->where('actual', 'bukan_penerima')->count(),
                'total_testing' => count($predictions),
                'akurasi' => round($accuracy, 2). '%',
            ]
        ])->setPaper('a3', 'landscape')
        ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => false]); // ← set ukuran & orientasi;

        return $pdf->stream("report-testing-{$percentage}persen.pdf");
    }
}
