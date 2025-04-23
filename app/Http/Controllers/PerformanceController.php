<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImportedData;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class PerformanceController extends Controller
{
    public function index(Request $request)
    {
        $percentage = (int) $request->get('percentage');
        $data = ImportedData::orderBy('id')->get();


        $total = $data->count();
        $totalData = $data->count();
        // dd($total);
        $trainCount = (int) round($total * ($percentage / 100));
        $testCount = $total - $trainCount;

        $trainData = $data->take($trainCount);
        $initialTestData = ImportedData::orderBy('id')
            ->skip($trainCount)
            ->take(100)
            ->get();

        return view('pages.naive-bayes.performance', [
            'percentage' => $percentage,
            'trainData' => $trainData,
            'testData' => $initialTestData,
            'total' => $total,
            'trainCount' => $trainCount,
            'testCount' => $testCount,
            'predictions' => [],
            'accuracy' => null,
            'totalData' => $totalData
        ]);
    }


    public function calculate(Request $request)
    {
        $percentage = (int) $request->get('percentage');
        $data = ImportedData::orderBy('id')->get();

        $total = $data->count();
        $trainCount = (int) round($total * ($percentage / 100));
        $testCount = $total - $trainCount;

        $trainData = $data->take($trainCount);
        $testData = $data->slice($trainCount);

        // Proses Naive Bayes
        $predictions = [];
        $correct = 0;

        $labelCounts = $trainData->groupBy('keterangan')->map->count();
        $totalTrain = $trainData->count();
        $labels = $labelCounts->keys();

        $prior = [];
        foreach ($labels as $label) {
            $prior[$label] = $labelCounts[$label] / $totalTrain;
        }

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

        foreach ($testData as $row) {
            $scores = [];
            foreach ($labels as $label) {
                $scores[$label] = $prior[$label];
                foreach ($features as $feature) {
                    $val = $row->$feature;
                    $scores[$label] *= $condProb[$feature][$val][$label] ?? (1 / ($labelCounts[$label] + 1));
                }
            }

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

        // Logging
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'performance ' . $percentage . '%',
            'created_at' => now(),
        ]);

        $totalData = $data->count();

        return view('pages.naive-bayes.performance', compact(
            'percentage',
            'trainData',
            'testData',
            'total',
            'trainCount',
            'testCount',
            'predictions',
            'accuracy',
            'totalData'
        ));
    }

    public function lazyLoadTraining(Request $request)
    {
        $percentage = (int) $request->get('percentage');
        $batch = (int) $request->get('batch', 1);
        $perBatch = 100;

        $total = ImportedData::count();
        $trainCount = (int) round($total * ($percentage / 100));
        $offset = ($batch - 1) * $perBatch;

        $trainData = ImportedData::orderBy('id')
            ->take($trainCount)
            ->skip($offset)
            ->take($perBatch)
            ->get();

        $filtered = $trainData->map(function ($row) {
            return collect($row->getAttributes())
                ->except(['id', 'user_id', 'file_name', 'file_size', 'created_at', 'updated_at'])
                ->values();
        });

        return response()->json($filtered);
    }


    public function lazyLoadTesting(Request $request)
    {
        $percentage = (int) $request->get('percentage');
        $batch = (int) $request->get('batch', 1);
        $perBatch = 100;

        $total = ImportedData::count();
        $trainCount = (int) round($total * ($percentage / 100));
        $offset = $trainCount + (($batch - 1) * $perBatch);

        $testData = ImportedData::orderBy('id')
            ->skip($offset)
            ->take($perBatch)
            ->get();

        $filtered = $testData->map(function ($row) {
            return collect($row->getAttributes())
                ->except(['id', 'user_id', 'file_name', 'file_size', 'created_at', 'updated_at'])
                ->values();
        });

        return response()->json($filtered);
    }

    public function lazyLoadProcess(Request $request)
{
    $percentage = $request->get('percentage');
    $batch = (int) $request->get('batch', 1);
    $perBatch = 100;

    $data = ImportedData::orderBy('id')->get();

    $total = $data->count();
    $trainCount = (int) round($total * ($percentage / 100));
    $testData = $data->slice($trainCount);

    // Hitung prediksi seperti sebelumnya
    $trainData = $data->take($trainCount);
    $labelCounts = $trainData->groupBy('keterangan')->map->count();
    $totalTrain = $trainData->count();

    $labels = $labelCounts->keys();
    $prior = [];
    foreach ($labels as $label) {
        $prior[$label] = $labelCounts[$label] / $totalTrain;
    }

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

    $start = ($batch - 1) * $perBatch;
    $batchData = $testData->slice($start, $perBatch);

    $predictions = [];

    foreach ($batchData as $row) {
        $scores = [];
        foreach ($labels as $label) {
            $scores[$label] = $prior[$label];
            foreach ($features as $feature) {
                $val = $row->$feature;
                $scores[$label] *= $condProb[$feature][$val][$label] ?? (1 / ($labelCounts[$label] + 1));
            }
        }

        $predicted = array_keys($scores, max($scores))[0];
        $actual = $row->keterangan;

        $predictions[] = [
            'data' => array_values(array_diff_key($row->getAttributes(), array_flip(['id', 'user_id', 'file_name', 'file_size', 'created_at', 'updated_at']))),
            'predicted' => $predicted,
            'correct' => $predicted === $actual,
        ];
    }

    return response()->json($predictions);
}

}
