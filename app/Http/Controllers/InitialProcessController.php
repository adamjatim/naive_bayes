<?php

namespace App\Http\Controllers;

use App\Models\ImportedData;
use Illuminate\Http\Request;

class InitialProcessController extends Controller
{
    public function index()
    {
        // Ambil data dengan pagination (misalnya, 50 data per halaman)
        $importedData = ImportedData::paginate(50);

        // Kirim data ke view
        return view('pages.naive-bayes.initial-process', compact('importedData'));
    }
}
