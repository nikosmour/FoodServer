<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @mixin IdeHelperCardApplicationStaff
 */
class CardApplicationStaff extends User
{
    public function cardApplication(): BelongsToMany
    {
        return $this->belongsToMany(CardApplication::class,(new CardApplicationChecking)->getTable())->using(CardApplicationChecking::class);
    }
}
