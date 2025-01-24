<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Imports\OrderImport;
use App\Jobs\ImportEwebJob;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportEwebController extends Controller
{
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
        $rows = Excel::toArray(new \App\Imports\OrderImport, $file)[0];

        $chunkSize = 100; // Jumlah baris per job
        $totalRows = count($rows);
        $batchIndex = 0;

        foreach (array_chunk($rows, $chunkSize) as $chunk) {
            ImportEwebJob::dispatch($chunk, $totalRows, $batchIndex++);
        }

        return back()->with('success', 'Proses impor sedang berjalan. Anda dapat memantau progres.');
    }
}
