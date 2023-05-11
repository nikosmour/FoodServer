
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
            {{route('document.store',$cardApplication)}}
        <card-application-edit-form v-bind:url="'{{route('cardApplication.update',$cardApplication)}}'"
{{--                                    v-bind:url-doc="'{{route('document.store',$cardApplication)}}'",--}}
                                    v-bind:doc-files="{{$files}}">
        </card-application-edit-form>
    </div>
@endsection
