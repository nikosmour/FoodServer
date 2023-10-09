@extends('layouts.nav')
@section('title')
    cardApplicationChecking
@endsection
@section('content')
    <card-application-checking :url="'{{route('cardApplication.index')}}'" v-bind:items ="{{$models}}"
    />
    <!--    <div class="container col">
        @include('components.modelToTable',compact('models')))
    </div>
<div class="container col">
    @include('components.modelToTable',compact('models')))
</div>-->

@endsection
