<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
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
            'pos_excel' => 'required|file|mimetypes:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);

        $file = $request->file('pos_excel');

        // Mengambil data dari Excel
        $rows = Excel::toArray(new \App\Imports\OrderImport, $file)[0];

        $totalRows = 0;
        $arr_orders = [];
        foreach ($rows as $index => $row) {
            // Lewati header
            if ($index === 0 && strtolower($row[0] ?? '') === 'no') {
                continue;
            }

            // Lewati baris tanpa nomor transaksi
            if (empty($row[5])) {
                continue;
            }

            $no_transaksi = $row[5];

            if (!isset($arr_orders[$no_transaksi])) {
                $arr_orders[$no_transaksi] = [
                    'tanggal' => $row[1],
                    'outlet' => $row[2],
                    'salesman' => $row[3],
                    'notes' => $row[4],
                    'no_transaksi' => $row[5],
                    'nama' => $row[6],
                    'alamat' => $row[7],
                    'kota' => $row[8],
                    'kode_pos' => $row[9],
                    'no_telp' => $row[10],
                    'order_detail' => [],
                ];

                $totalRows++;
            }

            $harga_satuan = $row[13] / $row[12];

            $arr_orders[$no_transaksi]['order_detail'][] = [
                'kode_item' => $row[11],
                'qty' => $row[12],
                'harga_satuan' => $harga_satuan,
                'total' => $row[13],
            ];
        }

        $batchIndex = 0;

        foreach (array_chunk($arr_orders, 50, true) as $chunk) {
            ImportEwebJob::dispatch($chunk, $totalRows, $batchIndex)->delay(now()->addSeconds(1));
            $batchIndex++;
        }

        return back()->with('success', 'Proses impor sedang berjalan. Anda dapat memantau progres.');
    }
}
