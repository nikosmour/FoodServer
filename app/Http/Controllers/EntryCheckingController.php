<?php

namespace App\Http\Controllers;

use App\Enum\UserAbilityEnum;
use App\Http\Requests\StoreEntryCheckingRequest;
use App\Traits\EntryCheckingTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class EntryCheckingController extends Controller
{
    use EntryCheckingTrait;

    public function __construct()
    {
        $this->middleware('auth:entryStaffs');
        $this->middleware('ability:' . UserAbilityEnum::ENTRY_CHECK->name);


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|\Illuminate\Contracts\View\View|View
     */
    public function create(): \Illuminate\Contracts\View\View|Factory|View|Application
    {
        $statistics = json_encode($this->statisticsStartValues());
        return view('entryChecking.create', compact('statistics'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreEntryCheckingRequest $request
     * @return JsonResponse
     */
    public function store(StoreEntryCheckingRequest $request): JsonResponse
    {
        $data = $request->validated();
        return response()->json(
            DB::transaction(function () use ($data) {
                return $this->canPass($data);
            })
        );
    }
}
