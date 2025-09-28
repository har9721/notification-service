<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mst_Roles extends Model
{
    protected $table = "mst__roles";

    protected $fillable = [
        'name',
        'is_active'
    ];
}
