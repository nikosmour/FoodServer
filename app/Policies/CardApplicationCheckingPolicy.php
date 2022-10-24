<?php

namespace App\Policies;

use App\Enum\UserAbilityEnum;
use App\Models\CardApplicationChecking;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class CardApplicationCheckingPolicy
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
        if ($user->hasAbility(UserAbilityEnum::CARD_APPLICATION_CHECK)) {
            return true;
        }
        return false;
    }
}
