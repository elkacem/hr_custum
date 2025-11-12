@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5>{{ $pageTitle }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.supplier.store') }}" method="POST">
                        @csrf
                        <x-form.input name="code" label="Code" required />
                        <x-form.input name="name" label="Nom" required />
                        <x-form.input name="email" label="Email" />
                        <x-form.input name="phone" label="Téléphone" />
                        <x-form.input name="address" label="Adresse" />
                        <button class="btn btn-primary w-100 mt-3">Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
