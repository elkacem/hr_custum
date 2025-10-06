{{--@extends('admin.layouts.app')--}}
{{--@section('panel')--}}
{{--    <div class="row">--}}
{{--        <div class="col-lg-12">--}}
{{--            <div class="card">--}}
{{--                <div class="card-body p-0">--}}
{{--                    <div class="table-responsive--md  table-responsive">--}}
{{--                        <table class="table table--light style--two">--}}
{{--                            <thead>--}}
{{--                                <tr>--}}
{{--                                    <th>@lang('Name')</th>--}}
{{--                                    <th>@lang('Brand')</th>--}}
{{--                                    <th>@lang('Seat Type')</th>--}}
{{--                                    <th>@lang('Price')</th>--}}
{{--                                    <th>@lang('Model')</th>--}}
{{--                                    <th>@lang('Matriculation')</th>--}}
{{--                                    <th>@lang('Transmission')</th>--}}
{{--                                    <th>@lang('Status')</th>--}}
{{--                                    <th>@lang('Actions')</th>--}}
{{--                                </tr>--}}
{{--                            </thead>--}}
{{--                            <tbody>--}}
{{--                            @forelse ($vehicles as $vehicle)--}}
{{--                                <tr>--}}
{{--                                    <td>{{ __($vehicle->name) }}</td>--}}
{{--                                    <td>{{ __($vehicle->brand->name) }}</td>--}}
{{--                                    <td>{{ __($vehicle->seater->number) }} @lang('Seater')</td>--}}
{{--                                    <td>{{ showAmount($vehicle->price) }}</strong></td>--}}
{{--                                    <td>{{ __($vehicle->model) }}</td>--}}
{{--                                    <td>{{ __($vehicle->matriculation) }}</td>--}}
{{--                                    <td>{{ __($vehicle->transmission) }}</td>--}}
{{--                                    <td>  @php echo $vehicle->statusBadge;  @endphp </td>--}}
{{--                                    <td>--}}
{{--                                        <div class="button--group">--}}
{{--                                            <a href="{{ route('admin.vehicles.edit', $vehicle->id) }}" class="btn btn-sm btn-outline--primary">--}}
{{--                                                <i class="la la-pencil"></i>@lang('Edit')--}}
{{--                                            </a>--}}
{{--                                            @if ($vehicle->status == Status::DISABLE)--}}
{{--                                                <button class="btn btn-sm btn-outline--success ms-1 confirmationBtn" data-action="{{ route('admin.vehicles.status', $vehicle->id) }}" data-question="@lang('Are you sure to enable this dossier')?" type="button">--}}
{{--                                                    <i class="la la-eye"></i> @lang('Enable')--}}
{{--                                                </button>--}}
{{--                                            @else--}}
{{--                                                <button class="btn btn-sm btn-outline--danger confirmationBtn" data-action="{{ route('admin.vehicles.status', $vehicle->id) }}" data-question="@lang('Are you sure to disable this dossier')?" type="button">--}}
{{--                                                    <i class="la la-eye-slash"></i> @lang('Disable')--}}
{{--                                                </button>--}}
{{--                                            @endif--}}
{{--                                        </div>--}}
{{--                                    </td>--}}

{{--                                </tr>--}}
{{--                            @empty--}}
{{--                                <tr>--}}
{{--                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>--}}
{{--                                </tr>--}}
{{--                            @endforelse--}}
{{--                            </tbody>--}}
{{--                        </table><!-- table end -->--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                @if($vehicles->haspages())--}}
{{--                <div class="card-footer">--}}
{{--                    {{ paginateLinks($vehicles) }}--}}
{{--                </div>--}}
{{--                @endif--}}
{{--            </div><!-- card end -->--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    <x-confirmation-modal />--}}
{{--    <h4 class="text-center">Les dossiers sont en cours de développement.</h4>--}}
{{--@endsection--}}

{{--@push('breadcrumb-plugins')--}}
{{--    <x-search-form placeholder="Name/brand/model" />--}}
{{--    <a href="{{ route('admin.vehicles.add') }}" class="btn btn-outline--primary"><i class="las la-plus"></i>@lang('Add New')</a>--}}
{{--@endpush--}}

@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>@lang('ID')</th>
                                <th>@lang('Date Engagement')</th>
                                <th>@lang('Fournisseur')</th>
                                <th>@lang('Montant TTC')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Actions')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($dossiers as $dossier)
                                <tr>
                                    <td>{{ $dossier->id }}</td>
                                    <td>{{ showDateTime($dossier->engagement_date, 'd/m/Y') }}</td>
                                    <td>{{ $dossier->fournisseur }}</td>
                                    <td>{{ showAmount($dossier->montant_ttc) }}</td>
                                    <td>{!! $dossier->statusBadge !!}</td>
{{--                                    <td>--}}
{{--                                        <a href="{{ route('admin.dossiers.details', $dossier->id) }}"--}}
{{--                                           class="btn btn-sm btn-outline--primary ms-1">--}}
{{--                                            <i class="la la-desktop"></i> @lang('Details')--}}
{{--                                        </a>--}}
{{--                                    </td>--}}
{{--                                    <td>--}}
{{--                                        <div class="button--group">--}}
{{--                                            <a href="{{ route('admin.dossiers.edit', $dossier->id) }}" class="btn btn-sm btn-outline--primary">--}}
{{--                                                <i class="la la-pencil"></i>@lang('Edit')--}}
{{--                                            </a>--}}
{{--                                            <button class="btn btn-sm btn-outline--danger ms-1 confirmationBtn"--}}
{{--                                                    data-action="{{ route('admin.dossiers.delete', $dossier->id) }}"--}}
{{--                                                    data-question="@lang('Are you sure to delete this dossier')?"--}}
{{--                                                    type="button">--}}
{{--                                                <i class="la la-trash"></i> @lang('Delete')--}}
{{--                                            </button>--}}
{{--                                        </div>--}}
{{--                                    </td>--}}
                                    <td>
                                        <div class="button--group">
                                            <a href="{{ route('admin.dossiers.details', $dossier->id) }}"
                                               class="btn btn-sm btn-outline--primary">
                                                <i class="la la-desktop"></i> @lang('Details')
                                            </a>
                                            <a href="{{ route('admin.dossiers.edit', $dossier->id) }}"
                                               class="btn btn-sm btn-outline--primary">
                                                <i class="la la-pencil"></i>@lang('Edit')
                                            </a>
                                            <button class="btn btn-sm btn-outline--danger ms-1 confirmationBtn"
                                                    data-action="{{ route('admin.dossiers.delete', $dossier->id) }}"
                                                    data-question="@lang('Are you sure to delete this dossier')?"
                                                    type="button">
                                                <i class="la la-trash"></i> @lang('Delete')
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse
                            </tbody>

                        </table><!-- table end -->
                    </div>
                </div>

                @if($dossiers->hasPages())
                    <div class="card-footer">
                        {{ paginateLinks($dossiers) }}
                    </div>
                @endif
            </div><!-- card end -->
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Fournisseur / Date" />
    <a href="{{ route('admin.dossiers.create') }}" class="btn btn-outline--primary">
        <i class="las la-plus"></i>@lang('Add New')
    </a>
@endpush

