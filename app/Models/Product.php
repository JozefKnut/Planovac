<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'vyrobky';
    protected $fillable = ['nazov', 'jednotka', 'cena', 'naklady'];
}
