<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    //disabling the 'created_at' and 'updated_at' columns
    public $timestamps = false;
    //setting the fillable columns
    protected $fillable = ['product_name', 'description', 'price', 'id_catalog', 'product_image'];
}
