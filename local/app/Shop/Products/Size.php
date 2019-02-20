<?php

namespace App\Shop\Products;
use App\Shop\Products\Product;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    //
    protected $fillable = [
        'product_id',
        'size',
        'value'
    ];

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
