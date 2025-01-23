<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'registration_number',
        'link_registration',
        'promotion',
        'description',
        'logo',
        'image'
    ];
}
