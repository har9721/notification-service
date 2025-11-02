<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Mst_Roles extends Model
{
    protected $table = "mst__roles";

    protected $fillable = [
        'name',
        'is_active'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'role_id');    
    }
}
