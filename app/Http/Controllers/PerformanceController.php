<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImportedData;
use Illuminate\Support\Facades\DB;

class PerformanceController extends Controller
{
    public function index()
    {
        // Ambil data dari tabel imported_data
        $importedData = ImportedData::all();
        return view('pages.naive-bayes.performance', compact('importedData'));
    }

    public function calculate(Request $request)
    {
        $percentage = $request->input('percentage');
        $importedData = ImportedData::all();

        // 1. Bagi dataset
        $trainingSize = intval(($percentage / 100) * count($importedData));
        $trainingData = $importedData->slice(0, $trainingSize);
        $testingData = $importedData->slice($trainingSize);

        // 2. Latih model Naïve Bayes
        $model = $this->trainNaiveBayes($trainingData);

        // 3. Uji model
        $correctPredictions = 0;
        foreach ($testingData as $record) {
            $predictedClass = $this->predictWithNaiveBayes($model, $record);
            if ($predictedClass == $record->KETERANGAN) {
                $correctPredictions++;
            }
        }

        // 4. Hitung akurasi
        $accuracy = ($correctPredictions / count($testingData)) * 100;

        return redirect()->route('naive-bayes.performance.index')->with('accuracy', $accuracy);
    }

    private function trainNaiveBayes($trainingData)
    {
        // Implementasi training Naïve Bayes
        // Hitung probabilities prior dan probabilities kondisional
        // Return model yang sudah dilatih
    }

    private function predictWithNaiveBayes($model, $record)
    {
        // Implementasi prediksi menggunakan model Naïve Bayes
        // Return kelas prediksi (Penerima atau Bukan Penerima)
    }
}
