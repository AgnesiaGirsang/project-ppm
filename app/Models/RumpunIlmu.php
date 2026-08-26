<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RumpunIlmu extends Model
{
    protected $table = 'rumpun_ilmu';

    protected $fillable = [
        'kode',
        'nama',
        'level'
    ];
}
