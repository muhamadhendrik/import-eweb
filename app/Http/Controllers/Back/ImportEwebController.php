<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Http\Traits\Eweb;
use App\Imports\OrderImport;
use App\Jobs\ImportEwebJob;
use App\Models\Customer;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportEwebController extends Controller
{
    use Eweb;

    public function index()
    {
        return view('back.import-eweb.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'pos_excel' => 'required|file|mimetypes:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);

        $file = $request->file('pos_excel');

        // Mengambil data dari Excel
        $rows = Excel::toArray(new \App\Imports\OrderImport, $file)[0];
        $validRows = []; // Untuk menyimpan baris valid

        foreach ($rows as $index => $value) {
            // Lewati header (misalnya baris pertama atau baris dengan "No")
            if ($index === 0 || strtolower($value[0]) === 'no') {
                continue;
            }

            // Lewati baris null atau kosong
            if (empty(array_filter($value))) {
                continue;
            }

            // Tambahkan baris valid ke array
            $validRows[] = $value;
        }

        $totalRows = count($validRows); // Hitung total baris valid

        // Bagi data ke dalam chunk
        $chunkSize = 100; // Jumlah baris per job
        foreach (array_chunk($validRows, $chunkSize) as $batchIndex => $chunk) {
            // Dispatch job untuk memproses setiap chunk
            ImportEwebJob::dispatch($chunk, $totalRows, $batchIndex);
        }

        return back()->with('success', 'Proses impor sedang berjalan. Anda dapat memantau progres.');
    }
}
