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
        foreach ($this->rows as $row) {
            if ($row[0] === 'No') {
                continue;
            }

            try {
                $customer = [
                    'nama' => $row[6],
                    'nickname' => $row[6],
                    'alamat' => $row[7],
                    'kota' => $row[8],
                    'kode_pos' => $row[9],
                    'hp' => $row[10],
                    'id_branches' => $row[2],
                ];

                $customerId = $this->syncCustomerEweb($customer);

                if (!$customerId) {
                    throw new \Exception('Customer synchronization failed');
                }

                $posData = [
                    'id_company' => $row[2],
                    'id_customer' => $customerId,
                    'id_employee' => $row[3],
                    'no_sinv' => $row[5],
                    'date_sinv' => $row[1],
                    'catatan' => $row[4],
                    'item' => $row[11],
                    'qty' => $row[12],
                    'harga_satuan' => $row[13],
                    'total' => $row[14],
                ];

                $addPosResult = $this->addPos($posData);

                if (isset($addPosResult['status']) && $addPosResult['status'] !== true) {
                    throw new \Exception('POS status: ' . $addPosResult['status'] . ' MESSAGE: ' . $addPosResult['message']);
                }

            } catch (\Exception $e) {
                Log::error('ImportEwebJob Error: ' . $e->getMessage());
            }

            $processed++;
            $progress = round((($this->batchIndex * count($this->rows) + $processed) / $this->totalRows) * 100);
            event(new \App\Events\ImportProgressUpdated($progress));
        }
    }
}
