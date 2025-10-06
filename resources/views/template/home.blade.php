@extends('template.layouts.frontend')

@section('content')
    @include('template.sections.banner')
    @if (@$sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include('template.sections.' . $sec)
        @endforeach
    @endif
@endsection
