<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'nama', 'nickname', 'email', 'alamat', 'kode_cusgrup', 'kota', 'kode_pos', 'update_by_nickname', 'id_customer', 'hp', 'telepon_1', 'id_branches'
    ];
}
