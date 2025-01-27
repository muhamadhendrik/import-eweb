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

        // Bagi data ke dalam chunk dan proses langsung
        $chunkSize = 100; // Jumlah baris per job
        $totalRows = 0;

        foreach (array_chunk($rows, $chunkSize) as $batchIndex => $chunk) {
            $validRows = array_filter($chunk, function ($row, $index) use ($batchIndex) {
                // Lewati header dan baris kosong
                return $index !== 0 || strtolower($row[0]) !== 'no' && !empty(array_filter($row));
            }, ARRAY_FILTER_USE_BOTH);

            // Hitung total baris valid
            $totalRows += count($validRows);

            // Dispatch job untuk setiap chunk
            if (!empty($validRows)) {
                ImportEwebJob::dispatch($validRows, $totalRows, $batchIndex);
            }
        }

        return back()->with('success', 'Proses impor sedang berjalan. Anda dapat memantau progres.');
    }
}
