<?php

namespace App\Policies;

use App\Enum\UserAbilityEnum;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class EntryStaffPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can access in everything for a entryStaff.
     *
     * @param User $user
     * @return Response|bool
     */

    public function all(User $user): Response|bool
    {
        if ($user->hasAbility(UserAbilityEnum::ENTRY_CHECK)) {
            return true;
        }
        return false;
    }
}
