<?php

namespace App\Model;

use App\Models\GuestUser;
use App\Models\OfflinePayment;
use App\Models\OrderPartialPayment;
use App\User;
use App\Model\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $guarded = [];

    protected $casts = [
        'order_amount' => 'float',
        'coupon_discount_amount' => 'float',
        'total_tax_amount' => 'float',
        'total_add_on_tax' => 'float',
        'delivery_address_id' => 'integer',
        'delivery_man_id' => 'integer',
        'delivery_charge' => 'float',
        'user_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'delivery_address' => 'array',
        'table_id' => 'integer',
        'number_of_people' => 'integer',
        'table_order_id' => 'integer',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function delivery_man(): BelongsTo
    {
        return $this->belongsTo(DeliveryMan::class, 'delivery_man_id')->withCount('orders');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->withCount('orders');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id')->withCount('orders');
    }

    public function delivery_address(): BelongsTo
    {
        return $this->belongsTo(CustomerAddress::class, 'delivery_address_id');
    }

    public function table_order(): BelongsTo
    {
        return $this->belongsTo(TableOrder::class, 'table_order_id', 'id');
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class, 'table_id', 'id');
    }

    public function scopePos($query)
    {
        return $query->where('order_type', '=', 'take_away');
    }

    public function scopeDineIn($query)
    {
        return $query->where('order_type', '=', 'dine_in');
    }


    public function scopeNotDineIn($query)
    {
        return $query->where('order_type', '!=', 'dine_in');
    }

    public function scopeNotPos($query)
    {
        return $query->where('order_type', '!=', 'pos');
    }

    public function scopeSchedule($query)
    {
        return $query->whereDate('delivery_date', '>', \Carbon\Carbon::now()->format('Y-m-d'));
    }

    public function scopeNotSchedule($query)
    {
        return $query->whereDate('delivery_date', '<=', \Carbon\Carbon::now()->format('Y-m-d'));
    }

    public function scopeVoided($query)
    {
        return $query->whereDate('order_status', 'void');
    }

    public function scopeNotVoided($query)
    {
        return $query->whereDate('order_status', '!=', 'void');
    }

    public function scopeRefunded($query)
    {
        return $query->whereDate('order_status', 'refund');
    }

    public function scopeNotRefunded($query)
    {
        return $query->whereDate('order_status', '!=', 'refund');
    }

    public function scopeEarningReport($query)
    {
        return $query->whereIn('order_status', ['delivered', 'completed']);
    }

    public function transaction(): HasOne
    {
        return $this->hasOne(OrderTransaction::class);
    }

    public function order_partial_payments(): HasMany
    {
        return $this->hasMany(OrderPartialPayment::class)->orderBy('id', 'DESC');
    }

    public function offline_payment()
    {
        return $this->hasOne(OfflinePayment::class, 'order_id');
    }

    public function guest()
    {
        return $this->belongsTo(GuestUser::class, 'user_id');
    }

    public function refundOrders()
    {
        return $this->hasMany(self::class, 'refund_order_id', 'id');
    }

    public function getRefundedOrdersProductsId()
    {
        return OrderDetail::whereIn('order_id', $this->refundOrders->pluck('id'))->pluck('product_id')->toArray();
    }

    public function voidOrders()
    {
        return $this->hasMany(self::class, 'void_order_id', 'id');
    }

    public function getVoidedOrdersProductsId()
    {
        return OrderDetail::whereIn('order_id', $this->voidOrders->pluck('id'))->pluck('product_id')->toArray();
    }
}
