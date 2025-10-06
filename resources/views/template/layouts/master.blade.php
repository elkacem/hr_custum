@extends('template.layouts.app')
@section('panel')
    @include('template.partials.header')

    @include('template.partials.breadcrumb')

    <main class="dashboard-section bg--section pt-60 pb-60">
        <div class="container">

            @yield('content')

        </div>
    </main>

    @include('template.partials.footer')
@endsection
