<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PredictionExport implements FromView, WithStyles
{
    public $predictions;
    public $summary;

    public function __construct($predictions, $summary)
    {
        $this->predictions = $predictions;
        $this->summary = $summary;
    }

    public function view(): View
    {
        return view('exports.report', [
            'predictions' => $this->predictions,
            'summary' => $this->summary
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        // Header bold
        $sheet->getStyle('A1:Z1')->getFont()->setBold(true);

        // Highlight baris dengan prediksi benar/salah
        $row = 11;
        foreach ($this->predictions as $prediction) {
            $color = $prediction['predicted'] === $prediction['actual'] ? '90EE90' : 'F08080'; // LightGreen or LightCoral
            $columnCount = count($prediction['data']->getAttributes()) - 5; // minus excluded cols + 1 for "hasil testing"
            $range = 'A' . $row . ':' . chr(65 + $columnCount) . $row;
            $sheet->getStyle($range)->getFill()->setFillType('solid')->getStartColor()->setRGB($color);
            $row++;
        }

        return [];
    }
}
