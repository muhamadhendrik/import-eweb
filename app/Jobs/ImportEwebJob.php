<?php

namespace App\Jobs;

use App\Http\Traits\Eweb;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ImportEwebJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Eweb;

    protected $orders;
    protected $totalRows;
    protected $batchIndex;

    public function __construct($orders, $totalRows, $batchIndex)
    {
        $this->orders = $orders;
        $this->totalRows = $totalRows;
        $this->batchIndex = $batchIndex;
    }

    public function handle()
    {
        $processed = 0;

        foreach ($this->orders as $order) {
            try {
                // Validasi data utama
                if (empty($order['no_transaksi']) || empty($order['order_detail'])) {
                    Log::warning('Order skipped due to missing transaction data', [
                        'order' => $order,
                    ]);

                    throw new \Exception('Order skipped due to missing transaction data');
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
                    Log::error('Customer synchronization failed', [
                        'customer' => $customer,
                    ]);

                    throw new \Exception('Customer synchronization failed');
                }

                // Proses data transaksi POS
                $arr_order_detail = [];
                foreach ($order['order_detail'] as $detail) {
                    $arr_order_detail[] = [
                        'item' => (string)$detail['kode_item'], // Pastikan string
                        'qty' => (int)$detail['qty'],         // Pastikan integer
                        'harga_satuan' => (float)$detail['harga_satuan'], // Pastikan float
                        'total' => (float)$detail['total'],   // Pastikan float
                    ];
                }

                $posData = [
                    'id_company' => $order['outlet'],
                    'id_customer' => $customerId,
                    'id_employee' => $order['salesman'],
                    'no_sinv' => $order['no_transaksi'],
                    'date_sinv' => $order['tanggal'],
                    'catatan' => $order['notes'],
                    'arr_detail' => $arr_order_detail,
                ];

                $addPosResult = $this->addPos($posData);

                if (isset($addPosResult['status']) && $addPosResult['status'] !== true) {
                    Log::error('Failed to add POS data', [
                        'response' => $addPosResult,
                        'posData' => $posData,
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Error processing order', [
                    'order' => $order,
                    'error' => $e->getMessage(),
                ]);
            }

            $processed++;

            $progress = round((($this->batchIndex * count($this->orders) + $processed) / $this->totalRows) * 100);
            Log::info('Progress updated', [
                'batchIndex' => $this->batchIndex,
                'processed' => $processed,
                'progress' => $progress . '%',
            ]);

            event(new \App\Events\ImportProgressUpdated($progress));
        }
    }
}
