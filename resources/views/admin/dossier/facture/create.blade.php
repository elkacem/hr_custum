{{--@extends('admin.layouts.app')--}}

{{--@section('panel')--}}
{{--    <div class="row">--}}
{{--        <div class="col-lg-12">--}}
{{--            <div class="card">--}}
{{--                <form action="{{ route('admin.dossiers.factures.store', $dossier->id) }}" method="POST" class="disableSubmission" enctype="multipart/form-data">--}}
{{--                    @csrf--}}
{{--                    <div class="card-body">--}}
{{--                        <div class="row">--}}

{{--                            --}}{{-- ================== FACTURE ================== --}}
{{--                            <h5 class="mb-3">@lang('Facture')</h5>--}}

{{--                            --}}{{-- Type (comme Dossier) --}}
{{--                            <div class="row g-3 align-items-end">--}}
{{--                                <div class="col-lg-4">--}}
{{--                                    <label class="form-label" for="type_dossier">@lang('Type')</label>--}}
{{--                                    <select name="type_dossier" id="type_dossier" class="form-control" required>--}}
{{--                                        --}}{{-- Par défaut on reprend le type du dossier parent --}}
{{--                                        @php $parentType = old('type_dossier', $dossier->type_dossier ?? 'national'); @endphp--}}
{{--                                        <option value="national" {{ $parentType === 'national' ? 'selected' : '' }}>@lang('National')</option>--}}
{{--                                        <option value="international" {{ $parentType === 'international' ? 'selected' : '' }}>@lang('International')</option>--}}
{{--                                    </select>--}}
{{--                                    <div class="form-text">@lang('Choisissez “International” pour activer la conversion devise.')</div>--}}
{{--                                </div>--}}

{{--                                <x-form.input name="ref_facture" label="Réf Facture" col="4"/>--}}
{{--                                <x-form.date  name="date_facture" label="Date Facture" col="4"/>--}}
{{--                            </div>--}}

{{--                            <hr class="my-4">--}}

{{--                            <div class="row g-4">--}}
{{--                                <div class="col-lg-8">--}}

{{--                                    --}}{{-- Métadonnées --}}
{{--                                    <div class="row g-3">--}}
{{--                                        <x-form.input name="bon_commande" label="Bon Commande" col="6"/>--}}
{{--                                        --}}{{-- Bon Commande (select) --}}
{{--                                        <div class="col-md-3">--}}
{{--                                            <label class="form-label" for="bon_commande">@lang('Bon Commande')</label>--}}
{{--                                            <select name="bon_commande" id="bon_commande" class="form-control" required>--}}
{{--                                                <option value="">-- @lang('Choisir') --</option>--}}
{{--                                                <option value="Bon de Commande" {{ old('bon_commande') == 'Bon de Commande' ? 'selected' : '' }}>@lang('Bon Commande')</option>--}}
{{--                                                <option value="Facture" {{ old('bon_commande') == 'Facture' ? 'selected' : '' }}>@lang('Facture')</option>--}}
{{--                                            </select>--}}
{{--                                        </div>--}}

{{--                                        --}}{{-- Numéro de contrat --}}
{{--                                        <x-form.input name="numero_contrat" label="Numéro de Contrat" col="3"/>--}}

{{--                                        <x-form.input name="periode" label="Période" col="6"/>--}}
{{--                                        <x-form.input name="objet"   label="Objet" col="12"/>--}}

{{--                                        <div class="col-md-6">--}}
{{--                                            <label class="form-label" for="direction_id">@lang('Direction')</label>--}}
{{--                                            <select name="direction_id" id="direction_id" class="form-control select2">--}}
{{--                                                <option value="">-- @lang('Choisir une direction') --</option>--}}
{{--                                                @foreach($directions as $direction)--}}
{{--                                                    <option value="{{ $direction->id }}" {{ old('direction_id') == $direction->id ? 'selected' : '' }}>--}}
{{--                                                        {{ $direction->name }}--}}
{{--                                                    </option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                        </div>--}}

{{--                                        <div class="col-md-6">--}}
{{--                                            <label class="form-label" for="compte_id">@lang('Compte Comptable')</label>--}}
{{--                                            <select name="compte_id" id="compte_id" class="form-control select2">--}}
{{--                                                <option value="">-- @lang('Choisir un compte') --</option>--}}
{{--                                                @foreach($comptes as $compte)--}}
{{--                                                    <option value="{{ $compte->id }}" {{ old('compte_id') == $compte->id ? 'selected' : '' }}>--}}
{{--                                                        {{ $compte->code }} - {{ $compte->name }}--}}
{{--                                                    </option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                    --}}{{-- Montants en Dinars (DZD) --}}
{{--                                    <div class="card mt-4 shadow-sm border-0">--}}
{{--                                        <div class="card-header bg--light-blue">--}}
{{--                                            <strong>@lang('Montants en Dinars (DZD)')</strong>--}}
{{--                                        </div>--}}
{{--                                        <div class="card-body">--}}
{{--                                            <div class="row g-3">--}}
{{--                                                --}}{{-- HT --}}
{{--                                                <div class="col-md-4">--}}
{{--                                                    <label class="form-label" for="montant_ht">@lang('Montant HT')</label>--}}
{{--                                                    <div class="input-group">--}}
{{--                                                        <input type="number" step="0.01" min="0" name="montant_ht" id="montant_ht"--}}
{{--                                                               class="form-control" placeholder="0.00" value="{{ old('montant_ht') }}">--}}
{{--                                                        <span class="input-group-text">DZD</span>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="form-text">@lang('Montant hors taxe en dinars.')</div>--}}
{{--                                                </div>--}}

{{--                                                --}}{{-- TVA (liste + autre) --}}
{{--                                                <div class="col-md-4">--}}
{{--                                                    <label class="form-label" for="taux_tva">@lang('TVA')</label>--}}
{{--                                                    <select id="taux_tva" name="taux_tva" class="form-control">--}}
{{--                                                        <option value="0"  {{ old('taux_tva') == 0 ? 'selected' : '' }}>0%</option>--}}
{{--                                                        <option value="9"  {{ old('taux_tva') == 9 ? 'selected' : '' }}>9%</option>--}}
{{--                                                        <option value="19" {{ old('taux_tva') == 19 ? 'selected' : '' }}>19%</option>--}}
{{--                                                        <option value="custom" {{ old('taux_tva') == 'custom' ? 'selected' : '' }}>@lang('Autre')</option>--}}
{{--                                                    </select>--}}
{{--                                                    <div class="form-text">@lang('Choisissez un taux de TVA ou “Autre”.')</div>--}}
{{--                                                </div>--}}

{{--                                                <div class="col-md-4 d-none" id="customTvaContainer">--}}
{{--                                                    <label class="form-label" for="custom_tva">@lang('TVA personnalisée (%)')</label>--}}
{{--                                                    <div class="input-group">--}}
{{--                                                        <input type="number" step="0.01" min="0" name="custom_tva" id="custom_tva"--}}
{{--                                                               class="form-control" value="{{ old('custom_tva') }}" placeholder="Ex: 12.50">--}}
{{--                                                        <span class="input-group-text">%</span>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}

{{--                                                --}}{{-- TTC (auto) --}}
{{--                                                <div class="col-md-4">--}}
{{--                                                    <label class="form-label" for="montant_ttc">@lang('Montant TTC')</label>--}}
{{--                                                    <div class="input-group">--}}
{{--                                                        <input type="number" step="0.01" name="montant_ttc" id="montant_ttc" class="form-control" readonly>--}}
{{--                                                        <span class="input-group-text">DZD</span>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="form-text">@lang('Calculé automatiquement : HT + TVA.')</div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                    --}}{{-- Montants en Devise (affiché seulement si "International") --}}
{{--                                    <div class="card mt-4 shadow-sm border-0 d-none" id="internationalFields">--}}
{{--                                        <div class="card-header bg--light-blue d-flex justify-content-between align-items-center">--}}
{{--                                            <strong>@lang('Montants en Devise')</strong>--}}
{{--                                            <span class="badge bg-secondary">@lang('Basé sur le TTC (DZD)')</span>--}}
{{--                                        </div>--}}
{{--                                        <div class="card-body">--}}
{{--                                            <div class="row g-3">--}}
{{--                                                <div class="col-md-4">--}}
{{--                                                    <label class="form-label" for="taux_conversion">@lang('Taux de Conversion')</label>--}}
{{--                                                    <div class="input-group">--}}
{{--                                                        <input type="number" step="0.0001" min="0" name="taux_conversion" id="taux_conversion"--}}
{{--                                                               class="form-control" placeholder="Ex: 135.5000" value="{{ old('taux_conversion') }}">--}}
{{--                                                        <span class="input-group-text">DZD/1</span>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="form-text">@lang('Montant de 1 unité de la devise en DZD.')</div>--}}
{{--                                                </div>--}}

{{--                                                <div class="col-md-4">--}}
{{--                                                    <label class="form-label" for="monnaie">@lang('Monnaie')</label>--}}
{{--                                                    <select id="monnaie" name="monnaie" class="form-control">--}}
{{--                                                        <option value="USD" {{ old('monnaie') == 'USD' ? 'selected' : '' }}>💵 USD</option>--}}
{{--                                                        <option value="EUR" {{ old('monnaie') == 'EUR' ? 'selected' : '' }}>💶 EUR</option>--}}
{{--                                                    </select>--}}
{{--                                                </div>--}}

{{--                                                <div class="col-md-4">--}}
{{--                                                    <label class="form-label" for="montant_devise">@lang('Montant en Devise (équiv. TTC)')</label>--}}
{{--                                                    <div class="input-group">--}}
{{--                                                        <input type="number" step="0.01" min="0" name="montant_devise" id="montant_devise"--}}
{{--                                                               class="form-control" placeholder="0.00" value="{{ old('montant_devise') }}">--}}
{{--                                                        <button type="button" class="btn btn-outline-secondary" id="resetDeviseFromDzd"--}}
{{--                                                                title="@lang('Réappliquer conversion depuis TTC DZD')">↻</button>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="form-text">@lang('Auto = TTC (DZD) ÷ Taux. Modifiable au besoin.')</div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                    <x-form.textarea name="observation" label="Observation" col="12"/>--}}
{{--                                </div>--}}

{{--                                --}}{{-- Résumé (sticky) --}}
{{--                                <div class="col-lg-4 d-none d-lg-block">--}}
{{--                                    <div class="card shadow-sm sticky-summary">--}}
{{--                                        <div class="card-header bg--light-blue">--}}
{{--                                            <strong>@lang('Résumé')</strong>--}}
{{--                                        </div>--}}
{{--                                        <div class="card-body">--}}
{{--                                            <div class="d-flex justify-content-between mb-2">--}}
{{--                                                <span>@lang('HT (DZD)')</span>--}}
{{--                                                <strong id="sum_ht_dzd">0.00</strong>--}}
{{--                                            </div>--}}
{{--                                            <div class="d-flex justify-content-between mb-2">--}}
{{--                                                <span>@lang('TVA')</span>--}}
{{--                                                <strong><span id="sum_tva_rate">0</span>%</strong>--}}
{{--                                            </div>--}}
{{--                                            <hr class="my-2">--}}
{{--                                            <div class="d-flex justify-content-between mb-2">--}}
{{--                                                <span>@lang('TTC (DZD)')</span>--}}
{{--                                                <strong id="sum_ttc_dzd">0.00</strong>--}}
{{--                                            </div>--}}
{{--                                            <div class="d-flex justify-content-between mb-2">--}}
{{--                                                <span>@lang('Taux')</span>--}}
{{--                                                <strong><span id="sum_taux">0</span> <small>DZD/1</small></strong>--}}
{{--                                            </div>--}}
{{--                                            <div class="d-flex justify-content-between">--}}
{{--                                                <span>@lang('Montant en Devise')</span>--}}
{{--                                                <strong id="sum_devise">0.00</strong>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            --}}{{-- Submit --}}
{{--                            <div class="col-md-12 mt-3">--}}
{{--                                <button type="submit" class="btn btn--primary w-100">@lang('Enregistrer')</button>--}}
{{--                            </div>--}}

{{--                        </div>--}}
{{--                    </div>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--@endsection--}}

{{-- Styles identiques au formulaire Dossier --}}
{{--<style>--}}
{{--    .select2-container { flex: 1; min-width: 0; }--}}
{{--    .select2-container .select2-selection { height: 38px !important; border-radius: 4px; }--}}
{{--    .sticky-summary { position: sticky; top: 12px; }--}}
{{--    .input-group > .input-group-text { min-width: 64px; justify-content: center; }--}}
{{--    .row.g-4 > [class*="col-"] { margin-top: 0.25rem; }--}}
{{--    .card { margin-bottom: 1rem; }--}}
{{--    .card-header { padding: .65rem 1rem; }--}}
{{--    .card-body { padding: .9rem 1rem; }--}}
{{--    hr.my-4 { margin-top: 1rem !important; margin-bottom: 1rem !important; }--}}
{{--    .form-text { margin-top: .25rem; color: #6c757d; }--}}
{{--</style>--}}

{{--@push('script')--}}
{{--    <script>--}}
{{--        /* ====== 1) Upload (idem Dossier) ====== */--}}
{{--        (function($){--}}
{{--            "use strict";--}}
{{--            var fileAdded = 0;--}}
{{--            $('.addAttachment').on('click', function() {--}}
{{--                if (fileAdded >= 5) return;--}}
{{--                fileAdded++;--}}
{{--                if (fileAdded >= 5) $(this).attr('disabled', true);--}}

{{--                $(".fileUploadsContainer").append(`--}}
{{--          <div class="col-lg-4 col-md-6 removeFileInput mt-3">--}}
{{--            <div class="form-group">--}}
{{--              <div class="input-group">--}}
{{--                <input type="file" name="attachments[]" class="form-control"--}}
{{--                       accept=".jpeg,.jpg,.png,.pdf,.doc,.docx" required>--}}
{{--                <button type="button" class="input-group-text removeFile bg--danger border--danger">--}}
{{--                  <i class="fas fa-times"></i>--}}
{{--                </button>--}}
{{--              </div>--}}
{{--            </div>--}}
{{--          </div>--}}
{{--        `);--}}
{{--            });--}}
{{--            $(document).on('click', '.removeFile', function() {--}}
{{--                fileAdded--;--}}
{{--                $('.addAttachment').removeAttr('disabled');--}}
{{--                $(this).closest('.removeFileInput').remove();--}}
{{--            });--}}
{{--        })(jQuery);--}}

{{--        /* ====== 2) Logic TVA + Conversion (même que Dossier) ====== */--}}
{{--        (function($){--}}
{{--            "use strict";--}}

{{--            const num = v => {--}}
{{--                v = parseFloat(String(v).replace(',', '.'));--}}
{{--                return isFinite(v) ? v : 0;--}}
{{--            };--}}

{{--            const getTvaRate = () => {--}}
{{--                const val = $('#taux_tva').val();--}}
{{--                return val === 'custom' ? num($('#custom_tva').val()) : num(val);--}}
{{--            };--}}

{{--            const showCustomTva = () => {--}}
{{--                const isCustom = $('#taux_tva').val() === 'custom';--}}
{{--                $('#customTvaContainer').toggleClass('d-none', !isCustom);--}}
{{--                if(!isCustom) $('#custom_tva').val('');--}}
{{--            };--}}

{{--            // L’utilisateur a-t-il modifié manuellement la devise ?--}}
{{--            let deviseTouched = false;--}}

{{--            function toggleInternationalBlock(){--}}
{{--                const intl = $('#type_dossier').val() === 'international';--}}
{{--                $('#internationalFields').toggleClass('d-none', !intl);--}}
{{--                if(!intl){--}}
{{--                    $('#taux_conversion, #montant_devise').val('');--}}
{{--                    deviseTouched = false;--}}
{{--                }--}}
{{--                recalc();--}}
{{--            }--}}

{{--            function recalc(){--}}
{{--                // TTC--}}
{{--                const ht   = num($('#montant_ht').val());--}}
{{--                const tva  = getTvaRate();--}}
{{--                const ttc  = ht * (1 + tva/100);--}}
{{--                $('#montant_ttc').val(ttc.toFixed(2));--}}

{{--                // International: montant devise auto si non touché--}}
{{--                if($('#type_dossier').val() === 'international'){--}}
{{--                    const taux = num($('#taux_conversion').val()); // DZD / 1 unité devise--}}
{{--                    if(!deviseTouched){--}}
{{--                        const autoDev = taux > 0 ? (ttc / taux) : 0;--}}
{{--                        $('#montant_devise').val(autoDev.toFixed(2));--}}
{{--                    }--}}
{{--                }--}}

{{--                // Résumé--}}
{{--                const taux = num($('#taux_conversion').val());--}}
{{--                const dev  = num($('#montant_devise').val());--}}
{{--                $('#sum_ht_dzd').text(ht.toFixed(2));--}}
{{--                $('#sum_tva_rate').text(tva.toFixed(2).replace(/\.00$/,''));--}}
{{--                $('#sum_ttc_dzd').text(ttc.toFixed(2));--}}
{{--                $('#sum_taux').text(taux > 0 ? taux.toFixed(4) : '0');--}}
{{--                $('#sum_devise').text(dev.toFixed(2));--}}
{{--            }--}}

{{--            // Listeners--}}
{{--            $('#type_dossier').on('change', toggleInternationalBlock);--}}
{{--            $('#taux_tva').on('change', function(){ showCustomTva(); recalc(); });--}}
{{--            $(document).on('input change', '#montant_ht, #custom_tva', recalc);--}}
{{--            $('#taux_conversion').on('input change', recalc);--}}
{{--            $('#montant_devise').on('input change', function(){ deviseTouched = true; });--}}
{{--            $(document).on('click', '#resetDeviseFromDzd', function(){ deviseTouched = false; recalc(); });--}}

{{--            // Init--}}
{{--            showCustomTva();--}}
{{--            toggleInternationalBlock(); // affiche/masque selon valeur initiale--}}
{{--            recalc();--}}

{{--        })(jQuery);--}}
{{--    </script>--}}
{{--@endpush--}}

@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route('admin.dossiers.factures.store', $dossier->id) }}" method="POST" class="disableSubmission" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">

                            {{-- ================== FACTURE ================== --}}
                            <h5 class="mb-3">@lang('Facture')</h5>

                            {{-- Type (hérite du Dossier) --}}
                            <div class="row g-3 align-items-end">
                                <div class="col-md-4">
                                    <label class="form-label" for="type_dossier">@lang('Type')</label>
                                    <select name="type_dossier" id="type_dossier" class="form-control" required>
                                        @php $parentType = old('type_dossier', $dossier->type_dossier ?? 'national'); @endphp
                                        <option value="national" {{ $parentType === 'national' ? 'selected' : '' }}>@lang('National')</option>
                                        <option value="international" {{ $parentType === 'international' ? 'selected' : '' }}>@lang('International')</option>
                                    </select>
{{--                                    <div class="form-text">@lang('Choisissez “International” pour activer la conversion devise.')</div>--}}
                                </div>

                                <x-form.input name="ref_facture" label="Réf Facture" col="4"/>
                                <x-form.date  name="date_facture" label="Date Facture" col="4"/>
                            </div>

                            <hr class="my-4">

                            <div class="row g-4">
                                <div class="col-lg-8">

                                    {{-- Métadonnées --}}
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label class="form-label" for="bon_commande">@lang('Bon Commande')</label>
                                            <select name="bon_commande" id="bon_commande" class="form-control" required>
                                                <option value="">-- @lang('Choisir') --</option>
                                                <option value="Bon de Commande" {{ old('bon_commande') == 'Bon de Commande' ? 'selected' : '' }}>@lang('Bon Commande')</option>
                                                <option value="Facture" {{ old('bon_commande') == 'Facture' ? 'selected' : '' }}>@lang('Facture')</option>
                                            </select>
                                        </div>

                                        <x-form.input name="numero_contrat" label="Numéro de Contrat" col="3"/>
                                        <x-form.input name="periode" label="Période" col="6"/>
                                        <x-form.input name="objet"   label="Objet" col="12"/>

                                        <div class="col-md-6">
                                            <label class="form-label" for="direction_id">@lang('Direction')</label>
                                            <select name="direction_id" id="direction_id" class="form-control select2">
                                                <option value="">-- @lang('Choisir une direction') --</option>
                                                @foreach($directions as $direction)
                                                    <option value="{{ $direction->id }}" {{ old('direction_id') == $direction->id ? 'selected' : '' }}>
                                                        {{ $direction->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label" for="compte_id">@lang('Compte Comptable')</label>
                                            <select name="compte_id" id="compte_id" class="form-control select2">
                                                <option value="">-- @lang('Choisir un compte') --</option>
                                                @foreach($comptes as $compte)
                                                    <option value="{{ $compte->id }}" {{ old('compte_id') == $compte->id ? 'selected' : '' }}>
                                                        {{ $compte->code }} - {{ $compte->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- ========== NATIONAL (DZD) ========== --}}
                                    <div class="card mt-4 shadow-sm border-0" id="cardNational">
                                        <div class="card-header bg--light-blue">
                                            <strong>@lang('Montants en Dinars (DZD)')</strong>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                {{-- HT --}}
                                                <div class="col-md-4">
                                                    <label class="form-label" for="montant_ht">@lang('Montant HT')</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.01" min="0" name="montant_ht" id="montant_ht"
                                                               class="form-control" placeholder="0.00" value="{{ old('montant_ht') }}">
                                                        <span class="input-group-text">DZD</span>
                                                    </div>
                                                    <div class="form-text">@lang('Montant hors taxe (base de tous les calculs).')</div>
                                                </div>

                                                {{-- TVA --}}
                                                <div class="col-md-4">
                                                    <label class="form-label" for="taux_tva">@lang('TVA')</label>
                                                    <select id="taux_tva" name="taux_tva" class="form-control">
                                                        <option value="0"  {{ old('taux_tva') == 0 ? 'selected' : '' }}>0%</option>
                                                        <option value="9"  {{ old('taux_tva') == 9 ? 'selected' : '' }}>9%</option>
                                                        <option value="19" {{ old('taux_tva') == 19 ? 'selected' : '' }}>19%</option>
                                                        <option value="custom" {{ old('taux_tva') == 'custom' ? 'selected' : '' }}>@lang('Autre')</option>
                                                    </select>
                                                    <div class="form-text">@lang('TVA appliquée sur HT.')</div>
                                                </div>

                                                {{-- TVA custom --}}
                                                <div class="col-md-4 d-none" id="customTvaContainer">
                                                    <label class="form-label" for="custom_tva">@lang('TVA personnalisée (%)')</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.01" min="0" name="custom_tva" id="custom_tva"
                                                               class="form-control" value="{{ old('custom_tva') }}" placeholder="Ex: 12.50">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                </div>

                                                {{-- Remise / Taxe / Timbre (tous sur HT) --}}
                                                <div class="col-md-4">
                                                    <label class="form-label" for="remise_percent">@lang('Remise (%)')</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.01" min="0" name="remise_percent" id="remise_percent"
                                                               class="form-control" value="{{ old('remise_percent', 0) }}" placeholder="0.00">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                    <div class="form-text">@lang('Appliquée sur HT.')</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="form-label" for="taxe_percent">@lang('Taxe (%)')</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.01" min="0" name="taxe_percent" id="taxe_percent"
                                                               class="form-control" value="{{ old('taxe_percent', 0) }}" placeholder="0.00">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                    <div class="form-text">@lang('Appliquée sur HT.')</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="form-label" for="timbre_percent">@lang('Droit de timbre (%)')</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.01" min="0" name="timbre_percent" id="timbre_percent"
                                                               class="form-control" value="{{ old('timbre_percent', 0) }}" placeholder="0.00">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                    <div class="form-text">@lang('Appliqué sur HT.')</div>
                                                </div>

                                                {{-- TTC (auto) --}}
                                                <div class="col-md-4">
                                                    <label class="form-label" for="montant_ttc">@lang('Montant TTC')</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.01" name="montant_ttc" id="montant_ttc" class="form-control" readonly>
                                                        <span class="input-group-text">DZD</span>
                                                    </div>
                                                    <div class="form-text">
                                                        @lang('Calcul : (HT − Remise(HT)) + TVA(HT) + Taxe(HT) + Timbre(HT).')
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- ========== INTERNATIONAL (Devise) ========== --}}
                                    <div class="card mt-4 shadow-sm border-0 d-none" id="cardInternational">
                                        <div class="card-header bg--light-blue d-flex justify-content-between align-items-center">
                                            <strong>@lang('Montants en Devise')</strong>
                                            <span class="badge bg-secondary">@lang('Le Dinar est dérivé')</span>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-3">


                                                <div class="col-md-4">
                                                    <label class="form-label" for="montant_devise">@lang('Montant en Devise (source)')</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.01" min="0" name="montant_devise" id="montant_devise"
                                                               class="form-control" placeholder="0.00" value="{{ old('montant_devise') }}">
                                                        <button type="button" class="btn btn-outline-secondary" id="resetDeviseFromDzd"
                                                                title="@lang('Réappliquer conversion depuis TTC DZD')">↻</button>
                                                    </div>
                                                    <div class="form-text">@lang('Source de vérité; DZD dérivés.')</div>
                                                </div>


                                                <div class="col-md-4">
                                                    <label class="form-label" for="taux_conversion">@lang('Taux de Conversion')</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.0001" min="0" name="taux_conversion" id="taux_conversion"
                                                               class="form-control" placeholder="Ex: 135.5000" value="{{ old('taux_conversion') }}">
                                                        <span class="input-group-text">DZD/1</span>
                                                    </div>
                                                    <div class="form-text">@lang('Montant de 1 unité devise en DZD.')</div>
                                                </div>

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
                                                    <label class="form-label" for="ibs_devise">@lang('IBS (devise)')</label>
                                                    <input type="number" step="0.01" min="0" id="ibs_devise" class="form-control" readonly>
                                                    <div class="form-text">@lang('= devise × IBS%.')</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="form-label" for="montant_devise_net">@lang('Devise après IBS')</label>
                                                    <input type="number" step="0.01" min="0" id="montant_devise_net" class="form-control" readonly>
                                                    <div class="form-text">@lang('= devise − IBS devise.')</div>
                                                </div>

                                                {{-- DZD dérivés / synchro vers inputs HT/TTC --}}
                                                <div class="col-md-6">
                                                    <label class="form-label">@lang('TTC (DZD) dérivé')</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.01" min="0" id="ttc_dzd_initial" class="form-control" readonly>
                                                        <span class="input-group-text">DZD</span>
                                                    </div>
                                                    <div class="form-text">@lang('= devise × taux.')</div>
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

                                    <x-form.textarea name="observation" label="Observation" col="12"/>
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
                                            <div class="d-flex justify-content-between mb-2" id="row_tva_summary">
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
                                            <div class="d-flex justify-content-between mb-2 d-none" id="row_devise_net">
                                                <span>@lang('Devise après IBS')</span>
                                                <strong id="sum_devise_net">0.00</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Submit --}}
                            <div class="col-md-12 mt-3">
                                <button type="submit" class="btn btn--primary w-100">@lang('Enregistrer')</button>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<style>
    .select2-container { flex: 1; min-width: 0; }
    .select2-container .select2-selection { height: 38px !important; border-radius: 4px; }
    .sticky-summary { position: sticky; top: 12px; }
    .input-group > .input-group-text { min-width: 64px; justify-content: center; }
    .row.g-4 > [class*="col-"] { margin-top: 0.25rem; }
    .card { margin-bottom: 1rem; }
    .card-header { padding: .65rem 1rem; }
    .card-body { padding: .9rem 1rem; }
    hr.my-4 { margin-top: 1rem !important; margin-bottom: 1rem !important; }
    .form-text { margin-top: .25rem; color: #6c757d; }
</style>

@push('script')
    <script>
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

            // Track manual override for devise
            let deviseTouched = false;

            function toggleBlocks(){
                const intl = $('#type_dossier').val() === 'international';
                $('#cardInternational').toggleClass('d-none', !intl);
                $('#cardNational').toggleClass('d-none', intl);

                // summary rows visibility
                $('#row_tva_summary').toggleClass('d-none', intl);
                $('#row_devise_net').toggleClass('d-none', !intl);

                if (!intl){
                    // clear intl-only UI values
                    $('#taux_conversion, #montant_devise, #ibs_percent').val('');
                    $('#ibs_devise, #montant_devise_net, #ttc_dzd_initial, #ht_dzd_apres_ibs').val('');
                    deviseTouched = false;
                    // ensure HT is editable in national
                    $('#montant_ht').prop('readonly', false);
                } else {
                    // HT becomes derived (readonly) in international
                    $('#montant_ht').prop('readonly', true);
                }
                recalc();
            }

            function recalc(){
                const isIntl = $('#type_dossier').val() === 'international';

                if (!isIntl){
                    // ===== NATIONAL: all on HT =====
                    const ht     = num($('#montant_ht').val());
                    const tva    = getTvaRate();
                    const remise = num($('#remise_percent').val());
                    const taxe   = num($('#taxe_percent').val());
                    const timbre = num($('#timbre_percent').val());

                    const remiseAmt = ht * (remise / 100);
                    const tvaAmt    = ht * (tva    / 100);
                    const taxeAmt   = ht * (taxe   / 100);
                    const timbAmt   = ht * (timbre / 100);

                    const ttc = (ht - remiseAmt) + tvaAmt + taxeAmt + timbAmt;

                    $('#montant_ttc').val(ttc.toFixed(2));

                    // summary
                    $('#sum_ht_dzd').text(ht.toFixed(2));
                    $('#sum_tva_rate').text(tva.toFixed(2).replace(/\.00$/,''));
                    $('#sum_ttc_dzd').text(ttc.toFixed(2));
                    $('#sum_taux').text(num($('#taux_conversion').val()) > 0 ? num($('#taux_conversion').val()).toFixed(4) : '0');
                    $('#sum_devise').text(num($('#montant_devise').val()).toFixed(2));
                } else {
                    // ===== INTERNATIONAL: source = devise; IBS% on devise; DZD derived =====
                    const taux   = num($('#taux_conversion').val()); // DZD per 1 unit
                    const devise = num($('#montant_devise').val());
                    const ibsPct = num($('#ibs_percent').val());

                    const ibsDev = devise * (ibsPct/100);
                    const devNet = Math.max(0, devise - ibsDev);

                    const ttcDzd = devise * taux;
                    const htDzd  = devNet * taux;

                    // fill helpers
                    $('#ibs_devise').val(ibsDev.toFixed(2));
                    $('#montant_devise_net').val(devNet.toFixed(2));
                    $('#ttc_dzd_initial').val(ttcDzd.toFixed(2));
                    $('#ht_dzd_apres_ibs').val(htDzd.toFixed(2));

                    // sync main DZD fields (HT derived & TTC derived)
                    $('#montant_ttc').val(ttcDzd.toFixed(2));
                    $('#montant_ht').val(htDzd.toFixed(2));

                    // summary
                    $('#sum_ht_dzd').text(htDzd.toFixed(2));
                    $('#sum_ttc_dzd').text(ttcDzd.toFixed(2));
                    $('#sum_taux').text(taux > 0 ? taux.toFixed(4) : '0');
                    $('#sum_devise').text(devise.toFixed(2));
                    $('#sum_devise_net').text(devNet.toFixed(2));
                }
            }

            // Events
            $('#type_dossier').on('change', toggleBlocks);
            $('#taux_tva').on('change', function(){ showCustomTva(); recalc(); });
            $(document).on('input change',
                '#montant_ht, #custom_tva, #remise_percent, #taxe_percent, #timbre_percent',
                recalc
            );

            $('#taux_conversion').on('input change', function(){
                // when taux changes, if user didn't touch devise we still recompute summary;
                recalc();
            });

            $('#montant_devise').on('input change', function(){
                deviseTouched = true;
                recalc();
            });

            $('#ibs_percent').on('input change', recalc);

            // Reset devise from current TTC & taux (useful if user wants to align again)
            $(document).on('click', '#resetDeviseFromDzd', function(){
                deviseTouched = false;
                const ttc  = num($('#montant_ttc').val());
                const taux = num($('#taux_conversion').val());
                if (taux > 0){
                    $('#montant_devise').val((ttc / taux).toFixed(2));
                }
                recalc();
            });

            // Init
            showCustomTva();
            toggleBlocks();
            recalc();

        })(jQuery);
    </script>
@endpush
