<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Models\ImportedData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PerformanceController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data dari tabel imported_data
        // $importedData = ImportedData::all();
        // return view('pages.naive-bayes.performance', compact('importedData'));

        $percentage = (int) $request->get('percentage');

        $data = ImportedData::orderBy('id')->get();

        $total = $data->count();
        $trainCount = (int) round($total * ($percentage / 100));
        $testCount = $total - $trainCount;

        $trainData = collect();
        $testData = collect();

        if ($percentage) {
            $trainData = $data->take($trainCount);
            $testData = $data->slice($trainCount);
        }

        return view('pages.naive-bayes.performance', compact('percentage', 'trainData', 'testData', 'total', 'trainCount', 'testCount'));
    }

    public function calculate(Request $request)
    {
        $percentage = (int) $request->get('percentage');

        $data = ImportedData::orderBy('id')->get();

        $total = $data->count();
        $trainCount = (int) round($total * ($percentage / 100));
        $testCount = $total - $trainCount;

        $trainData = collect();
        $testData = collect();

        if ($percentage) {
            $trainData = $data->take($trainCount);
            $testData = $data->slice($trainCount);
        }

        $predictions = [];
        $correct = 0;

        if ($percentage) {
            // 1. Hitung jumlah masing-masing label
            $labelCounts = $trainData->groupBy('keterangan')->map->count();
            $totalTrain = $trainData->count();

            $labels = $labelCounts->keys();

            // 2. Hitung probabilitas label
            $prior = [];
            foreach ($labels as $label) {
                $prior[$label] = $labelCounts[$label] / $totalTrain;
            }

            // 3. Hitung probabilitas kondisional untuk tiap atribut
            $features = array_keys($trainData->first()->getAttributes());
            $features = array_diff($features, ['id', 'user_id', 'file_name', 'file_size', 'created_at', 'updated_at', 'keterangan']);

            $condProb = [];

            foreach ($features as $feature) {
                foreach ($labels as $label) {
                    $values = $trainData->where('keterangan', $label)->groupBy($feature)->map->count();
                    $total = $labelCounts[$label];

                    foreach ($values as $val => $count) {
                        $condProb[$feature][$val][$label] = $count / $total;
                    }
                }
            }

            // 4. Prediksi data testing
            foreach ($testData as $row) {
                $scores = [];

                foreach ($labels as $label) {
                    $scores[$label] = $prior[$label]; // P(label)

                    foreach ($features as $feature) {
                        $val = $row->$feature;

                        // Gunakan Laplace smoothing jika tidak ditemukan
                        $scores[$label] *= $condProb[$feature][$val][$label] ?? (1 / ($labelCounts[$label] + 1));
                    }
                }

                // Prediksi: pilih label dengan probabilitas tertinggi
                $predicted = array_keys($scores, max($scores))[0];
                $actual = $row->keterangan;

                $predictions[] = [
                    'data' => $row,
                    'predicted' => $predicted,
                    'actual' => $actual,
                    'correct' => $predicted === $actual
                ];

                if ($predicted === $actual) $correct++;
            }

            $accuracy = round(($correct / count($predictions)) * 100, 2);
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'performance',
            'created_at' => now(),
        ]);

        return view('pages.naive-bayes.performance', compact('percentage', 'trainData', 'testData', 'total', 'trainCount', 'testCount', 'predictions', 'accuracy'));
    }
}
