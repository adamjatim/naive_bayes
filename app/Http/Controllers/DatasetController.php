<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImportedData;
use App\Models\ImportedDataHistory;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use function PHPSTORM_META\type;

class DatasetController extends Controller
{
    public function index()
    {
        // Ambil data dengan pagination (misalnya, 50 data per halaman)
        $importedData = ImportedData::paginate(50);
        // dd($importedData);
        return view('pages.naive-bayes.dataset', compact('importedData'));
    }

    public function import(Request $request)
    {
        set_time_limit(120); // Tingkatkan batas waktu eksekusi

        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv'
        ]);

        try {
            $file = $request->file('file');
            $data = Excel::toArray([], $file)[0]; // Ambil data dari sheet pertama

            // Ambil header (baris pertama)
            $headers = array_shift($data);

            // Ambil email user yang sedang login
            $userEmail = Auth::user()->email;

            // Pindahkan data lama ke tabel history
            $existingData = ImportedData::all();
            foreach ($existingData as $oldData) {
                ImportedDataHistory::create([
                    'user_email' => $oldData->user_email,
                    'dataset_id' => $oldData->dataset_id,
                    'row_data' => $oldData->row_data,
                    'modified_at' => now(), // Waktu saat data dipindahkan
                ]);
            }

            // Hapus data lama dari tabel imported_data
            ImportedData::truncate();

            // Bagi data menjadi chunk berisi 100 baris
            $chunks = array_chunk($data, 100);

            // Proses setiap chunk
            foreach ($chunks as $chunk) {
                foreach ($chunk as $row) {
                    ImportedData::create([
                        'user_email' => $userEmail,
                        'dataset_id' => 1, // Sesuaikan dengan dataset_id yang sesuai
                        'row_data' => json_encode(array_combine($headers, $row)),
                    ]);
                }
            }

            return redirect()->route('naive-bayes.dataset.index')->with('message', [
                'type' => 'Success',
                'text' => 'Data berhasil diimport!',
            ]);
        } catch (\Exception $e) {
            Log::error('Error importing data: ' . $e->getMessage());
            return redirect()->route('naive-bayes.dataset.index')->with('message', [
                'type' => 'Error',
                'text' => 'Terjadi kesalahan saat mengimport data.',
            ]);
        }
    }

    public function history()
    {
        $history = ImportedDataHistory::with('user')->orderBy('modified_at', 'desc')->get();
        return view('pages.naive-bayes.history', compact('history'));
    }

    public function restoreHistory($history_id)
    {
        // Ambil data dari history berdasarkan ID
        $historyData = ImportedDataHistory::findOrFail($history_id);

        // Hapus data saat ini dari imported_data
        ImportedData::truncate();

        // Masukkan data dari history ke imported_data
        ImportedData::create([
            'user_id' => Auth::id(),
            'dataset_id' => $historyData->dataset_id,
            'row_data' => $historyData->row_data,
        ]);

        return redirect()->back()->with('success', 'Data berhasil dikembalikan');
    }
}
