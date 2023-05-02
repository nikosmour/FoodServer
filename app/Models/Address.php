<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperAddress
 */
class Address extends Model
{
    use HasFactory;
    public $timestamps = false;
    public function cardApplicant()
    {
        return $this->belongsTo(CardApplicant::class,'academic_id');
    }
}
