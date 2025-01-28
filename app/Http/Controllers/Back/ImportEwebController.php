<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Http\Traits\Eweb;
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

        $arr_order = [];

        foreach ($rows as $index => $row) {
            // Lewati header
            if ($index === 0 && strtolower($row[0] ?? '') === 'no') {
                continue;
            }

            // Lewati baris yang tidak memiliki nomor transaksi
            if (empty($row[5])) {
                continue;
            }

            $no_transaksi = $row[5];

            // Jika nomor transaksi sudah ada, tambahkan item ke dalam order_detail
            if (isset($arr_order[$no_transaksi])) {
                $arr_order[$no_transaksi]['order_detail'][] = [
                    'kode_item' => $row[11],
                    'qty' => $row[12],
                    'harga_satuan' => $row[13],
                    'total' => $row[14],
                ];
            } else {
                // Jika nomor transaksi belum ada, tambahkan order baru
                $arr_order[$no_transaksi] = [
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
                    'order_detail' => [
                        [
                            'kode_item' => $row[11],
                            'qty' => $row[12],
                            'harga_satuan' => $row[13],
                            'total' => $row[14],
                        ],
                    ],
                ];
            }
        }

        // Dispatch job untuk setiap order
        foreach ($arr_order as $order) {
            ImportEwebJob::dispatch($order)->delay(now()->addSeconds(1));
        }

        return back()->with('success', 'Proses impor sedang berjalan. Anda dapat memantau progres.');
    }
}
