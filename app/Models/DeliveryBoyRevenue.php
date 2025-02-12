<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int|mixed $revenue
 * @property mixed $delivery_boy_id
 * @property mixed $order_id
 * @property mixed $shop_id
 * @property float|int|mixed $pay_to_admin
 * @property DeliveryBoyRevenue|mixed $take_from_admin
 * @property DeliveryBoyRevenue|mixed $take_from_shop
 * @property float|int|mixed $pay_to_shop
 * @property mixed $collected_payment_from_customer
 * @method static create(array $delivery_boy_revenue)
 * @method static where(string $string, mixed $user_id)
 */
class DeliveryBoyRevenue extends Model
{
    //===================== RelationShips  ====================================//

    public function deliveryBoy(): BelongsTo
    {
        return $this->belongsTo(DeliveryBoy::class);
    }
}
