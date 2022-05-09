<?php

namespace App\Http\Controllers;

use App\Enum\MealPlanPeriodEnum;
use App\Enum\UserAbilityEnum;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CouponOwnerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:academics');
        $this->middleware('ability:' . UserAbilityEnum::COUPON_OWNERSHIP->name);

    }

    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return Application|Factory|\Illuminate\Contracts\View\View|View
     */
    public function __invoke(Request $request): Application|Factory|\Illuminate\Contracts\View\View|View
    {
        /** @noinspection PhpUndefinedFieldInspection */
        $couponOwner = Auth::user()->couponOwner;
        $sending = $couponOwner->sendingCoupon()->select(DB::raw('"sending" as transaction, receiver_id as academic_id,0 as money'), 'created_at', ...MealPlanPeriodEnum::names());
        $receiving = $couponOwner->receivingCoupon()->select(DB::raw(' "receiving" as transaction,sender_id as academic_id,0 as money'), 'created_at', ...MealPlanPeriodEnum::names());
        $buying = $couponOwner->purchaseCoupon()->select(DB::raw(' "buying" as transaction,0 as academic_id,money/100 as money'), 'created_at', ...MealPlanPeriodEnum::names());
        $using = $couponOwner->usageCoupon()->select(DB::raw('"using" as transaction,period as academic_id,0 as money'), 'created_at', DB::raw('0 as BREAKFAST,0 as `LUNCH`,0 as `DINNER`'));
        $transactions = $sending->union($receiving)->union($buying)->union($using)->orderByDesc('created_at')->get();
        return view('couponOwner.index', compact('couponOwner', 'transactions'));

    }
}
