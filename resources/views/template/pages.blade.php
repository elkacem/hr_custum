@extends('template.layouts.frontend')

@section('content')
    @if($sections != null)
        @foreach(json_decode($sections) as $sec)
            @include('template.sections.'.$sec)
        @endforeach
    @endif
@endsection
