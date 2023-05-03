<?php

namespace App\Http\Controllers;

use App\Enum\UserAbilityEnum;
use App\Http\Requests\StoreCardApplicationRequest;
use App\Http\Requests\UpdateCardApplicationRequest;
use App\Models\CardApplication;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CardApplicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:academics');
        $this->middleware('ability:' . UserAbilityEnum::CARD_OWNERSHIP->name);

    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $user = Auth::user();
        $user->cardApplicant->address;
        $models = [$user];
        $caption = 'User info';
        return view('cardApplicant/index', compact('models', 'caption'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCardApplicationRequest $request
     * @return Response
     */
    public function store(StoreCardApplicationRequest $request)
    {
        $cardApplication= new CardApplication();
        $cardApplication->academic_id= Auth::user()->cardApplicant->academic_id;
        $cardApplication->expiration_date= date('Y-m-d', strtotime('-1 day'));
        $cardApplication->save();
        return redirect(route('cardApplication.show', compact("cardApplication")));
    }

    /**
     * Display the specified resource.
     *
     * @param CardApplication $cardApplication
     * @return Response
     */
    public function show(CardApplication $cardApplication)
    {
        dd($cardApplication);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param CardApplication $cardApplication
     * @return Response
     */
    public function edit(CardApplication $cardApplication)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCardApplicationRequest $request
     * @param CardApplication $cardApplication
     * @return Response
     */
    public function update(UpdateCardApplicationRequest $request, CardApplication $cardApplication)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CardApplication $cardApplication
     * @return Response
     */
    public function destroy(CardApplication $cardApplication)
    {
        //
    }
}
