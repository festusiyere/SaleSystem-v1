<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'cost', 'quantity', 'unit', 'unique_id', 'details', 'price'
    ];
}
