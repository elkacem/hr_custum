@extends('template.layouts.app')
@section('panel')
    @include('template.partials.header')

    @if (!request()->routeIs('home'))
        @include('template.partials.breadcrumb')
    @endif

    @yield('content')

    @include('template.partials.footer')
@endsection
