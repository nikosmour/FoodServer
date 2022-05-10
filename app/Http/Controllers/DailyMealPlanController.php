<?php

namespace App\Http\Controllers;

use App\Enum\MealCategoryEnum;
use App\Enum\MealPlanPeriodEnum;
use App\Enum\UserAbilityEnum;
use App\Http\Requests\StoreDailyMealPlanRequest;
use App\Http\Requests\UpdateDailyMealPlanRequest;
use App\Models\DailyMealPlan;
use App\Models\Meal;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class DailyMealPlanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:entryStaffs,couponStaffs')->except(['index', 'show']);
        $this->middleware('ability:' . UserAbilityEnum::DAILY_MEAL_PLAN_CREATE->name)->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|\Illuminate\Contracts\View\View|View
     */
    public function index(): \Illuminate\Contracts\View\View|Factory|View|Application
    {
        $models = DailyMealPlan::all();
        $caption = 'DailyMealPlan';
        return view('test', compact('models', 'caption'));
    }

    /**
     * Show the form for creating a new resource.
     * @param Request $request
     * @return \Illuminate\Contracts\View\View|Factory|View|Application
     */
    public function create(Request $request): \Illuminate\Contracts\View\View|Factory|View|Application
    {

        $root_name = "store";
        $validatedData = $request->validate([
            'sameas' => 'sometimes|exists:meal_plans,date']);
        if (!isset($request['sameas'])) {
            $dailyMealPlan = [];
            foreach (MealPlanPeriodEnum::names() as $period)
                $dailyMealPlan[strtolower($period)] = [['description' => '', 'category' => collect(MealCategoryEnum::cases())->random()]];
        } else
            $dailyMealPlan = DailyMealPlan::find($validatedData['sameas']);
//        return view('programFood.create',compact('root_name','dailyMealPlan'));
        return view('dailyMealPlan.create', compact('root_name', 'dailyMealPlan'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreDailyMealPlanRequest $request
     * @return RedirectResponse|Redirector|Application
     * @throws Throwable
     */
    public function store(StoreDailyMealPlanRequest $request): Application|RedirectResponse|Redirector
    {
        $validatedData = $request->validated();
        $dailyMealPlan = DB::transaction(function () use ($validatedData) {
            foreach (MealPlanPeriodEnum::values() as $period) {
                $temp = new DailyMealPlan;
                $temp['date'] = $validatedData['date'];
                $temp['period'] = $period;
                $temp->saveOrFail();
            }
            $dailyMealPlan = DailyMealPlan::find($validatedData['date']);
            foreach (MealPlanPeriodEnum::names() as $period) {
                $dailyMealPlan->{strtolower($period)}()->sync(
                    Meal::whereIn(
                        'description', $validatedData[$period]
                    )->select('id')->get()
                );
            }
            return $dailyMealPlan;
        });
        return redirect(route('mealPlan.show', compact("dailyMealPlan")));

    }

    /**
     * Display the specified resource.
     *
     * @param DailyMealPlan $dailyMealPlan
     * @return Application|Factory|\Illuminate\Contracts\View\View|View
     */
    public function show(DailyMealPlan $dailyMealPlan): \Illuminate\Contracts\View\View|Factory|View|Application
    {
        $relation1s = ['breakfast', 'lunch', 'dinner'];
        $models = [$dailyMealPlan];
        foreach ($relation1s as $relation1)
            $relations[] = explode('.', $relation1);

        $caption = $dailyMealPlan->date->toDateString();
        return view('test', compact('models', 'relations', 'caption'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param DailyMealPlan $dailyMealPlan
     * @return \Illuminate\Contracts\View\View|Application|Factory|View
     */
    public function edit(DailyMealPlan $dailyMealPlan): Application|Factory|\Illuminate\Contracts\View\View|View
    {
        $root_name = "update";
        return view('dailyMealPlan.edit', compact('root_name', 'dailyMealPlan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateDailyMealPlanRequest $request
     * @param DailyMealPlan $dailyMealPlan
     * @return RedirectResponse|Redirector|Application
     * @throws Throwable
     */
    public function update(UpdateDailyMealPlanRequest $request, DailyMealPlan $dailyMealPlan): Application|RedirectResponse|Redirector
    {
        $validatedData = $request->validated();
        DB::transaction(function () use ($dailyMealPlan, $validatedData) {
            foreach (MealPlanPeriodEnum::names() as $period) {
                $dailyMealPlan->{strtolower($period)}()->sync(
                    Meal::whereIn(
                        'description', $validatedData[$period]
                    )->select('id')->get()
                );
            }
        });
        return redirect(route('mealPlan.show', compact("dailyMealPlan")));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DailyMealPlan $dailyMealPlan
     * @return Application|Redirector|RedirectResponse
     */
    public function destroy(DailyMealPlan $dailyMealPlan): Application|RedirectResponse|Redirector
    {
        $ids = [
            $dailyMealPlan->breakfast_id,
            $dailyMealPlan->lunch_id,
            $dailyMealPlan->dinner_id,
        ];
        DailyMealPlan::withoutGlobalScopes()->whereIn('id', $ids)->delete();
        return redirect(route('mealPlan.index'));
    }
}
