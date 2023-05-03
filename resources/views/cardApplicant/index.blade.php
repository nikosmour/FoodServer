@extends('layouts.nav')
@section('title')
    cardApplicant
@endsection
@section('content')
    <div class="container">
        @include('components.cardApplicant.info',['models'=>$models,'caption'=>'cardApplicant'])
        <form id="accept-form" action="{{ route('cardApplication.store') }}" method="POST" >
            @csrf
            <button>Accept</button>
        </form>
    </div>
@endsection
