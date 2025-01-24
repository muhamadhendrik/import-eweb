<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;

class OrderImport implements ToArray
{
    /**
     * Method untuk membaca seluruh isi file Excel.
     */
    public function array(array $array)
    {
        return $array;
    }
}
