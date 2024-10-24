<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin IdeHelperAcademic
 */
class Academic extends User
{
    public $incrementing = false;
    protected $primaryKey = 'academic_id';


    /**
     * Merge new casts with existing casts on the model.
     *
     * @param array $casts
     * @return $this
     */

//    public function __construct()
//    {
//        parent::__construct();
//        $this->casts['is_active'] = 'boolean'; // Define the column as boolean
//
//
//    }

    public function cardApplicant(): HasOne
    {
        return $this->hasOne(CardApplicant::class, 'academic_id');
    }

    public function couponOwner(): HasOne
    {
        return $this->hasOne(CouponOwner::class, 'academic_id');
    }
}
