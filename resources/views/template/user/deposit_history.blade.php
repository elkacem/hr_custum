@extends( 'template.layouts.master')
@section('content')
    <div class="pb-60 pt-60">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <form>
                    <div class="mb-3 search-inner-form">
                        <div class="input-group">
                            <input class="form-control form--control" name="search" type="search" value="{{ request()->search }}" placeholder="@lang('Search by trx|plan|dossier')">
                            <button class="input-group-text bg--base text-white"><i class="las la-search"></i></button>
                        </div>
                    </div>
                </form>
                <div class="card custom--card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            @include('template.partials.payment_table')
                        </div>
                    </div>
                    @if ($deposits->hasPages())
                        <div class="card-footer">
                            {{ paginateLinks($deposits) }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
