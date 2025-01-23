<?php

namespace App\Models\Transaction;

use App\Models\Master\Product;
use App\Models\Master\Qrcode;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'product_id',
        'qrcode_id',
        'claimed_date',
        'name',
        'phone',
        'email',
        'city',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function qrcode()
    {
        return $this->belongsTo(Qrcode::class)->withTrashed();
    }
}
