<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImport extends Model
{
    protected $fillable = [
        'filename', 'total', 'inserted', 'updated', 'invalid', 'duplicates','expected_total',
    ];
}