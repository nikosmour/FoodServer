<?php

namespace Tests\Feature;

use App\Models\DailyMealPlan;
use Tests\ControllerTest;

class DailyMealPlanTest extends ControllerTest
{
    protected string $model = DailyMealPlan::class;
    protected string $routeName = 'mealPlan';
    protected string $varName = 'dailyMealPlan';
    protected array $methods = ['index' => 200, 'show' => 200, 'create' => 302, 'edit' => 302];

}
