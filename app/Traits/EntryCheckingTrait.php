<?php

namespace App\Traits;

use App\Enum\MealPlanPeriodEnum;
use App\Models\CardApplication;
use App\Models\CouponOwner;
use App\Models\UsageCard;
use App\Models\UsageCoupon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use JetBrains\PhpStorm\ArrayShape;
use Throwable;

trait EntryCheckingTrait
{
    /**
     * Check if the user can pass the entry point
     * @param array $data
     * @return array
     * <p>
     * A string that define how the user pass the entry point or if didn't poss
     * </p>
     */
    private function canPass(array $data): array
    {
        $json = ['pass' => false];
        try {
            if ($this->canPassAsCardApplicant($data))
                return ['pass' => true,
                    'passWith' => 'card'];
            else
                $json += ['card' => ['message' => 'expired or not exist card ']];
        } catch (ModelNotFoundException) {
            $json += ['card' => ['message' => 'not have a card']];
        } catch (QueryException $e) {
            // check if the error is for duplicate entry
            if (23000 == $e->getCode())
                $json += ['card' => ['message' => 'already use the card']];
            else
                $json += ['card' => $e];
        } catch (Throwable $e) {
            $json += ['card' => $e];
        }
        try {
            $this->canPassAsCouponOwner($data);
            return ['pass' => true,
                    'passWith' => 'coupon'] + $json;
        } catch (ModelNotFoundException) {
            return $json + ['coupon' => ['message' => 'not be a coupon owner']];
        } catch (QueryException $e) {
            // check if the error is for negative
            if (22003 == $e->getCode())//1690
                return $json + ['coupon' => ['message' => 'not have enough coupons']];
            return $json + ['coupon' => $e];
        } catch (Throwable $e) {
            return $json + ['coupon' => $e];

        }
    }

    /**
     * Check if the user can pass the entry point as couponOwner
     * @param array $data
     * @return bool
     * <p>
     * A boolean that define if the user pass
     * </p>
     */
    private function canPassAsCardApplicant(array $data): bool
    {

        $cardApplications = CardApplication::whereAcademicId($data['academic_id'])->where('expiration_date', '>=', now()->toDateString())->first();
        if (is_null($cardApplications))
            return false;
        UsageCard::create($data);
        return true;
    }

    /**
     * Check if the user can pass the entry point as couponOwner
     * @param array $data
     * @return bool
     * <p>
     * A string that define how the user pass the entry point or if didn't poss
     * </p>
     */
    private function canPassAsCouponOwner(array $data): bool
    {
        CouponOwner::findOrFail($data['academic_id']);
        UsageCoupon::create($data);
        return true;
    }

    /**
     * the already entries for current meal
     * @return array
     */
    #[ArrayShape(['coupons' => "int", 'cards' => "int"])]
    private function statisticsStartValues(): array
    {
        $currentMeal = MealPlanPeriodEnum::getCurrentMealPeriod()->value;
        $currentDate = now()->format('Y-m-d');
        return [
            'coupons' => UsageCoupon::all()->where('created_at', '>', $currentDate)->where('period', $currentMeal)->count(),
            'cards' => UsageCard::all()->where('date', '>', $currentDate)->where('period', $currentMeal)->count()
        ];
    }

}
