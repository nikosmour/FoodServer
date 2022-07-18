@extends('layouts.nav')
@section('title')
    coupons transactions
@endsection

@section('content')
    <div class="container">
        @include('components.couponOwner.balance')
        {{($temp=$couponOwner) ?'' : ''}}
        <div>
            <table class="table text-center  table-hover table-col-to-row-sm caption-top">
                <caption>{{ __('transactions') }}</caption>
                <thead class="thead-dark">
                <tr>
                    <th scope="col">{{__('Category')}}</th>
                    <th scope="col">{{__('Comments')}}</th>
                    <th scope="col">{{__('Money')}}</th>
                    <th scope="col">{{__('Breakfast')}}</th>
                    <th scope="col">{{__('Lunch')}}</th>
                    <th scope="col">{{__('Dinner')}}</th>
                    <th scope="col">{{__('Date')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($transactions as $transaction)
                    <tr>
                        <th scope="row">
                            {{__($transaction->transaction)}}
                        </th>
                        <td>{{ $transaction->money ? : ''}}
                            @foreach(\App\Enum\MealPlanPeriodEnum::toArray() as $meal=>$value)
                                {{--                                check if transaction is using in academic_id has a $value --}}
                                {{__($meal)}}: {{($transaction->academic_id==$value)?1:$transaction[$meal]}}
                            @endforeach
                            @if($transaction->transaction=='receiving')
                                {{__('Sender').': '.$transaction->academic_id}}</td>
                        <td>{{ $temp->money}}{{($temp->money-=$transaction->money)?'':'' }} €</td>
                        @foreach(\App\Enum\MealPlanPeriodEnum::names() as $meal)
                            <td>{{$temp[$meal]}}{{($temp[$meal]-=$transaction[$meal])?'':''}}</td>
                            @endforeach
                            @elseif($transaction->transaction=='sending')
                                {{__('Receiver').': '.$transaction->academic_id}}</td>
                            <td>{{ $temp->money}}{{($temp->money+=$transaction->money)?'':'' }} €</td>
                            @foreach(\App\Enum\MealPlanPeriodEnum::names() as $meal)
                                <td>{{$temp[$meal]}}{{($temp[$meal]+=$transaction[$meal])?'':''}}</td>
                                @endforeach
                                @elseif($transaction->transaction=='buying')
                                    </td>
                                <td>{{ $temp->money}}{{($temp->money+=$transaction->money)?'':'' }} €</td>
                                @foreach(\App\Enum\MealPlanPeriodEnum::names() as $meal)
                                    <td>{{$temp[$meal]}}{{($temp[$meal]-=$transaction[$meal])?'':''}}</td>
                                    @endforeach
                                    @elseif($transaction->transaction=='using')
                                        </td>
                                    <td>{{ $temp->money}} €</td>
                                    @foreach(\App\Enum\MealPlanPeriodEnum::toArray() as $meal=>$value)
                                        <td>{{$temp[$meal]}}{{($temp[$meal]+=($value==$transaction->academic_id)?1:0)?'':''}}</td>
                                    @endforeach
                                    @endif
                                    <td>{{$transaction->created_at}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
