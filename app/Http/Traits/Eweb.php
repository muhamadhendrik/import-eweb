<?php

namespace App\Http\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait Eweb
{
    public function syncCustomerEweb($customer)
    {
        $id_branches = [];
        if ($customer["id_branches"] == 'SGOS') {
            $id_branches[] = '83';
        }

        // Mengubah array menjadi string
        $id_brances = '{' . implode(',', $id_branches) . '}';

        # SEND TO EWEB
        $arr_data = array(
            'nama'                  => $customer["nama"],
            'nickname'              => $customer["nickname"],
            'alamat'                => $customer["alamat"],
            'kode_cusgrup'          => '005',
            'kota'                  => $customer["kota"],
            'kode_pos'              => $customer["kode_pos"],
            'update_by_nickname'    => 't',
            'hp'                    => $customer["hp"],
            'id_branches'           => $id_brances
        );

        $data_json = http_build_query($arr_data);

        $url = config('eweb.base_url') . '/sync-customer';
        $url .= '?username=' . config('eweb.username') . '&apikey=' . config('eweb.api_key');

        $curl = curl_init($url);

        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data_json,
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache"
            ),
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
        ));

        $response_raw = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);


        if ($err) {
            return false;
        } else {
            $response = json_decode($response_raw, TRUE);
            return $response['id_customer'];
        }
    }

    public function addPos($data)
    {
        $id_company = config('eweb.id_company_sgos', '83');
        $idtipetrans = config('eweb.idtipetrans', 1781);

        if ($data["id_company"] == 'SGOS') {
            $id_company = '83';
        }

        // $price_sell = $this->priceSell($data["item"], $id_company);
        $total_pembagi = $data['total'] / $data['qty'];
        $diskon = $data['harga_satuan'] - $total_pembagi;
        // $diskon =  ($data['harga_satuan'] * 2) - $nominal_total_diskon;
        // $diskon = $diskon / $data['qty'];
        // $sub_total = $data['harga_satuan'] * $data['qty'];

        $arr_detail[] = array(
            'kode_original_inv' => $data['item'],
            'cqty'              => $data['qty'],
            'harga_satuan'      => $data['harga_satuan'],
            'discount_item'     => $diskon,
        );

        $date_sinv = $this->dateformat_short($data['date_sinv']);

        $arr_data = array(
            'id_company'        => $id_company,
            'id_customer'       => $data['id_customer'],
            'id_employee'       => $data['id_employee'],
            'kode_transaksi'    => 'POS',
            'no_sinv'           => $data['no_sinv'],
            'date_sinv'         => $date_sinv,
            'idtipetrans'       => $idtipetrans,
            'detail'            => $arr_detail,
            'catatan'           => $data['catatan'],
        );


        return $this->curl_post('add-pos', $arr_data);
    }

    public function curl_post($action_url, $payloads = [])
    {
        $url = config('eweb.base_url') . '/' . $action_url;
        $url .= '?username=' . config('eweb.username') . '&apikey=' . config('eweb.api_key');

        $data_json = http_build_query($payloads);

        try {
            $curl = curl_init($url);

            curl_setopt_array($curl, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $data_json,
                CURLOPT_HTTPHEADER => ["cache-control: no-cache"],
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
            ]);

            $response_raw = curl_exec($curl);

            if (curl_errno($curl)) {
                throw new \Exception(curl_error($curl));
            }

            curl_close($curl);
            $response = json_decode($response_raw, true);

            if (!$response) {
                throw new \Exception('Invalid JSON response');
            }

            return $response;
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    function dateformat_short($date)
    {
        // Map bulan Indonesia ke bahasa Inggris
        $bulanIndonesia = [
            'Januari' => 'January',
            'Februari' => 'February',
            'Maret' => 'March',
            'April' => 'April',
            'Mei' => 'May',
            'Juni' => 'June',
            'Juli' => 'July',
            'Agustus' => 'August',
            'September' => 'September',
            'Oktober' => 'October',
            'November' => 'November',
            'Desember' => 'December',
        ];

        try {
            // Ganti nama bulan ke bahasa Inggris
            $tanggalDalamBahasaInggris = str_replace(array_keys($bulanIndonesia), array_values($bulanIndonesia), $date);

            // Parse dan format tanggal
            $formattedDate = Carbon::parse($tanggalDalamBahasaInggris)->format('d-m-Y');
            return $formattedDate;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
