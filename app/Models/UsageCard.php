<?php

namespace App\Models;

use App\Enum\MealPlanPeriodEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperUsageCard
 */
class UsageCard extends Model
{
    use HasFactory;

    public $incrementing = false;
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     * @var array<int, string>
     */
    public $fillable = ['academic_id'];

    /**
     * The attributes that are the primary key
     * @var array<int, string>
     */
    protected $primaryKey = ['date', 'academic_id', 'type'];

    /**
     * The attributes that should be cast.
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date:Y-m-d',
        'time' => 'date:H:m',
    ];

    protected static function boot()
    {
        parent::boot();
        // define default date,time and entry_staff_id when model is created
        static::creating(
        /**
         * define default date, time, status and entry_staff_id when model is created
         * @param $model
         * @return void
         */
            function ($model) {
                if ($model->isClean('date'))
                    $model->date = now();
                if ($model->isClean('time'))
                    $model->time = now();
                if ($model->isClean('period'))
                    $model->period = MealPlanPeriodEnum::getCurrentMealPeriod();
                if ($model->isClean('entry_staff_id'))
                    /** @noinspection PhpUndefinedFieldInspection */ $model->entry_staff_id = auth()->user()->id;


            });
    }

    /**
     * Get the cardApplicant model associate with the usageCard
     * @return BelongsTo
     */
    public function cardApplicant(): BelongsTo
    {
        return $this->belongsTo(CardApplicant::class, 'academic_id');
    }

    /**
     * Get the entryStaff model associate with the usageCard
     * @return BelongsTo
     */
    public function entryStaff(): BelongsTo
    {
        return $this->belongsTo(EntryStaff::class);
    }

}
