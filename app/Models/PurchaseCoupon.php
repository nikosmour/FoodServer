<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperPurchaseCoupon
 */
class PurchaseCoupon extends Model
{
    use HasFactory;

    public $guarded = [];
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();
        static::creating(
        /**
         * define default sender_id when model is created and
         * adding the coupons to the buyer
         * @param $model
         * @return void
         */
            function ($model) {
                if ($model->isClean('coupon_staff_id'))
                    /** @noinspection PhpUndefinedFieldInspection */ $model->coupon_staff_id = auth()->user()->id;
                CouponOwner::addCoupons($model['academic_id'], $model->attributes);
            });
    }

    public function getMoneyAttribute($money): float|int
    {
        return $money / 100;
    }

    public function setMoneyAttribute($money)
    {
        $this->attributes['money'] = $money * 100;
    }

    public function couponOwner(): BelongsTo
    {
        return $this->belongsTo(CouponOwner::class, 'academic_id');
    }

    public function couponStaff(): BelongsTo
    {
        return $this->belongsTo(CouponStaff::class);
    }
}
