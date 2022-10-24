<?php

namespace App\Policies;

use App\Enum\UserAbilityEnum;
use App\Models\DailyMealPlan;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class DailyMealPlanPolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     *
     * @param User $user
     * @return void|bool
     */
    public function before(?User $user)
    {
        if (optional($user)->hasAbility(UserAbilityEnum::DAILY_MEAL_PLAN_CREATE)) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param User|null $user
     * @return Response|bool
     */
    public function viewAny(?User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User|null $user
     * @param DailyMealPlan $dailyMealPlan
     * @return Response|bool
     */
    public function view(?User $user, DailyMealPlan $dailyMealPlan)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param DailyMealPlan $dailyMealPlan
     * @return Response|bool
     */
    public function update(User $user, DailyMealPlan $dailyMealPlan)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param DailyMealPlan $dailyMealPlan
     * @return Response|bool
     */
    public function delete(User $user, DailyMealPlan $dailyMealPlan)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param DailyMealPlan $dailyMealPlan
     * @return Response|bool
     */
    public function restore(User $user, DailyMealPlan $dailyMealPlan)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param DailyMealPlan $dailyMealPlan
     * @return Response|bool
     */
    public function forceDelete(User $user, DailyMealPlan $dailyMealPlan)
    {
        //
    }
}
