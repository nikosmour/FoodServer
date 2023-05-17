
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
        <p>Your Application status is {{$cardApplication->status}}</p>
        <card-application-edit-form v-bind:url="'{{route('cardApplication.update',$cardApplication)}}'"
{{--                                    v-bind:url-doc="'{{route('document.store',$cardApplication)}}'",--}}
                                    v-bind:doc-files="{{$files}}"
                                    v-bind:application-edit='{{in_array($cardApplication->status,[\App\Enum\CardStatusEnum::TEMPORARY_SAVED, \App\Enum\CardStatusEnum::INCOMPLETE])? 'true' : 'false'}}'
        >
        </card-application-edit-form>
    </div>
@endsection
