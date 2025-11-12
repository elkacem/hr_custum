@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route('admin.dossiers.store') }}" method="post" class="disableSubmission" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">

                            {{-- ================== ENGAGEMENT ================== --}}
                            <h5 class="mb-3">@lang('Engagement')</h5>

                            <div class="row g-3 align-items-end">
                                {{-- Type de Dossier --}}
                                <div class="col-lg-4">
                                    <label class="form-label" for="type_dossier">@lang('Type de Dossier')</label>
                                    <select name="type_dossier" id="type_dossier" class="form-control" required>
                                        <option value="national" {{ old('type_dossier') == 'national' ? 'selected' : '' }}>@lang('National')</option>
                                        <option value="international" {{ old('type_dossier') == 'international' ? 'selected' : '' }}>@lang('International')</option>
                                    </select>
                                    <div class="form-text">@lang('Choisissez “International” pour activer la conversion devise.')</div>
                                </div>

                                <x-form.date name="engagement_date" label="Date Engagement" col="4" />
                                <x-form.input name="demande_number" label="N° Demande Paiement" col="4" />
                            </div>

                            <hr class="my-4">

                            <div class="row g-4">
                                <div class="col-lg-8">
                                    {{-- Fournisseur / Direction / Condition --}}
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label" for="fournisseur">@lang('Fournisseur')</label>
                                            <div class="d-flex align-items-start gap-2">
                                                <select name="fournisseur_id" id="fournisseur" class="form-control select2" required>
                                                    <option value="">-- @lang('Sélectionner un fournisseur') --</option>
                                                    @foreach($fournisseurs as $fournisseur)
                                                        <option value="{{ $fournisseur->id }}" {{ old('fournisseur') == $fournisseur->id ? 'selected' : '' }}>
                                                            {{ $fournisseur->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <button type="button" class="btn btn-outline--primary btn-sm cuModalBtn"
                                                        data-modal_title="@lang('Ajouter Fournisseur')" data-bs-toggle="modal" data-bs-target="#fournisseurModal">
                                                    <i class="las la-plus"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label" for="direction_id">@lang('Direction Émettrice')</label>
                                            <select name="direction_id" id="direction_id" class="form-control select2" required>
                                                <option value="">-- @lang('Sélectionner une direction') --</option>
                                                @foreach($directions as $direction)
                                                    <option value="{{ $direction->id }}" {{ old('direction_id') == $direction->id ? 'selected' : '' }}>
                                                        {{ $direction->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label" for="condition_paiement">@lang('Condition de Paiement')</label>
                                            <select name="condition_paiement" id="condition_paiement" class="form-control select2" required>
                                                <option value="">-- @lang('Sélectionner une condition') --</option>
                                                @foreach($conditions as $condition)
                                                    <option value="{{ $condition->name }}"
                                                        {{ old('condition_paiement', $dossier->condition_paiement ?? '') == $condition->name ? 'selected' : '' }}>
                                                        {{ $condition->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="card mt-4 shadow-sm border-0" id="dzdCard">
                                        <div class="card-header bg--light-blue">
                                            <strong>@lang('Montants en Dinars (DZD)')</strong>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                {{-- Montant HT (DZD) --}}
                                                <div class="col-md-4">
                                                    <label class="form-label" for="montant_ht">@lang('Montant HT')</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.01" min="0" name="montant_ht" id="montant_ht"
                                                               class="form-control" placeholder="0.00" value="{{ old('montant_ht') }}">
                                                        <span class="input-group-text">DZD</span>
                                                    </div>
                                                    <div class="form-text">@lang('Montant hors taxe en dinars.')</div>
                                                </div>

                                                {{-- TVA (wrap in a section so we can hide for International) --}}
                                                <div class="col-md-4" id="tvaSection">
                                                    <label class="form-label" for="taux_tva">@lang('TVA')</label>
                                                    <select id="taux_tva" name="taux_tva" class="form-control">
                                                        <option value="0"  {{ old('taux_tva') == 0  ? 'selected' : '' }}>0%</option>
                                                        <option value="9"  {{ old('taux_tva') == 9  ? 'selected' : '' }}>9%</option>
                                                        <option value="19" {{ old('taux_tva') == 19 ? 'selected' : '' }}>19%</option>
                                                        <option value="custom" {{ old('taux_tva') == 'custom' ? 'selected' : '' }}>@lang('Autre')</option>
                                                    </select>
                                                    <div class="form-text">@lang('Choisissez un taux de TVA ou “Autre”.')</div>
                                                </div>

                                                {{-- TVA personnalisée --}}
                                                <div class="col-md-4 d-none" id="customTvaContainer">
                                                    <label class="form-label" for="custom_tva">@lang('TVA personnalisée (%)')</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.01" min="0" name="custom_tva" id="custom_tva"
                                                               class="form-control" value="{{ old('custom_tva') }}" placeholder="Ex: 12.50">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                </div>

{{--                                                --}}{{-- Extras nationaux: Remise %, Taxe %, Droit de timbre % --}}
                                                <div class="col-12"></div>
                                                <div class="col-md-4" id="nationalExtras">
                                                    <label class="form-label" for="remise_percent">@lang('Remise (%)')</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.01" min="0" name="remise_percent" id="remise_percent"
                                                               class="form-control" value="{{ old('remise_percent', 0) }}" placeholder="0.00">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                    <div class="form-text">@lang('Réduction appliquée sur le HT.')</div>
                                                </div>

{{--                                                <div class="d-flex justify-content-between mb-2">--}}
{{--                                                    <span>@lang('Remise')</span>--}}
{{--                                                    <strong><span id="sum_remise_rate">0</span>%</strong>--}}
{{--                                                </div>--}}
{{--                                                <div class="d-flex justify-content-between mb-2">--}}
{{--                                                    <span>@lang('Taxe')</span>--}}
{{--                                                    <strong><span id="sum_taxe_rate">0</span>%</strong>--}}
{{--                                                </div>--}}
{{--                                                <div class="d-flex justify-content-between mb-2">--}}
{{--                                                    <span>@lang('Droit de timbre')</span>--}}
{{--                                                    <strong><span id="sum_timbre_rate">0</span>%</strong>--}}
{{--                                                </div>--}}


                                                <div class="col-md-4">
                                                    <label class="form-label" for="taxe_percent">@lang('Taxe (%)')</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.01" min="0" name="taxe_percent" id="taxe_percent"
                                                               class="form-control" value="{{ old('taxe_percent', 0) }}" placeholder="0.00">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                    <div class="form-text">@lang('Taxe appliquée sur le HT après remise.')</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="form-label" for="timbre_percent">@lang('Droit de timbre (%)')</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.01" min="0" name="timbre_percent" id="timbre_percent"
                                                               class="form-control" value="{{ old('timbre_percent', 0) }}" placeholder="0.00">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                    <div class="form-text">@lang('Timbre appliqué sur le HT après remise.')</div>
                                                </div>

                                                {{-- Montant TTC (DZD) --}}
                                                <div class="col-md-4">
                                                    <label class="form-label" for="montant_ttc">@lang('Montant TTC')</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.01" name="montant_ttc" id="montant_ttc"
                                                               class="form-control" readonly>
                                                        <span class="input-group-text">DZD</span>
                                                    </div>
                                                    <div class="form-text">@lang('Calculé automatiquement.')</div>
                                                </div>
                                            </div>
                                        </div>

                                        {{--                                        <div class="card-body">--}}
{{--                                            <div class="row g-3">--}}
{{--                                                --}}{{-- Montant HT (DZD) --}}
{{--                                                <div class="col-md-4">--}}
{{--                                                    <label class="form-label" for="montant_ht">@lang('Montant HT')</label>--}}
{{--                                                    <div class="input-group">--}}
{{--                                                        <input type="number" step="0.01" min="0" name="montant_ht" id="montant_ht"--}}
{{--                                                               class="form-control" placeholder="0.00" value="{{ old('montant_ht') }}">--}}
{{--                                                        <span class="input-group-text">DZD</span>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="form-text">@lang('Montant hors taxe en dinars.')</div>--}}
{{--                                                </div>--}}

{{--                                                --}}{{-- TVA --}}
{{--                                                <div class="col-md-4">--}}
{{--                                                    <label class="form-label" for="taux_tva">@lang('TVA')</label>--}}
{{--                                                    <select id="taux_tva" name="taux_tva" class="form-control">--}}
{{--                                                        <option value="0" {{ old('taux_tva') == 0 ? 'selected' : '' }}>0%</option>--}}
{{--                                                        <option value="9" {{ old('taux_tva') == 9 ? 'selected' : '' }}>9%</option>--}}
{{--                                                        <option value="19" {{ old('taux_tva') == 19 ? 'selected' : '' }}>19%</option>--}}
{{--                                                        <option value="custom" {{ old('taux_tva') == 'custom' ? 'selected' : '' }}>@lang('Autre')</option>--}}
{{--                                                    </select>--}}
{{--                                                    <div class="form-text">@lang('Choisissez un taux de TVA ou “Autre”.')</div>--}}
{{--                                                </div>--}}

{{--                                                --}}{{-- TVA personnalisée --}}
{{--                                                <div class="col-md-4 d-none" id="customTvaContainer">--}}
{{--                                                    <label class="form-label" for="custom_tva">@lang('TVA personnalisée (%)')</label>--}}
{{--                                                    <div class="input-group">--}}
{{--                                                        <input type="number" step="0.01" min="0" name="custom_tva" id="custom_tva"--}}
{{--                                                               class="form-control" value="{{ old('custom_tva') }}" placeholder="Ex: 12.50">--}}
{{--                                                        <span class="input-group-text">%</span>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}

{{--                                                --}}{{-- Montant TTC (DZD) --}}
{{--                                                <div class="col-md-4">--}}
{{--                                                    <label class="form-label" for="montant_ttc">@lang('Montant TTC')</label>--}}
{{--                                                    <div class="input-group">--}}
{{--                                                        <input type="number" step="0.01" name="montant_ttc" id="montant_ttc"--}}
{{--                                                               class="form-control" readonly>--}}
{{--                                                        <span class="input-group-text">DZD</span>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="form-text">@lang('Calculé automatiquement : HT + TVA.')</div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
                                    </div>



                                    <div class="card mt-4 shadow-sm border-0 d-none" id="internationalFields">
                                        <div class="card-header bg--light-blue d-flex justify-content-between align-items-center">
                                            <strong>@lang('Montants en Devise')</strong>
                                            <span class="badge bg-secondary">@lang('Le Dinar est dérivé pour information')</span>
                                        </div>

                                        <div class="card-body">
                                            <div class="row g-3">
                                                {{-- Montant en Devise (initial) --}}
                                                <div class="col-md-4">
                                                    <label class="form-label" for="montant_devise">@lang('Montant en Devise (initial)')</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.01" min="0" name="montant_devise" id="montant_devise"
                                                               class="form-control" placeholder="0.00">
                                                        <button type="button" class="btn btn-outline-secondary" id="resetDeviseFromDzd"
                                                                title="@lang('Réappliquer conversion depuis TTC DZD')">↻</button>
                                                    </div>
                                                    <div class="form-text">@lang('Source de vérité; le DZD est dérivé.')</div>
                                                </div>
                                                {{-- Taux (DZD / 1 unité) --}}
                                                <div class="col-md-4">
                                                    <label class="form-label" for="taux_conversion">@lang('Taux de Conversion')</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.0001" min="0" name="taux_conversion" id="taux_conversion"
                                                               class="form-control" placeholder="Ex: 135.5000">
                                                        <span class="input-group-text">DZD/1</span>
                                                    </div>
                                                    <div class="form-text">@lang('Montant de 1 unité de la devise en DZD.')</div>
                                                </div>

                                                {{-- Devise --}}
                                                <div class="col-md-4">
                                                    <label class="form-label" for="monnaie">@lang('Monnaie')</label>
                                                    <select id="monnaie" name="monnaie" class="form-control">
                                                        <option value="USD" {{ old('monnaie') == 'USD' ? 'selected' : '' }}>💵 USD</option>
                                                        <option value="EUR" {{ old('monnaie') == 'EUR' ? 'selected' : '' }}>💶 EUR</option>
                                                    </select>
                                                </div>

                                                {{-- IBS sur devise --}}
                                                <div class="col-md-4">
                                                    <label class="form-label" for="ibs_percent">@lang('IBS (%) sur Devise')</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.01" min="0" id="ibs_percent" name="ibs_percent"
                                                               class="form-control" value="{{ old('ibs_percent', 0) }}" placeholder="0.00">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                    <div class="form-text">@lang('Retenue appliquée sur le montant en devise.')</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="form-label" for="ibs_devise">@lang('IBS (montant en devise)')</label>
                                                    <input type="number" step="0.01" min="0" id="ibs_devise" class="form-control" readonly>
                                                    <div class="form-text">@lang('Calcul : devise × IBS%.')</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="form-label" for="montant_devise_net">@lang('Devise après IBS')</label>
                                                    <input type="number" step="0.01" min="0" id="montant_devise_net" class="form-control" readonly>
                                                    <div class="form-text">@lang('devise initiale - IBS en devise.')</div>
                                                </div>

                                                {{-- DZD dérivés (affichage uniquement) --}}
                                                <div class="col-md-6">
                                                    <label class="form-label">@lang('TTC (DZD) dérivé')</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.01" min="0" id="ttc_dzd_initial" class="form-control" readonly>
                                                        <span class="input-group-text">DZD</span>
                                                    </div>
                                                    <div class="form-text">@lang('= devise initiale × taux.')</div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">@lang('HT (DZD) après IBS (dérivé)')</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.01" min="0" id="ht_dzd_apres_ibs" class="form-control" readonly>
                                                        <span class="input-group-text">DZD</span>
                                                    </div>
                                                    <div class="form-text">@lang('= devise après IBS × taux.')</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                    {{-- Champs International --}}
{{--                                    <div class="card mt-4 shadow-sm border-0 d-none" id="internationalFields">--}}
{{--                                        <div class="card-header bg--light-blue d-flex justify-content-between align-items-center">--}}
{{--                                            <strong>@lang('Montants en Devise')</strong>--}}
{{--                                            <span class="badge bg-secondary">@lang('Basé sur le TTC (DZD)')</span>--}}
{{--                                        </div>--}}
{{--                                        <div class="card-body">--}}
{{--                                            <div class="row g-3">--}}
{{--                                                --}}{{----}}{{-- Taux (DZD / 1 unité) --}}
{{--                                                <div class="col-md-4">--}}
{{--                                                    <label class="form-label" for="taux_conversion">@lang('Taux de Conversion')</label>--}}
{{--                                                    <div class="input-group">--}}
{{--                                                        <input type="number" step="0.0001" min="0" name="taux_conversion" id="taux_conversion"--}}
{{--                                                               class="form-control" placeholder="Ex: 135.5000">--}}
{{--                                                        <span class="input-group-text">DZD/1</span>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="form-text">@lang('Montant de 1 unité de la devise en DZD.')</div>--}}
{{--                                                </div>--}}

{{--                                                --}}{{----}}{{-- Devise --}}
{{--                                                <div class="col-md-4">--}}
{{--                                                    <label class="form-label" for="monnaie">@lang('Monnaie')</label>--}}
{{--                                                    <select id="monnaie" name="monnaie" class="form-control">--}}
{{--                                                        <option value="USD" {{ old('monnaie') == 'USD' ? 'selected' : '' }}>💵 USD</option>--}}
{{--                                                        <option value="EUR" {{ old('monnaie') == 'EUR' ? 'selected' : '' }}>💶 EUR</option>--}}
{{--                                                    </select>--}}
{{--                                                </div>--}}

{{--                                                --}}{{----}}{{-- Montant en Devise (équiv. TTC) + reset --}}
{{--                                                <div class="col-md-4">--}}
{{--                                                    <label class="form-label" for="montant_devise">@lang('Montant en Devise (équiv. TTC)')</label>--}}
{{--                                                    <div class="input-group">--}}
{{--                                                        <input type="number" step="0.01" min="0" name="montant_devise" id="montant_devise"--}}
{{--                                                               class="form-control" placeholder="0.00">--}}
{{--                                                        <button type="button" class="btn btn-outline-secondary" id="resetDeviseFromDzd"--}}
{{--                                                                title="@lang('Réappliquer conversion depuis TTC DZD')">↻</button>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="form-text">@lang('Auto = TTC (DZD) ÷ Taux. Modifiable au besoin.')</div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="card-body">--}}
{{--                                            <div class="row g-3">--}}
{{--                                                --}}{{-- Taux (DZD / 1 unité) --}}
{{--                                                <div class="col-md-4">--}}
{{--                                                    <label class="form-label" for="taux_conversion">@lang('Taux de Conversion')</label>--}}
{{--                                                    <div class="input-group">--}}
{{--                                                        <input type="number" step="0.0001" min="0" name="taux_conversion" id="taux_conversion"--}}
{{--                                                               class="form-control" placeholder="Ex: 135.5000">--}}
{{--                                                        <span class="input-group-text">DZD/1</span>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="form-text">@lang('Montant de 1 unité de la devise en DZD.')</div>--}}
{{--                                                </div>--}}

{{--                                                --}}{{-- Devise --}}
{{--                                                <div class="col-md-4">--}}
{{--                                                    <label class="form-label" for="monnaie">@lang('Monnaie')</label>--}}
{{--                                                    <select id="monnaie" name="monnaie" class="form-control">--}}
{{--                                                        <option value="USD" {{ old('monnaie') == 'USD' ? 'selected' : '' }}>💵 USD</option>--}}
{{--                                                        <option value="EUR" {{ old('monnaie') == 'EUR' ? 'selected' : '' }}>💶 EUR</option>--}}
{{--                                                    </select>--}}
{{--                                                </div>--}}

{{--                                                --}}{{-- Montant en Devise (initial) + reset --}}
{{--                                                <div class="col-md-4">--}}
{{--                                                    <label class="form-label" for="montant_devise">@lang('Montant en Devise (initial)')</label>--}}
{{--                                                    <div class="input-group">--}}
{{--                                                        <input type="number" step="0.01" min="0" name="montant_devise" id="montant_devise"--}}
{{--                                                               class="form-control" placeholder="0.00">--}}
{{--                                                        <button type="button" class="btn btn-outline-secondary" id="resetDeviseFromDzd"--}}
{{--                                                                title="@lang('Réappliquer conversion depuis TTC DZD')">↻</button>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="form-text">@lang('Base de calcul, la conversion en DZD est dérivée.')</div>--}}
{{--                                                </div>--}}

{{--                                                --}}{{-- IBS sur devise --}}
{{--                                                <div class="col-md-4">--}}
{{--                                                    <label class="form-label" for="ibs_percent">@lang('IBS (%) sur Devise')</label>--}}
{{--                                                    <div class="input-group">--}}
{{--                                                        <input type="number" step="0.01" min="0" id="ibs_percent" name="ibs_percent"--}}
{{--                                                               class="form-control" value="{{ old('ibs_percent', 0) }}" placeholder="0.00">--}}
{{--                                                        <span class="input-group-text">%</span>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="form-text">@lang('Retenue appliquée sur le montant en devise.')</div>--}}
{{--                                                </div>--}}

{{--                                                <div class="col-md-4">--}}
{{--                                                    <label class="form-label" for="ibs_devise">@lang('IBS (montant en devise)')</label>--}}
{{--                                                    <input type="number" step="0.01" min="0" id="ibs_devise" class="form-control" readonly>--}}
{{--                                                    <div class="form-text">@lang('Calcul : devise × IBS%.')</div>--}}
{{--                                                </div>--}}

{{--                                                <div class="col-md-4">--}}
{{--                                                    <label class="form-label" for="montant_devise_net">@lang('Devise après IBS')</label>--}}
{{--                                                    <input type="number" step="0.01" min="0" id="montant_devise_net" class="form-control" readonly>--}}
{{--                                                    <div class="form-text">@lang('devise initiale - IBS en devise.')</div>--}}
{{--                                                </div>--}}

{{--                                                --}}{{-- DZD dérivés (affichage uniquement) --}}
{{--                                                <div class="col-md-6">--}}
{{--                                                    <label class="form-label">@lang('TTC (DZD) dérivé')</label>--}}
{{--                                                    <div class="input-group">--}}
{{--                                                        <input type="number" step="0.01" min="0" id="ttc_dzd_initial" class="form-control" readonly>--}}
{{--                                                        <span class="input-group-text">DZD</span>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="form-text">@lang('= devise initiale × taux.')</div>--}}
{{--                                                </div>--}}

{{--                                                <div class="col-md-6">--}}
{{--                                                    <label class="form-label">@lang('HT (DZD) après IBS (dérivé)')</label>--}}
{{--                                                    <div class="input-group">--}}
{{--                                                        <input type="number" step="0.01" min="0" id="ht_dzd_apres_ibs" class="form-control" readonly>--}}
{{--                                                        <span class="input-group-text">DZD</span>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="form-text">@lang('= devise après IBS × taux.')</div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}

{{--                                    </div>--}}
                                </div>

                                {{-- Résumé (sticky) --}}
                                <div class="col-lg-4 d-none d-lg-block">
                                    <div class="card shadow-sm sticky-summary">
                                        <div class="card-header bg--light-blue">
                                            <strong>@lang('Résumé')</strong>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>@lang('HT (DZD)')</span>
                                                <strong id="sum_ht_dzd">0.00</strong>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2 national-only">
                                                <span>@lang('TVA')</span>
                                                <strong><span id="sum_tva_rate">0</span>%</strong>
                                            </div>

                                            <hr class="my-2">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>@lang('TTC (DZD)')</span>
                                                <strong id="sum_ttc_dzd">0.00</strong>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>@lang('Taux')</span>
                                                <strong><span id="sum_taux">0</span> <small>DZD/1</small></strong>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span>@lang('Montant en Devise')</span>
                                                <strong id="sum_devise">0.00</strong>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2 intl-only d-none">
                                                <span>@lang('Devise après IBS')</span>
                                                <strong id="sum_devise_net">0.00</strong>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h5 class="mt-4 mb-3">@lang('Fichiers du Dossier')</h5>
                            <div class="col-md-9">
                                <button type="button" class="btn btn--dark btn--sm addAttachment my-3">
                                    <i class="fas fa-plus"></i> @lang('Ajouter un fichier')
                                </button>
                                <p class="mb-2">
                                <span class="text--info">
                                    @lang('Max 5 fichiers | Taille max : ' . convertToReadableSize(ini_get('upload_max_filesize')))
                                    <br>@lang('Extensions autorisées : .jpg, .jpeg, .png, .pdf, .doc, .docx')
                                </span>
                                </p>
                                <div class="row fileUploadsContainer"></div>
                            </div>

                            <x-form.input name="periode" label="Périodicité" col="4" />

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>@lang('Dossier Rejeté')</label><br>
                                    <input type="checkbox" id="dossier_rejete" name="dossier_rejete" value="1"
                                        {{ old('dossier_rejete') ? 'checked' : '' }}>
                                </div>
                            </div>

                            {{-- Conteneur des infos de rejet --}}
                            <div class="row d-none" id="rejetContainer">
                                <div class="col-md-3">
                                    <x-form.date name="date_envoi" label="Date Envoi" />
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="rejection_reason">@lang('Raison du rejet')</label>
                                        <textarea name="rejection_reason" id="rejection_reason"
                                                  class="form-control" rows="2">{{ old('rejection_reason') }}</textarea>
                                    </div>
                                </div>
                            </div>

{{--                            <x-form.textarea name="observation" label="Observation" col="12" />--}}
                            <div class="col-md-12 mt-3">
                                <button type="submit" class="btn btn--primary w-100">@lang('Enregistrer')</button>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Fournisseur --}}
    <div class="modal fade" id="fournisseurModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.supplier.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('Ajouter Fournisseur')</h5>
                        <button type="button" class="close" data-bs-dismiss="modal">
                            <i class="las la-times"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Nom')</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Email')</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>@lang('Téléphone')</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>@lang('Adresse')</label>
                            <input type="text" name="address" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary">@lang('Enregistrer')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<style>
    .select2-container {
        flex: 1; /* take available space */
        min-width: 0; /* prevents overflowing */
    }

    .select2-container .select2-selection {
        height: 38px !important; /* match Bootstrap form-control height */
        border-radius: 4px;
    }

    .sticky-summary { position: sticky; top: 12px; }
    .input-group > .input-group-text { min-width: 70px; justify-content: center; }


    /* tighten vertical rhythm */
    .row.g-4 > [class*="col-"] { margin-top: 0.25rem; }
    .card { margin-bottom: 1rem; }
    .card-header { padding: .65rem 1rem; }
    .card-body { padding: .9rem 1rem; }
    hr.my-4 { margin-top: 1rem !important; margin-bottom: 1rem !important; }

    /* inputs look aligned */
    .input-group > .input-group-text { min-width: 64px; justify-content: center; }
    .form-text { margin-top: .25rem; color: #6c757d; }

    /* reject section subtle card look without changing markup */
    #rejetContainer {
        border: 1px solid #e9ecef;
        border-radius: .5rem;
        padding: .75rem;
        margin-top: .5rem;
        background: #fcfcfd;
    }
    #rejetContainer .form-group label { font-weight: 600; }



</style>

@push('script')
    <script>
        /* ====== 1) Upload (UNCHANGED) ====== */
        (function($){
            "use strict";
            var fileAdded = 0;
            $('.addAttachment').on('click', function() {
                if (fileAdded >= 5) return;
                fileAdded++;
                if (fileAdded >= 5) $(this).attr('disabled', true);

                $(".fileUploadsContainer").append(`
      <div class="col-lg-4 col-md-6 removeFileInput mt-3">
        <div class="form-group">
          <div class="input-group">
            <input type="file" name="attachments[]" class="form-control"
                   accept=".jpeg,.jpg,.png,.pdf,.doc,.docx" required>
            <button type="button" class="input-group-text removeFile bg--danger border--danger">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
      </div>
    `);
            });
            $(document).on('click', '.removeFile', function() {
                fileAdded--;
                $('.addAttachment').removeAttr('disabled');
                $(this).closest('.removeFileInput').remove();
            });
        })(jQuery);


        {{--/* ====== 2) Rejet toggle (UNCHANGED logic, tiny polish) ====== */--}}
        {{--(function($){--}}
        {{--    "use strict";--}}
        {{--    function toggleRejetFields() {--}}
        {{--        const on = $('#dossier_rejete').is(':checked');--}}
        {{--        $('#rejetContainer').toggleClass('d-none', !on);--}}
        {{--        if(!on){--}}
        {{--            $('#rejection_reason, #date_envoi, #date_retour').val('');--}}
        {{--        }--}}
        {{--    }--}}
        {{--    toggleRejetFields();--}}
        {{--    $('#dossier_rejete').on('change', toggleRejetFields);--}}
        {{--})(jQuery);--}}

        {{--(function($){--}}
        {{--    "use strict";--}}

        {{--    const num = v => {--}}
        {{--        v = parseFloat(String(v).replace(',', '.'));--}}
        {{--        return isFinite(v) ? v : 0;--}}
        {{--    };--}}

        {{--    const getTvaRate = () => {--}}
        {{--        const val = $('#taux_tva').val();--}}
        {{--        return val === 'custom' ? num($('#custom_tva').val()) : num(val);--}}
        {{--    };--}}

        {{--    const showCustomTva = () => {--}}
        {{--        const isCustom = $('#taux_tva').val() === 'custom';--}}
        {{--        $('#customTvaContainer').toggleClass('d-none', !isCustom);--}}
        {{--        if (!isCustom) $('#custom_tva').val('');--}}
        {{--    };--}}

        {{--    function recalc(){--}}
        {{--        const type = $('#type_dossier').val();--}}

        {{--        if (type === 'national') {--}}
        {{--            // Inputs--}}
        {{--            const ht     = num($('#montant_ht').val());--}}
        {{--            const remise = num($('#remise_percent').val());--}}
        {{--            const taxe   = num($('#taxe_percent').val());--}}
        {{--            const timbre = num($('#timbre_percent').val());--}}
        {{--            const tva    = getTvaRate();--}}

        {{--            // Base after remise--}}
        {{--            const htNet = ht * (1 - (remise / 100));--}}

        {{--            // Components applied on HT after remise--}}
        {{--            const tvaAmt    = htNet * (tva    / 100);--}}
        {{--            const taxeAmt   = htNet * (taxe   / 100);--}}
        {{--            const timbreAmt = htNet * (timbre / 100);--}}

        {{--            const ttc = htNet + tvaAmt + taxeAmt + timbreAmt;--}}

        {{--            $('#montant_ttc').val(ttc.toFixed(2));--}}

        {{--            // résumé--}}
        {{--            $('#sum_tva_rate').text(tva.toFixed(2).replace(/\.00$/, ''));--}}
        {{--            $('#sum_remise_rate').text(remise.toFixed(2).replace(/\.00$/, ''));--}}
        {{--            $('#sum_taxe_rate').text(taxe.toFixed(2).replace(/\.00$/, ''));--}}
        {{--            $('#sum_timbre_rate').text(timbre.toFixed(2).replace(/\.00$/, ''));--}}
        {{--        }--}}
        {{--        else if (type === 'international') {--}}
        {{--            const taux   = num($('#taux_conversion').val());   // DZD per unit--}}
        {{--            const devise = num($('#montant_devise').val());    // amount in currency--}}
        {{--            const ibs    = num($('#montant_ibs').val());       // retention (DZD)--}}

        {{--            // TTC from devise--}}
        {{--            const ttc = devise * taux;--}}
        {{--            $('#montant_ttc').val(ttc.toFixed(2));--}}

        {{--            // HT = TTC - IBS--}}
        {{--            const ht = ttc - ibs;--}}
        {{--            $('#montant_ht').val(ht.toFixed(2));--}}

        {{--            // résumé markers--}}
        {{--            $('#sum_tva_rate').text('IBS');--}}
        {{--            $('#sum_remise_rate').text('0');--}}
        {{--            $('#sum_taxe_rate').text('0');--}}
        {{--            $('#sum_timbre_rate').text('0');--}}
        {{--        }--}}

        {{--        // Common résumé fields--}}
        {{--        const ht     = num($('#montant_ht').val());--}}
        {{--        const ttc    = num($('#montant_ttc').val());--}}
        {{--        const taux   = num($('#taux_conversion').val());--}}
        {{--        const devise = num($('#montant_devise').val());--}}
        {{--        $('#sum_ht_dzd').text(ht.toFixed(2));--}}
        {{--        $('#sum_ttc_dzd').text(ttc.toFixed(2));--}}
        {{--        $('#sum_taux').text(taux > 0 ? taux.toFixed(4) : '0');--}}
        {{--        $('#sum_devise').text(devise.toFixed(2));--}}
        {{--    }--}}

        {{--    // Type toggle (show only what’s relevant)--}}
        {{--    $('#type_dossier').on('change', function(){--}}
        {{--        const intl = $(this).val() === 'international';--}}
        {{--        $('#internationalFields').toggleClass('d-none', !intl);--}}
        {{--        $('#tvaSection').toggleClass('d-none', intl);--}}
        {{--        $('#customTvaContainer').toggleClass('d-none', intl || $('#taux_tva').val() !== 'custom');--}}
        {{--        $('#nationalExtras').toggleClass('d-none', intl);--}}

        {{--        // Add IBS input dynamically for international--}}
        {{--        if (intl) {--}}
        {{--            if ($('#ibsContainer').length === 0) {--}}
        {{--                $('#internationalFields .row.g-3').append(`--}}
        {{--  <div class="col-md-4" id="ibsContainer">--}}
        {{--    <label class="form-label" for="montant_ibs">@lang('IBS (Retenue)')</label>--}}
        {{--    <div class="input-group">--}}
        {{--      <input type="number" step="0.01" min="0" name="montant_ibs" id="montant_ibs"--}}
        {{--             class="form-control" placeholder="0.00">--}}
        {{--      <span class="input-group-text">DZD</span>--}}
        {{--    </div>--}}
        {{--    <div class="form-text">@lang('Montant de l’IBS à soustraire du TTC.')</div>--}}
        {{--  </div>--}}
        {{--`);--}}
        {{--            }--}}
        {{--        } else {--}}
        {{--            $('#ibsContainer').remove();--}}
        {{--        }--}}
        {{--        recalc();--}}
        {{--    });--}}

        {{--    // Reset devise from current TTC and taux--}}
        {{--    $(document).on('click', '#resetDeviseFromDzd', function(){--}}
        {{--        const ttc  = num($('#montant_ttc').val());--}}
        {{--        const taux = num($('#taux_conversion').val());--}}
        {{--        if (taux > 0) {--}}
        {{--            $('#montant_devise').val((ttc / taux).toFixed(2));--}}
        {{--            recalc();--}}
        {{--        }--}}
        {{--    });--}}

        {{--    // Events--}}
        {{--    $('#taux_tva').on('change', function(){ showCustomTva(); recalc(); });--}}
        {{--    $(document).on('input change',--}}
        {{--        '#montant_ht, #custom_tva, #remise_percent, #taxe_percent, #timbre_percent,' +--}}
        {{--        ' #montant_devise, #taux_conversion, #montant_ibs',--}}
        {{--        recalc--}}
        {{--    );--}}

        {{--    // Init--}}
        {{--    showCustomTva();--}}
        {{--    $('#type_dossier').trigger('change');--}}
        {{--    recalc();--}}

        {{--})(jQuery);--}}

        (function($){
            "use strict";

            const num = v => { v = parseFloat(String(v).replace(',', '.')); return isFinite(v) ? v : 0; };

            const getTvaRate = () => {
                const val = $('#taux_tva').val();
                return val === 'custom' ? num($('#custom_tva').val()) : num(val);
            };

            const showCustomTva = () => {
                const isCustom = $('#taux_tva').val() === 'custom';
                $('#customTvaContainer').toggleClass('d-none', !isCustom);
                if (!isCustom) $('#custom_tva').val('');
            };

            function recalc(){
                const type = $('#type_dossier').val();

                if (type === 'national') {
                    const ht     = num($('#montant_ht').val());
                    const remise = num($('#remise_percent').val());
                    const taxe   = num($('#taxe_percent').val());
                    const timbre = num($('#timbre_percent').val());
                    const tva    = getTvaRate();

                    // const htNet   = ht * (1 - (remise / 100));
                    // const tvaAmt  = htNet * (tva    / 100);
                    // const taxeAmt = htNet * (taxe   / 100);
                    // const timbAmt = htNet * (timbre / 100);
                    // const ttc     = htNet + tvaAmt + taxeAmt + timbAmt;

                    // all computed on original HT
                    const remiseAmt = ht * (remise / 100);
                    const tvaAmt    = ht * (tva    / 100);
                    const taxeAmt   = ht * (taxe   / 100);
                    const timbAmt   = ht * (timbre / 100);

                    // TTC = (HT - Remise) + TVA + Taxe + Timbre
                    const ttc = (ht - remiseAmt) + tvaAmt + taxeAmt + timbAmt;

                    $('#montant_ttc').val(ttc.toFixed(2));
                    $('#sum_tva_rate').text(tva.toFixed(2).replace(/\.00$/, ''));
                }
                else if (type === 'international') {
                    // Source of truth: DEVISE
                    const taux       = num($('#taux_conversion').val());
                    const deviseInit = num($('#montant_devise').val());
                    const ibsPct     = num($('#ibs_percent').val());

                    const ibsDevise  = deviseInit * ibsPct / 100;
                    const deviseNet  = Math.max(0, deviseInit - ibsDevise);

                    const ttcDzdInit = deviseInit * taux;
                    const htDzdNet   = deviseNet  * taux;

                    // Fill helpers
                    $('#ibs_devise').val(ibsDevise.toFixed(2));
                    $('#montant_devise_net').val(deviseNet.toFixed(2));
                    $('#ttc_dzd_initial').val(ttcDzdInit.toFixed(2));
                    $('#ht_dzd_apres_ibs').val(htDzdNet.toFixed(2));

                    // Keep the global DZD fields in sync (if you store them)
                    $('#montant_ttc').val(ttcDzdInit.toFixed(2));
                    $('#montant_ht').val(htDzdNet.toFixed(2));

                    // Summary
                    $('#sum_devise').text(deviseInit.toFixed(2));
                    $('#sum_devise_net').text(deviseNet.toFixed(2));
                    $('#sum_tva_rate').text('IBS');
                }

                // Common résumé
                const ht   = num($('#montant_ht').val());
                const ttc  = num($('#montant_ttc').val());
                const taux = num($('#taux_conversion').val());
                $('#sum_ht_dzd').text(ht.toFixed(2));
                $('#sum_ttc_dzd').text(ttc.toFixed(2));
                $('#sum_taux').text(taux > 0 ? taux.toFixed(4) : '0');
            }

            // Toggle: hide DZD card entirely for international
            $('#type_dossier').on('change', function(){
                const intl = $(this).val() === 'international';

                // Cards/sections
                $('#dzdCard').toggleClass('d-none', intl);           // hide Dinars card on intl
                $('#internationalFields').toggleClass('d-none', !intl);

                // National-only UI
                $('#tvaSection').toggleClass('d-none', intl);
                $('#customTvaContainer').toggleClass('d-none', intl || $('#taux_tva').val() !== 'custom');
                $('.national-only').toggleClass('d-none', intl);

                // International-only summary rows
                $('.intl-only').toggleClass('d-none', !intl);

                recalc();
            });

            // Reset helper: recompute devise from current TTC and taux
            $(document).on('click', '#resetDeviseFromDzd', function(){
                const ttc  = num($('#montant_ttc').val());
                const taux = num($('#taux_conversion').val());
                if (taux > 0) {
                    $('#montant_devise').val((ttc / taux).toFixed(2));
                    recalc();
                }
            });

            // Events
            $('#taux_tva').on('change', function(){ showCustomTva(); recalc(); });
            $(document).on('input change',
                '#montant_ht, #custom_tva, #remise_percent, #taxe_percent, #timbre_percent,' +
                ' #montant_devise, #taux_conversion, #ibs_percent',
                recalc
            );

            // Init
            showCustomTva();
            $('#type_dossier').trigger('change');
            recalc();

        })(jQuery);
    </script>

    <script>
        (function($){
            "use strict";

            // --- toggle affichage des champs de rejet ---
            function toggleRejetFields() {
                const isRejected = $('#dossier_rejete').is(':checked');
                $('#rejetContainer').toggleClass('d-none', !isRejected);
                if (!isRejected) {
                    $('#rejection_reason, #date_envoi').val('');
                }
            }

            // init au chargement
            toggleRejetFields();

            // événement au clic
            $('#dossier_rejete').on('change', toggleRejetFields);

        })(jQuery);
    </script>
@endpush
