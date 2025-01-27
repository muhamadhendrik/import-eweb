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
            'pos_excel' => 'required|file',
        ]);

        $file = $request->file('pos_excel');

        $totalRows = 0;
        $rows = Excel::toArray(new \App\Imports\OrderImport, $file)[0];
        foreach ($rows as $value) {
            if ($value[0] != 'No') {
                if ($value[0] != null) {
                    $totalRows++;
                }
            }
        }

        $chunkSize = 100; // Jumlah baris per job
        $batchIndex = 0;

        foreach (array_chunk($rows, $chunkSize) as $chunk) {
            ImportEwebJob::dispatch($chunk, $totalRows, $batchIndex++);
        }

        return back()->with('success', 'Proses impor sedang berjalan. Anda dapat memantau progres.');
    }
}
