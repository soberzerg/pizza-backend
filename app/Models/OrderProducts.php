<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\OrderProduct
 *
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property int $quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProducts newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProducts newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProducts query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProducts whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProducts whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProducts whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProducts whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProducts whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProducts whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrderProducts extends Pivot
{
    public $incrementing = true;

    protected $table = 'order_products';
}
