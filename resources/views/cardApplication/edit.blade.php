
@extends('layouts.nav')

@section('content')
    <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <card-application-edit-form url="{{route('cardApplication.update',$cardApplication)}}"></card-application-edit-form>
    </div>
@endsection
