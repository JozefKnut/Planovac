<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $table = 'zakazka_polozky';
    protected $fillable = ['zakazka_id', 'vyrobok_id', 'mnozstvo', 'cena_za_jednotku'];

    public function vyrobok(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'vyrobok_id');
    }
}
