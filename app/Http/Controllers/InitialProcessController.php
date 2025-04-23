<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\ImportedData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InitialProcessController extends Controller
{
    public function index()
    {
        $columns = [
            'rw', 'kriteria', 'usia', 'nama',
            'tanggungan_kepala_keluarga', 'lansia', 'anak_wajib_sekolah',
            'penghasilan_kepala_keluarga', 'status_bpjs', 'tipe_kendaraan',
            'sumber_air', 'tipe_jamban', 'status_kepemilikan_bangunan',
            'bahan_lantai', 'bahan_dinding', 'kategori_luas_bangunan', 'keterangan'
        ];

        $importedData = ImportedData::select($columns)->paginate(50);
        // $importedData = ImportedData::select($columns)->get();

        // Kirim data ke view
        return view('pages.naive-bayes.initial-process', compact('importedData'));
    }
}
