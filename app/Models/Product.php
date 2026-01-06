<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'sku','name','description','price','primary_image_id','primary_size'
    ];

    public function images()
    {
        return $this->hasMany(Image::class);
    }
}