<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelPhone\Casts\E164PhoneNumberCast;

/**
 * @mixin IdeHelperAddress
 */
class Address extends Model
{
    use HasFactory;

    protected $casts = [
        'phone' => E164PhoneNumberCast::class . ':GR',
        'is_permanent' => 'boolean',
    ];
    #public $timestamps = false;
    public $fillable = ['academic_id', 'location', 'is_permanent'];

    public function cardApplicant()
    {
        return $this->belongsTo(CardApplicant::class, 'academic_id');
    }
}
