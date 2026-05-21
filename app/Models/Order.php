<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $table = 'zakazky';
    protected $fillable = ['zakaznik', 'celkom', 'stav'];

    public function polozky(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'zakazka_id');
    }
}
