<?php

namespace App\Models\Master;

use App\Models\Transaction\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Qrcode extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'serial_code',
        'batch_number',
        'expired_date',
        'status'
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id')->withTrashed();
    }
}
