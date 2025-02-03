<?php

namespace App\Http\Traits;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait Eweb
{
    public function syncCustomerEweb($customer)
    {
        $id_branches = [];
        if (strcasecmp($customer["id_branches"], 'SATWAGIA OFFICIAL STORE') == 0) {
            $id_branches[] = '44';
        } elseif (strcasecmp($customer["id_branches"], 'PT TEKNOLOGI DIGITAL VETERINER') == 0) {
            $id_branches[] = '24';
        } elseif (strcasecmp($customer["id_branches"], 'SATWAGIA OFFICIAL STORE SANDBOX') == 0) {
            $id_branches[] = '83';
        } else {
            $id_branches[] = '83';

            Log::error('Branches sync customer not found');
            throw new \Exception('Branches sync customer not found');
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

        $response = $this->curl_post('sync-customer', $arr_data);
        if ($response['status'] == true) {
            $arr_data['id_customer'] = $response['id_customer'];
            $customer_result = Customer::updateOrCreate([
                'nickname' => $customer["nickname"],
            ], $arr_data);

            Log::info('Sync Customer: ' . $customer["nickname"]);

            return $response['id_customer'];
        } else {
            $customer = Customer::where('nickname', $customer["nickname"])->first();
            Log::error('Sync Customer Error: ' . $response['message']);
            return $customer->id_customer;
        }
    }

    public function addPos($data)
    {
        $id_company = config('eweb.id_company_sgos', '83');
        $idtipetrans = config('eweb.idtipetrans', 1781);

        if (strcasecmp($data["id_company"], 'SATWAGIA OFFICIAL STORE') == 0) {
            $id_company = '44';
        } elseif (strcasecmp($data["id_company"], 'PT TEKNOLOGI DIGITAL VETERINER') == 0) {
            $id_company = '24';
        } elseif (strcasecmp($data["id_company"], 'SATWAGIA OFFICIAL STORE SANDBOX') == 0) {
            $id_company = '83';
        } else {
            $id_company = '83';

            Log::error('Branches add pos not found');
            // Throw new \Exception('Branches add pos not found');
        }

        $total = 0;
        foreach ($data['arr_detail'] as $detail) {
            // Pastikan qty dan total valid
            if (!isset($detail['qty']) || !is_numeric($detail['qty']) || $detail['qty'] <= 0) {
                Log::error("Invalid qty in order detail: " . json_encode($detail));
                continue; // Skip ke detail berikutnya
            }

            if (!isset($detail['total']) || !is_numeric($detail['total'])) {
                Log::error("Invalid total in order detail: " . json_encode($detail));
                continue; // Skip ke detail berikutnya
            }

            $total_pembagi = $detail['total'] / $detail['qty'];
            $diskon = $detail['harga_satuan'] - $total_pembagi;

            $harga_satuan = $detail['harga_satuan'];
            if ($diskon <= 0) {
                $harga_satuan = $detail['total'];
                $diskon = 0;
            }

            $arr_detail[] = array(
                'kode_original_inv' => $detail['item'],
                'cqty'              => $detail['qty'],
                'harga_satuan'      => (float)$harga_satuan,
                'discount_item'     => $diskon > 0 ? (float)$diskon : 0,
            );

            $total += $detail['total'];
        }


        $date_sinv = $this->dateformat_short(strtolower($data['date_sinv']));

        $arr_data = array(
            'id_company'        => $id_company,
            'id_customer'       => $data['id_customer'],
            'id_employee'       => $data['id_employee'],
            'kode_transaksi'    => 'POS',
            'no_sinv'           => $data['no_sinv'],
            'date_sinv'         => $date_sinv,
            'idtipetrans'       => $idtipetrans,
            'catatan'           => $data['catatan'],
            'detail'            => $arr_detail
        );

        $response = $this->curl_post('add-pos', $arr_data);

        if ($response['status'] == true) {
            // Pastikan total adalah float
            $total = (float) $total;

            $order = Order::updateOrCreate([
                'no_transaksi' => $arr_data["no_sinv"],
            ], [
                'no_transaksi' => $arr_data["no_sinv"],
                'tanggal' => $this->convertToDatabaseDate(ucwords($arr_data["date_sinv"])),
                'customer_id' => $arr_data["id_customer"],
                'total' => $total,
                'created_by' => Auth::user()->name
            ]);


            foreach ($arr_detail as $detail) {
                OrderDetail::updateOrCreate([
                    'kode_item' => $detail["kode_original_inv"],
                    'order_id' => $order->id,
                ], [
                    'qty' => $detail['cqty'],
                    'harga' => $detail['harga_satuan'],
                ]);
            }
        }

        return $response;
    }

    function curl_post($action_url, $payloads = [])
    {
        $url = config('eweb.base_url') . '/' . $action_url;
        $url .= '?username=' . config('eweb.username') . '&apikey=' . config('eweb.api_key');

        $data_json = http_build_query($payloads);
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
            $result = $err;
        } else {
            $response = json_decode($response_raw, TRUE);
            $result = $response;
        }

        return $result;
    }

    function dateformat_short($date)
    {
        // Map bulan Indonesia ke bahasa Inggris
        $bulanIndonesia = [
            'januari' => 'January',
            'februari' => 'February',
            'maret' => 'March',
            'april' => 'April',
            'mei' => 'May',
            'juni' => 'June',
            'juli' => 'July',
            'agustus' => 'August',
            'september' => 'September',
            'oktober' => 'October',
            'november' => 'November',
            'desember' => 'December',
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

    function convertToDatabaseDate($dateString) {
        // Pastikan format tanggal adalah DD-MM-YYYY
        $dateObject = DateTime::createFromFormat('d-m-Y', $dateString);

        if ($dateObject) {
            // Ubah ke format YYYY-MM-DD
            return $dateObject->format('Y-m-d');
        } else {
            // Return null jika format salah
            return null;
        }
    }
}
