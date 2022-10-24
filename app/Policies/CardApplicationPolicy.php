<?php

namespace App\Policies;

use App\Enum\UserAbilityEnum;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class CardApplicationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can access in everything for a CouponOwner.
     *
     * @param User $user
     * @return Response|bool
     */
    public function all(User $user): Response|bool
    {
        if ($user->hasAbility(UserAbilityEnum::CARD_OWNERSHIP)) {
            return true;
        }
        return false;
    }
}
