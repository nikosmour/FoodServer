<?php

namespace App\Http\Controllers;

use App\Enum\UserAbilityEnum;
use App\Http\Requests\StoreTransferCouponRequest;
use App\Models\TransferCoupon;
use App\Traits\CouponOwnerTrait;
use DB;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;

class TransferCouponController extends Controller
{
    use CouponOwnerTrait;

    public function __construct()
    {
        $this->middleware('auth:academics');
        $this->middleware('ability:' . UserAbilityEnum::COUPON_OWNERSHIP->name);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreTransferCouponRequest $request
     * @return Application|RedirectResponse|Redirector
     */
    public function store(StoreTransferCouponRequest $request): Application|RedirectResponse|Redirector
    {
        $validatedData = $request->validated();
        DB::transaction(function () use ($validatedData) {
            TransferCoupon::create($validatedData);
        });
        return redirect(route('coupons.transfer.create'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|\Illuminate\Contracts\View\View|View
     */
    public function create(): \Illuminate\Contracts\View\View|Factory|View|Application
    {
        $couponOwner = auth()->user();
        return view('couponOwner.send', compact('couponOwner'));
    }
}
