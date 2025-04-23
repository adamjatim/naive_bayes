<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
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


    // PredictionController.php
    public function exportPdf(Request $request)
    {
        $request->validate([
            'file_name' => 'required|string'
        ]);

        $fileName = $request->input('file_name');

        // Ambil data berdasarkan nama file
        $data = ImportedData::where('file_name', $fileName)
            ->orderBy('id')
            ->get();

        // Hitung statistik
        $summary = [
            'file_name' => $fileName,
            'total_data' => $data->count(),
            'jumlah_penerima' => $data->where('keterangan', 'penerima')->count(),
            'jumlah_bukan' => $data->where('keterangan', 'bukan_penerima')->count()
        ];

        // Generate PDF
        $pdf = Pdf::loadView('exports.report', [
            'data' => $data,
            'summary' => $summary
        ])->setPaper('a4', 'landscape');

        // Nama file download
        $downloadFileName = 'laporan_' . str_replace([' ', '.xlsx', '.xls'], '_', $fileName) . '.pdf';

        return $pdf->download($downloadFileName);
    }
}
