<?php

namespace App\Http\Middleware;

use App\Enum\UserAbilityEnum;
use Closure;
use Illuminate\Http\Request;

class EnsureUserHasAbility
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string $abilityName
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $abilityName): mixed
    {
        abort_if(!$request->user()->hasAbility(UserAbilityEnum::fromName($abilityName)), 403, 'you don\'t have the ability to access this service');

        return $next($request);
    }

}
