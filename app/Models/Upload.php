<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    protected $fillable = [
        'uuid','total_chunks','received_chunks',
        'checksum','temp_path','status'
    ];
}
