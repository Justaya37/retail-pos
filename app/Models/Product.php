<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'barcode',
        'name',
        'price_buy',
        'price_sell',
        'stock',
    ];

    protected $casts = [
        'price_buy' => 'decimal:2',
        'price_sell' => 'decimal:2',
        'stock' => 'integer',
    ];

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function stockMutations()
    {
        return $this->hasMany(StockMutation::class);
    }
}
