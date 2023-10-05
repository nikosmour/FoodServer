<?php

namespace App\Http\Controllers;

use App\Enum\CardStatusEnum;
use App\Enum\UserAbilityEnum;
use App\Http\Requests\StoreCardApplicationCheckingRequest;
use App\Http\Requests\UpdateCardApplicationCheckingRequest;
use App\Models\CardApplication;
use App\Models\CardApplicationChecking;
use Illuminate\Http\Response;

class CardApplicationCheckingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:cardApplicationStaffs');
        $this->middleware('ability:' . UserAbilityEnum::CARD_APPLICATION_CHECK->name);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(CardStatusEnum $category)
    {
        $cardApplications =CardApplication::whereStatus($category)->select('id','status')->get();
        $models = $cardApplications;
        $caption = 'Card Applications -> '.$category->value;
        return view('cardApplicationChecking.index', compact('models', 'caption'));
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
     * @param StoreCardApplicationCheckingRequest $request
     * @return Response
     */
    public function store(StoreCardApplicationCheckingRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param CardApplicationChecking $cardApplicationChecking
     * @return Response
     */
    public function show(CardApplicationChecking $cardApplicationChecking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param CardApplicationChecking $cardApplicationChecking
     * @return Response
     */
    public function edit(CardApplicationChecking $cardApplicationChecking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCardApplicationCheckingRequest $request
     * @param CardApplicationChecking $cardApplicationChecking
     * @return Response
     */
    public function update(UpdateCardApplicationCheckingRequest $request, CardApplicationChecking $cardApplicationChecking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CardApplicationChecking $cardApplicationChecking
     * @return Response
     */
    public function destroy(CardApplicationChecking $cardApplicationChecking)
    {
        //
    }
}
