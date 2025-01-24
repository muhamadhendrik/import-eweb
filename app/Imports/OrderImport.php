<?php

namespace App\Imports;

use App\Http\Traits\Eweb;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;

class OrderImport implements ToModel
{
    use Eweb;

    public function model(array $row)
    {
        if ($row[0] === 'No') {
            return null;
        }

        $customer = [
            'nama' => $row[6],
            'nickname' => $row[6],
            'alamat' => $row[7],
            'kota' => $row[8],
            'kode_pos' => $row[9],
            'hp' => $row[10],
            'id_branches' => $row[2],
        ];

        try {
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

            if (isset($addPosResult['error'])) {
                throw new \Exception('POS Error: ' . $addPosResult['error']);
            }
        } catch (\Exception $e) {
            Log::error('OrderImport Error: ' . $e->getMessage());
        }
    }
}
