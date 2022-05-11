<?php

namespace App\Models;

use App\Enum\MealPlanPeriodEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperUsageCoupon
 */
class UsageCoupon extends Model
{
    use HasFactory;

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     * @var array<int, string>
     */
    public $fillable = ['academic_id'];

    protected static function boot()
    {
        parent::boot();
        static::creating(
        /**
         * define default status and entry_staff_id when model is created
         * @param $model
         * @return void
         */
            callback: function ($model): void {
                if ($model->isClean('period'))
                    $model->period = MealPlanPeriodEnum::getCurrentMealPeriod();
                if ($model->isClean('entry_staff_id'))
                    /** @noinspection PhpUndefinedFieldInspection */ $model->entry_staff_id = auth()->user()->id;
                $model->couponOwner()->decrement($model->period->name);

            });
    }

    /**
     * Get the couponOwner model associate with the usageCoupon
     * @return BelongsTo
     */
    public function couponOwner(): BelongsTo
    {
        return $this->belongsTo(CouponOwner::class, 'academic_id');
    }

    /**
     * Get the entryStaff model associate with the usageCoupon
     * @return BelongsTo
     */
    public function entryStaff(): BelongsTo
    {
        return $this->belongsTo(EntryStaff::class);
    }
}
