<?php

namespace App\Imports;

use App\Http\Traits\Eweb;
use App\Models\Order;
use Maatwebsite\Excel\Concerns\ToModel;

class OrderImport implements ToModel
{
    use Eweb;

    public function model(array $row)
    {
        if ($row[0] == 'No') {
            return null;
        }

        $customer = [
            'nama' => $row[6],
            'nickname' => $row[6],
            'alamat' => $row[7],
            'kode_cusgrup' => 'E COMMERCE',
            'kota' => $row[8],
            'kode_pos' => $row[9],
            'update_by_nickname'    => 'yes',
            'id_customer'           => '0',
            'hp' => $row[10],
            'telepon_1' => $row[10],
            'id_branches' => $row[2]
        ];

        $customer = $this->syncCustomerEweb($customer);

        $pos = [
            'id_company' => $row[2],
            'id_customer' => $customer,
            'id_employee' => $row[3],
            'no_sinv' => $row[5],
            'date_sinv' => $row[1],
            'catatan' => $row[4],
            'item' =>  $row[11],
            'qty' => $row[12],
            'harga_satuan' => $row[13],
            'total' => $row[14]
        ];

        $add_pos = $this->addPos($pos);
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required|email|unique:users'
        ];
    }
}
