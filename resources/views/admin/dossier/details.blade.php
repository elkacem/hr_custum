{{--@extends('admin.layouts.app')--}}

{{--@section('panel')--}}
{{--    <div class="row mb-none-30 justify-content-center">--}}
{{--        <div class="col-xl-6 col-md-8 mb-30">--}}
{{--            <div class="card overflow-hidden box--shadow1">--}}
{{--                <div class="card-body">--}}
{{--                    <h5 class="mb-20 text-muted">@lang('Détails du dossier')</h5>--}}
{{--                    <ul class="list-group">--}}
{{--                        <li class="list-group-item d-flex justify-content-between">--}}
{{--                            <span>@lang('ID')</span>--}}
{{--                            <span class="fw-bold">{{ $dossier->id }}</span>--}}
{{--                        </li>--}}
{{--                        <li class="list-group-item d-flex justify-content-between">--}}
{{--                            <span>@lang('Date Engagement')</span>--}}
{{--                            <span class="fw-bold">{{ showDateTime($dossier->engagement_date) }}</span>--}}
{{--                        </li>--}}
{{--                        <li class="list-group-item d-flex justify-content-between">--}}
{{--                            <span>@lang('Fournisseur')</span>--}}
{{--                            <span class="fw-bold">{{ $dossier->fournisseur }}</span>--}}
{{--                        </li>--}}
{{--                        <li class="list-group-item d-flex justify-content-between">--}}
{{--                            <span>@lang('Réf Facture')</span>--}}
{{--                            <span class="fw-bold">{{ $dossier->ref_facture ?? '-' }}</span>--}}
{{--                        </li>--}}
{{--                        <li class="list-group-item d-flex justify-content-between">--}}
{{--                            <span>@lang('Date Facture')</span>--}}
{{--                            <span class="fw-bold">{{ $dossier->date_facture ? showDateTime($dossier->date_facture) : '-' }}</span>--}}
{{--                        </li>--}}
{{--                        <li class="list-group-item d-flex justify-content-between">--}}
{{--                            <span>@lang('Montant HT')</span>--}}
{{--                            <span class="fw-bold">{{ showAmount($dossier->montant_ht) }}</span>--}}
{{--                        </li>--}}
{{--                        <li class="list-group-item d-flex justify-content-between">--}}
{{--                            <span>@lang('TVA')</span>--}}
{{--                            <span class="fw-bold">{{ $dossier->taux_tva }} %</span>--}}
{{--                        </li>--}}
{{--                        <li class="list-group-item d-flex justify-content-between">--}}
{{--                            <span>@lang('Montant TTC')</span>--}}
{{--                            <span class="fw-bold">{{ showAmount($dossier->montant_ttc) }}</span>--}}
{{--                        </li>--}}
{{--                        <li class="list-group-item d-flex justify-content-between">--}}
{{--                            <span>@lang('Observation')</span>--}}
{{--                            <span class="fw-bold">{{ $dossier->observation ?? '-' }}</span>--}}
{{--                        </li>--}}
{{--                        <li class="list-group-item d-flex justify-content-between">--}}
{{--                            <span>@lang('Statut')</span>--}}
{{--                            <span class="fw-bold">{!! $dossier->status !!}</span>--}}
{{--                        </li>--}}
{{--                        --}}{{-- Workflow --}}
{{--                        <li class="list-group-item d-flex justify-content-between">--}}
{{--                            <span>@lang('Étape Actuelle')</span>--}}
{{--                            <span class="fw-bold">--}}
{{--                            @if($dossier->approval_step == 0) ⏳ En attente chef service--}}
{{--                                @elseif($dossier->approval_step == 1) ⏳ En attente chef département--}}
{{--                                @elseif($dossier->approval_step == 2) ⏳ En attente de l'autre structure--}}
{{--                                @elseif($dossier->approval_step == 3) ✅ Complètement approuvé--}}
{{--                                @endif--}}
{{--                        </span>--}}
{{--                        </li>--}}
{{--                        @if($dossier->status === 'REJECTED')--}}
{{--                            <li class="list-group-item">--}}
{{--                                <strong>@lang('Rejeté par')</strong> : {{ optional($dossier->rejectedByUser)->name }}--}}
{{--                                <br>--}}
{{--                                <strong>@lang('Raison')</strong> : {{ $dossier->rejection_reason }}--}}
{{--                            </li>--}}
{{--                        @endif--}}
{{--                    </ul>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--@endsection--}}

@php $user = auth()->guard('admin')->user(); @endphp

@extends('admin.layouts.app')

@section('panel')
    <div class="row mb-none-30 justify-content-center">
        {{-- Left side: dossier info --}}
        <div class="col-xl-6 col-md-8 mb-30">
            <div class="card overflow-hidden box--shadow1">
                <div class="card-body">
                    <h5 class="mb-20 text-muted">@lang('Détails du dossier')</h5>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>@lang('ID')</span>
                            <span class="fw-bold">{{ $dossier->id }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>@lang('Date Engagement')</span>
                            <span class="fw-bold">{{ showDateTime($dossier->engagement_date) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>@lang('Fournisseur')</span>
                            <span class="fw-bold">{{ $dossier->fournisseur }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>@lang('Montant TTC')</span>
                            <span class="fw-bold">{{ showAmount($dossier->montant_ttc) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>@lang('Statut')</span>
                            <span class="fw-bold">{!! $dossier->status !!}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>@lang('Étape Actuelle')</span>
                            <span class="fw-bold">
                            @if($dossier->approval_step == 0) ⏳ En attente Chef Service
                                @elseif($dossier->approval_step == 1) ⏳ En attente Chef Département
                                @elseif($dossier->approval_step == 2) ⏳ En attente Autre Structure
                                @elseif($dossier->approval_step == 3) ✅ Complètement approuvé
                                @endif
                        </span>
                        </li>
{{--                        @if($dossier->status === 'REJECTED')--}}
{{--                            <li class="list-group-item">--}}
{{--                                <strong>@lang('Rejeté par')</strong> : {{ optional($dossier->rejectedByUser)->name }}<br>--}}
{{--                                <strong>@lang('Raison')</strong> : {{ $dossier->rejection_reason }}--}}
{{--                            </li>--}}
{{--                        @endif--}}
                        @if($dossier->status === 'REJECTED')
                            <li class="list-group-item">
                                <strong>@lang('Rejeté par')</strong> :
                                {{ optional($dossier->rejectedByAdmin)->name }}
                                <br>
                                <strong>@lang('Raison')</strong> : {{ $dossier->rejection_reason }}
                            </li>
                        @endif

                        @if(strtoupper($dossier->status) === 'REJECTED' && in_array($user->role, ['admin','payment_service']))
                            <form action="{{ route('admin.dossiers.resubmit', $dossier->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline--success">
                                    <i class="las la-redo"></i> @lang('Resubmit for Review')
                                </button>
                            </form>
                        @endif



                    </ul>
                </div>
            </div>
        </div>

        {{-- Right side: Approval actions --}}
        @if($dossier->status === 'PENDING'
            && $user
            && \App\Models\Dossier::APPROVAL_STEPS[$dossier->approval_step] === $user->role)
            <div class="col-xl-6 col-md-8 mb-30">
                <div class="card overflow-hidden box--shadow1">
                    <div class="card-body">
                        <h5 class="card-title border-bottom pb-2">@lang('Actions d\'approbation')</h5>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <form action="{{ route('admin.dossiers.approve', $dossier->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline--success btn-sm ms-1">
                                        <i class="las la-check"></i> @lang('Approve')
                                    </button>
                                </form>

                                <button class="btn btn-outline--danger btn-sm ms-1"
                                        data-bs-toggle="modal" data-bs-target="#rejectModal">
                                    <i class="las la-ban"></i> @lang('Reject')
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Reject Modal --}}
    <div id="rejectModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Reject Dossier Confirmation')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.dossiers.reject', $dossier->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>@lang('Are you sure you want to reject this dossier?')</p>
                        <div class="form-group">
                            <label class="mt-2">@lang('Reason for Rejection')</label>
                            <textarea name="reason" maxlength="255" class="form-control" rows="5" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Factures Section --}}
    <div class="row mt-4">
        <div class="col-xl-12 mb-30">
            <div class="card overflow-hidden box--shadow1">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>@lang('Factures')</h5>
                    <a href="{{ route('admin.dossiers.factures.create', $dossier->id) }}"
                       class="btn btn-sm btn-outline--primary">
                        <i class="las la-plus"></i> @lang('Ajouter Facture')
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($dossier->factures->count())
                        <div class="table-responsive">
                            <table class="table table--light style--two mb-0">
                                <thead>
                                <tr>
                                    <th>@lang('Réf')</th>
                                    <th>@lang('Date')</th>
                                    <th>@lang('Montant HT')</th>
                                    <th>@lang('TVA')</th>
                                    <th>@lang('Montant TTC')</th>
                                    <th>@lang('Actions')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($dossier->factures as $facture)
                                    <tr>
                                        <td>{{ $facture->ref_facture }}</td>
                                        <td>{{ showDateTime($facture->date_facture, 'd/m/Y') }}</td>
                                        <td>{{ showAmount($facture->montant_ht) }}</td>
                                        <td>{{ $facture->taux_tva }}%</td>
                                        <td>{{ showAmount($facture->montant_ttc) }}</td>
                                        <td>
                                            <div class="button--group">
                                                <a href="{{ route('admin.dossiers.factures.edit', [$dossier->id, $facture->id]) }}"
                                                   class="btn btn-sm btn-outline--primary">
                                                    <i class="la la-pencil"></i> @lang('Edit')
                                                </a>
                                                <form action="{{ route('admin.dossiers.factures.destroy', [$dossier->id, $facture->id]) }}"
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline--danger confirmationBtn"
                                                            data-question="@lang('Are you sure to delete this facture?')">
                                                        <i class="la la-trash"></i> @lang('Delete')
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted p-3 mb-0">@lang('Aucune facture pour ce dossier')</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-xl-12 mb-30">
    @if($dossier->rejections->count())
        <h5 class="mt-4">@lang('Historique des Rejets')</h5>
        <table class="table table--light style--two">
            <thead>
            <tr>
                <th>@lang('Step')</th>
                <th>@lang('Rejected By')</th>
                <th>@lang('Role')</th>
                <th>@lang('Reason')</th>
                <th>@lang('Date')</th>
            </tr>
            </thead>
            <tbody>
            @foreach($dossier->rejections as $rejection)
                <tr>
                    <td>{{ $rejection->step }}</td>
                    <td>{{ $rejection->admin->name ?? 'N/A' }}</td>
                    <td>{{ $rejection->role }}</td>
                    <td>{{ $rejection->reason }}</td>
                    <td>{{ showDateTime($rejection->created_at) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
        </div>
        </div>
@endsection

