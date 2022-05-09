<?php

namespace App\Http\Controllers;

use App\Enum\UserAbilityEnum;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CardHistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:academics');
        $this->middleware('ability:' . UserAbilityEnum::CARD_OWNERSHIP->name);;
    }

    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return Application|Factory|\Illuminate\Contracts\View\View|View
     */
    public function __invoke(Request $request): Application|Factory|\Illuminate\Contracts\View\View|View
    {
        $cardApplicant = Auth::user()->cardApplicant()->with('usageCard')->first();
        return view('cardApplicant.index', compact('cardApplicant'));

    }
}
