<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @mixin IdeHelperTransferCoupon
 */
class TransferCoupon extends Pivot
{
    use HasFactory;

    public $guarded = [];
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();
        static::creating(
        /**
         * define default sender_id when model is created
         * @param $model
         * @return void
         */
            function ($model) {
                if ($model->isClean('sender_id'))
                    $model->sender_id = auth()->user()->academic_id;
                CouponOwner::addCoupons($model['receiver_id'], $model->attributes);
                CouponOwner::removeCoupons($model['sender_id'], $model->attributes);
            });
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(CouponOwner::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(CouponOwner::class, 'receiver_id');
    }

}
