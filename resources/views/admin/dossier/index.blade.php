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

    @php
        function sort_link($col, $label) {
            $dir = request('dir','asc');
            $next = (request('sort')===$col && $dir==='asc') ? 'desc' : 'asc';
            $qs = array_merge(request()->query(), ['sort'=>$col, 'dir'=>$next]);
            $icon = request('sort')===$col ? ($dir==='asc' ? 'la-sort-amount-up' : 'la-sort-amount-down') : 'la-sort';
            return '<a href="'.e(request()->url().'?'.http_build_query($qs)).'">'.$label.' <i class="la '.$icon.'"></i></a>';
        }
    @endphp


    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
{{--                            <thead>--}}
{{--                            <tr>--}}
{{--                                <th>@lang('ID')</th>--}}
{{--                                <th>@lang('Date Engagement')</th>--}}
{{--                                <th>@lang('Fournisseur')</th>--}}
{{--                                <th>@lang('Montant TTC')</th>--}}
{{--                                <th>@lang('Status')</th>--}}
{{--                                <th>@lang('Actions')</th>--}}
{{--                            </tr>--}}
{{--                            </thead>--}}
                            <thead>
                            <tr>
                                <th>{!! sort_link('id', __('ID')) !!}</th>
                                <th>{!! sort_link('engagement_date', __('Date Engagement')) !!}</th>
                                <th>{!! sort_link('fournisseur', __('Fournisseur')) !!}</th>
                                <th>{!! sort_link('montant_ttc', __('Montant TTC')) !!}</th>
                                <th>{!! sort_link('status', __('Status')) !!}</th>
                                <th>@lang('Dossiers')</th>
                                <th>@lang('Actions')</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse ($dossiers as $dossier)
                                <tr>
                                    <td>{{ $dossier->id }}</td>
                                    <td>{{ showDateTime($dossier->engagement_date, 'd/m/Y') }}</td>
                                    <td>{{ optional($dossier->fournisseur)->name ?? '—' }}</td>
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
                                    {{-- NEW: column "Fichiers" --}}
                                    <td>
                                        @if($dossier->attachments_count > 0)
                                            <a href="{{ route('admin.dossiers.details', $dossier->id) }}"
                                               class="btn btn-sm btn-outline--info">
                                                <i class="la la-paperclip"></i>
                                                {{ $dossier->attachments_count }}
                                            </a>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
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

{{--@push('breadcrumb-plugins')--}}
{{--    <x-search-form placeholder="Fournisseur / Date" />--}}
{{--    <a href="{{ route('admin.dossiers.create') }}" class="btn btn-outline--primary">--}}
{{--        <i class="las la-plus"></i>@lang('Add New')--}}
{{--    </a>--}}
{{--@endpush--}}

@push('breadcrumb-plugins')
    <form method="GET" class="d-flex flex-wrap gap-2 align-items-end">
        {{-- Global search (matches many columns via DossierFilters::globalLike) --}}
        <div>
            <label class="form-label mb-0">@lang('Recherche')</label>
            <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                   placeholder="@lang('ID, Fournisseur, Réf facture, …')">
        </div>

        {{-- Status --}}
        <div>
            <label class="form-label mb-0">@lang('Statut')</label>
            <select name="status" class="form-control">
                <option value="">@lang('Tous')</option>
                @foreach(['PENDING'=>'En attente','APPROVED'=>'Approuvé','REJECTED'=>'Rejeté'] as $k=>$v)
                    <option value="{{ $k }}" @selected(request('status')===$k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>

        {{-- Fournisseur --}}
        <div>
            <label class="form-label mb-0">@lang('Fournisseur')</label>
            <select name="fournisseur_id" class="form-control select2">
                <option value="">@lang('Tous')</option>
                @foreach($fournisseurs ?? [] as $f)
                    <option value="{{ $f->id }}" @selected(request('fournisseur_id')==$f->id)>{{ $f->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Date engagement from/to --}}
        <div>
            <label class="form-label mb-0">@lang('Engagement du')</label>
            <input type="date" name="engagement_start" value="{{ request('engagement_start') }}" class="form-control">
        </div>
        <div>
            <label class="form-label mb-0">@lang('au')</label>
            <input type="date" name="engagement_end" value="{{ request('engagement_end') }}" class="form-control">
        </div>

        {{-- TTC min/max --}}
        <div>
            <label class="form-label mb-0">@lang('TTC min')</label>
            <input type="number" step="0.01" name="montant_ttc_min" value="{{ request('montant_ttc_min') }}" class="form-control">
        </div>
        <div>
            <label class="form-label mb-0">@lang('TTC max')</label>
            <input type="number" step="0.01" name="montant_ttc_max" value="{{ request('montant_ttc_max') }}" class="form-control">
        </div>

        {{-- Has facture toggle --}}
        <div>
            <label class="form-label mb-0">@lang('Avec facture ?')</label>
            <select name="has_facture" class="form-control">
                <option value="">@lang('Tous')</option>
                <option value="1" @selected(request('has_facture')==='1')>@lang('Oui')</option>
                <option value="0" @selected(request('has_facture')==='0')>@lang('Non')</option>
            </select>
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn--primary"><i class="la la-filter"></i> @lang('Filtrer')</button>
            <a href="{{ route('admin.dossiers.index') }}" class="btn btn--dark">@lang('Réinitialiser')</a>

            {{-- Excel export (same filters) --}}
{{--            <a href="{{ route('admin.dossiers.export', request()->query()) }}" class="btn btn--success">--}}
{{--                <i class="la la-file-excel"></i> @lang('Exporter Excel')--}}
{{--            </a>--}}

            <a href="{{ route('admin.dossiers.create') }}" class="btn btn-outline--primary">
                <i class="las la-plus"></i>@lang('Add New')
            </a>
        </div>
    </form>
@endpush


