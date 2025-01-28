<?php

namespace App\Jobs;

use App\Http\Traits\Eweb;
use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ImportEwebJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Eweb;

    protected $rows;
    protected $totalRows;
    protected $batchIndex;

    public function __construct($rows, $totalRows, $batchIndex)
    {
        $this->rows = $rows;
        $this->totalRows = $totalRows;
        $this->batchIndex = $batchIndex;
    }

    public function handle()
    {
        $processed = 0;
        foreach ($this->rows as $roder) {
            if ($roder[0] === 'No') {
                continue;
            }

            try {
                // Validasi data
                if (!isset($order['no_transaksi'], $order['order_detail'])) {
                    continue;
                }

                // Sinkronisasi data customer
                $customer = [
                    'nama' => $order['nama'],
                    'nickname' => $order['nama'],
                    'alamat' => $order['alamat'],
                    'kota' => $order['kota'],
                    'kode_pos' => $order['kode_pos'],
                    'hp' => $order['no_telp'],
                    'id_branches' => $order['outlet'],
                ];

                $customerId = $this->syncCustomerEweb($customer);

                if (!$customerId) {
                    Log::error('ImportEwebJob Error: Customer ID not found');
                }

                // Tambahkan data transaksi POS
                foreach ($order['order_detail'] as $detail) {
                    $arr_order_detail[] = [
                        'item' => $detail['kode_item'],
                        'qty' => $detail['qty'],
                        'harga_satuan' => $detail['harga_satuan'],
                        'total' => $detail['total'],
                    ];
                }

                $posData = [
                    'id_company' => $order['outlet'],
                    'id_customer' => $customerId,
                    'id_employee' => $order['salesman'],
                    'no_sinv' => $order['no_transaksi'],
                    'date_sinv' => $order['tanggal'],
                    'catatan' => $order['notes'],
                    'arr_detail' => $arr_order_detail
                ];

                $addPosResult = $this->addPos($posData);

                if (isset($addPosResult['status']) && $addPosResult['status'] !== true) {
                    Log::error('ImportEwebJob Error: ' . $addPosResult['message']);
                }
                // Update progress event
            } catch (\Exception $e) {
                Log::error('ImportEwebJob Error: ' . $e->getMessage());
                Throw new \Exception($e->getMessage());
            }

            $processed++;
            $progress = round((($this->batchIndex * count($this->rows) + $processed) / $this->totalRows) * 100);

            Log::info('Processing row', [
                'rowIndex' => $processed,
                'batchIndex' => $this->batchIndex,
                'progress' => $progress . '%',
            ]);

            event(new \App\Events\ImportProgressUpdated($progress));
        }
    }
}
