<?php

namespace App\Models\Acl;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'acl_menus';

    protected $fillable = [
        'name',
        'description',
        'link',
        'parent_id',
        'parent_type',
        'parent_icon',
        'permission_key',
        'permission_option',
        'ordering',
    ];
}
