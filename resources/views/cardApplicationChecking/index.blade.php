@extends('layouts.nav')
@section('title')
    cardApplicationChecking
@endsection
@section('content')
    <div class="container col">
        @include('components.modelToTable',compact('models')))
    </div>
    <div class="container col">
        @include('components.modelToTable',compact('models')))
    </div>

@endsection
