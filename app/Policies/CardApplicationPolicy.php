<?php

namespace App\Policies;

use App\Enum\UserAbilityEnum;
use App\Models\User;
use App\Models\CardApplication;
use Illuminate\Auth\Access\HandlesAuthorization;

class CardApplicationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return isset($user->cardApplicant);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\CardApplication $cardApplication
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, CardApplication $cardApplication)
    {
        if (Auth('cardApplicationStaffs')->user())
            return true;
        return $user->academic_id == $cardApplication->academic_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return isset($user->cardApplicant);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\CardApplication $cardApplication
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, CardApplication $cardApplication)
    {
        return isset($user->cardApplicant);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\CardApplication $cardApplication
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, CardApplication $cardApplication)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\CardApplication $cardApplication
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, CardApplication $cardApplication)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\CardApplication $cardApplication
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, CardApplication $cardApplication)
    {
        //
    }
}
