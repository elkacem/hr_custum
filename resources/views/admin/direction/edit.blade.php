@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5>{{ $pageTitle }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.compte.update', $compte) }}" method="POST">
                        @csrf @method('PUT')
{{--                        <x-form.input name="name" label="Nom" value="{{ $compte->name }}" required />--}}
{{--                        <x-form.input name="code" label="Code" value="{{ $compte->code }}" />--}}
                        <button class="btn btn-success w-100 mt-3">Mettre à jour</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
