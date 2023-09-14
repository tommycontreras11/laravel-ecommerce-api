<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductInventory extends Model
{
    use HasFactory;

    public $table = "product_inventories";

    protected $fillable = [
        'quantity'
    ];

    public function product(): HasOne
    {
        return $this->hasOne(Product::class);
    }
}
