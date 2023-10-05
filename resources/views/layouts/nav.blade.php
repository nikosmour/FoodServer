@extends('layouts.app')
@section('title')
    {{ config('app.name', 'Laravel') }}
@endsection
@section('nav_list')
    @auth('academics')
        <li class="nav-item dropdown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle {{
    Route::is('card.history') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true"
               aria-expanded="false" v-pre>
                {{ __('student.nav.Free Food')  }} <span class="caret"></span>
            </a>

            <div class="dropdown-menu dropdown-menu-left" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{route('card.history')}}">
                    {{ __('student.nav.History') }}
                </a>
                <a class="dropdown-item" href="{{route('cardApplication.index')}}">
                    {{ __('student.nav.Request') }}
                </a>
            </div>
        </li>

        <li class="nav-item dropdown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle {{
    Route::is('coupons.history','coupons.transfer.create') ? 'active' : '' }}" href="#" role="button"
               data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                {{ __('student.nav.Coupons')  }} <span class="caret"></span>
            </a>

            <div class="dropdown-menu dropdown-menu-left" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{route('coupons.history')}}">
                    {{ __('student.nav.History') }}
                </a>
                <a class="dropdown-item" href="{{route('coupons.transfer.create')}}">
                    {{ __('student.nav.New Transaction') }}
                </a>
            </div>
        </li>
    @elseauth('entryStaffs')
        <li class="nav-item {{ Route::is('entryChecking.create') ? 'active' : '' }}">
            <a class="nav-link " href="{{ route('entryChecking.create')}}">{{ __('entryChecking.create') }}</a>
        </li>
    @elseauth('couponStaffs')
        <li class="nav-item {{ Route::is('coupons.purchase.create') ? 'active' : '' }}">
            <a class="nav-link " href="{{ route('coupons.purchase.create')}}">{{ __('coupons.purchase.create') }}</a>
        </li>
    @elseauth('cardApplicationStaffs')
        @foreach(\App\Enum\CardStatusEnum::values() as $cardStatus )
            <li class="nav-item {{ Route::is('cardApplication.checking.index', ['category'=>$cardStatus]) ? 'active' : '' }}">
                <a class="nav-link " href="{{ route('cardApplication.checking.index', ['category'=>$cardStatus])}}">{{ __($cardStatus) }}</a>
            </li>
        @endforeach
    @endauth
    <li class="nav-item {{ Route::is('mealPlan.*') ? 'active' : '' }}">
        <a class="nav-link " href="{{ route('mealPlan.index')}}">{{ __('student.nav.Program Food') }}</a>
    </li>
    <!--    <li class="nav-item {{ Route::is('contact.*') ? 'active' : '' }}">
        <a class="nav-link" href="https://www.upatras.gr/el/node/5585" target="_blank">{{ __('student.nav.Contact') }}</a>
    </li>-->

@endsection

