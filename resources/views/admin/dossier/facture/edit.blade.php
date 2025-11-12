{{--@extends('admin.layouts.app')--}}

{{--@section('panel')--}}
{{--    <div class="card">--}}
{{--        <div class="card-header"><h5>Modifier Facture #{{ $facture->id }} (Dossier #{{ $dossier->id }})</h5></div>--}}
{{--        <div class="card-body">--}}
{{--            <form action="{{ route('admin.dossiers.factures.update', [$dossier->id,$facture->id]) }}" method="POST">--}}
{{--                @csrf @method('PUT')--}}
{{--                <div class="row">--}}
{{--                    <x-form.input name="ref_facture" label="Réf Facture" value="{{ $facture->ref_facture }}" col="6"/>--}}
{{--                    <x-form.date name="date_facture" label="Date Facture" value="{{ $facture->date_facture }}" col="6"/>--}}
{{--                    <x-form.input name="bon_commande" label="Bon Commande" value="{{ $facture->bon_commande }}" col="6"/>--}}
{{--                    <x-form.input name="periode" label="Période" value="{{ $facture->periode }}" col="6"/>--}}
{{--                    <x-form.input type="number" step="0.01" name="montant_ht" label="Montant HT" value="{{ $facture->montant_ht }}" col="6"/>--}}
{{--                    <x-form.input type="number" step="0.01" name="taux_tva" label="TVA (%)" value="{{ $facture->taux_tva }}" col="6"/>--}}
{{--                    <x-form.textarea name="observation" label="Observation" col="12">{{ $facture->observation }}</x-form.textarea>--}}
{{--                </div>--}}
{{--                <button type="submit" class="btn btn--primary">Mettre à jour</button>--}}
{{--            </form>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--@endsection--}}

{{--@extends('admin.layouts.app')--}}

{{--@section('panel')--}}
{{--    <div class="row justify-content-center">--}}
{{--        <div class="col-xl-10 col-lg-12">--}}
{{--            <div class="card box--shadow1 border-0">--}}
{{--                <div class="card-header bg--primary text-white">--}}
{{--                    <h5 class="mb-0">--}}
{{--                        <i class="las la-file-invoice"></i>--}}
{{--                        @lang('Modifier Facture') #{{ $facture->id }}--}}
{{--                        <small class="ms-2">(@lang('Dossier') #{{ $dossier->id }})</small>--}}
{{--                    </h5>--}}
{{--                </div>--}}
{{--                <div class="card-body">--}}
{{--                    <form action="{{ route('admin.dossiers.factures.update', [$dossier->id, $facture->id]) }}" method="POST">--}}
{{--                        @csrf--}}
{{--                        @method('PUT')--}}

{{--                        <div class="row g-3">--}}
{{--                            --}}{{----}}{{----}}{{----}}{{-- Réf & Date --}}
{{--                            <x-form.input name="ref_facture" label="Réf Facture" value="{{ old('ref_facture', $facture->ref_facture) }}" col="6"/>--}}
{{--                            <x-form.date name="date_facture" label="Date Facture" value="{{ old('date_facture', $facture->date_facture) }}" col="6"/>--}}

{{--                            --}}{{----}}{{----}}{{----}}{{-- Bon Commande & Période --}}
{{--                            <x-form.input name="bon_commande" label="Bon de Commande" value="{{ old('bon_commande', $facture->bon_commande) }}" col="6"/>--}}
{{--                            <x-form.input name="periode" label="Période" value="{{ old('periode', $facture->periode) }}" col="6"/>--}}

{{--                            --}}{{----}}{{----}}{{----}}{{-- Montant HT & TVA --}}
{{--                            <x-form.input type="number" step="0.01" name="montant_ht" id="montant_ht"--}}
{{--                                          label="Montant HT" value="{{ old('montant_ht', $facture->montant_ht) }}" col="6"/>--}}
{{--                            <x-form.input type="number" step="0.01" name="taux_tva" id="taux_tva"--}}
{{--                                          label="TVA (%)" value="{{ old('taux_tva', $facture->taux_tva) }}" col="6"/>--}}

{{--                            --}}{{----}}{{----}}{{----}}{{-- Montant TTC auto --}}
{{--                            <x-form.input type="number" step="0.01" name="montant_ttc" id="montant_ttc"--}}
{{--                                          label="Montant TTC" value="{{ old('montant_ttc', $facture->montant_ttc) }}"--}}
{{--                                          col="6" readonly/>--}}

{{--                            --}}{{----}}{{----}}{{----}}{{-- Observation --}}
{{--                            <x-form.textarea name="observation" label="Observation" col="12">--}}
{{--                                {{ old('observation', $facture->observation) }}--}}
{{--                            </x-form.textarea>--}}
{{--                        </div>--}}

{{--                        <div class="d-flex justify-content-between mt-4">--}}
{{--                            <a href="{{ route('admin.dossiers.details', $dossier->id) }}" class="btn btn--dark">--}}
{{--                                <i class="las la-arrow-left"></i> @lang('Annuler')--}}
{{--                            </a>--}}
{{--                            <button type="submit" class="btn btn--primary">--}}
{{--                                <i class="las la-save"></i> @lang('Mettre à jour')--}}
{{--                            </button>--}}
{{--                        </div>--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--@endsection--}}

{{--@push('script')--}}
{{--    <script>--}}
{{--        (function($){--}}
{{--            "use strict";--}}

{{--            function calcTTC() {--}}
{{--                let ht = parseFloat($('#montant_ht').val()) || 0;--}}
{{--                let tva = parseFloat($('#taux_tva').val()) || 0;--}}
{{--                let ttc = ht + (ht * tva / 100);--}}
{{--                $('#montant_ttc').val(ttc.toFixed(2));--}}
{{--            }--}}

{{--            $('#montant_ht, #taux_tva').on('input', calcTTC);--}}
{{--            $(document).ready(calcTTC);--}}

{{--        })(jQuery);--}}
{{--    </script>--}}
{{--@endpush--}}

{{--@extends('admin.layouts.app')--}}

{{--@section('panel')--}}
{{--    <div class="row justify-content-center">--}}
{{--        <div class="col-xl-10 col-lg-12">--}}
{{--            <div class="card box--shadow1 border-0">--}}
{{--                <div class="card-header bg--primary text-white">--}}
{{--                    <h5 class="mb-0">--}}
{{--                        <i class="las la-file-invoice"></i>--}}
{{--                        @lang('Modifier Facture') #{{ $facture->id }}--}}
{{--                        <small class="ms-2">(@lang('Dossier') #{{ $dossier->id }})</small>--}}
{{--                    </h5>--}}
{{--                </div>--}}
{{--                <div class="card-body">--}}
{{--                    <form action="{{ route('admin.dossiers.factures.update', [$dossier->id, $facture->id]) }}" method="POST">--}}
{{--                        @csrf--}}
{{--                        @method('PUT')--}}

{{--                        <div class="row g-3">--}}
{{--                            --}}{{----}}{{-- Réf & Date --}}
{{--                            <div class="col-md-6">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="ref_facture">@lang('Réf Facture')</label>--}}
{{--                                    <input type="text" class="form-control" id="ref_facture" name="ref_facture"--}}
{{--                                           value="{{ old('ref_facture', $facture->ref_facture) }}">--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <div class="col-md-6">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="date_facture">@lang('Date Facture')</label>--}}
{{--                                    <input type="date" class="form-control" id="date_facture" name="date_facture"--}}
{{--                                           value="{{ old('date_facture', $facture->date_facture) }}">--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <div class="col-md-6">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="direction_id">@lang('Direction')</label>--}}
{{--                                    <select name="direction_id" id="direction_id" class="form-control">--}}
{{--                                        <option value="" >-- Choisir une direction --</option>--}}
{{--                                        @foreach($directions as $direction)--}}

{{--                                            <option value="{{ $direction->id }}"--}}
{{--                                                {{ old('direction_id', $facture->direction_id) == $direction->id ? 'selected' : '' }}>--}}
{{--                                                {{ $direction->name }}--}}
{{--                                            </option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            --}}{{----}}{{-- Bon Commande & Période --}}
{{--                            <div class="col-md-6">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="bon_commande">@lang('Bon de Commande')</label>--}}
{{--                                    <input type="text" class="form-control" id="bon_commande" name="bon_commande"--}}
{{--                                           value="{{ old('bon_commande', $facture->bon_commande) }}">--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <div class="col-md-6">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="periode">@lang('Période')</label>--}}
{{--                                    <input type="text" class="form-control" id="periode" name="periode"--}}
{{--                                           value="{{ old('periode', $facture->periode) }}">--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            --}}{{----}}{{-- Montants --}}
{{--                            <div class="col-md-4">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="montant_ht">@lang('Montant HT')</label>--}}
{{--                                    <input type="number" step="0.01" class="form-control" id="montant_ht" name="montant_ht"--}}
{{--                                           value="{{ old('montant_ht', $facture->montant_ht) }}">--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <div class="col-md-4">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="taux_tva">@lang('TVA (%)')</label>--}}
{{--                                    <input type="number" step="0.01" class="form-control" id="taux_tva" name="taux_tva"--}}
{{--                                           value="{{ old('taux_tva', $facture->taux_tva) }}">--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <div class="col-md-4">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="montant_ttc">@lang('Montant TTC')</label>--}}
{{--                                    <input type="number" step="0.01" class="form-control" id="montant_ttc" name="montant_ttc"--}}
{{--                                           value="{{ old('montant_ttc', $facture->montant_ttc) }}" readonly>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            --}}{{----}}{{-- International fields --}}
{{--                            <div class="col-md-4">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="monnaie">@lang('Monnaie')</label>--}}
{{--                                    <select id="monnaie" name="monnaie" class="form-control">--}}
{{--                                        <option value="" disabled>-- Choisir --</option>--}}
{{--                                        <option value="USD" {{ old('monnaie', $facture->monnaie) == 'USD' ? 'selected' : '' }}>💵 USD</option>--}}
{{--                                        <option value="EUR" {{ old('monnaie', $facture->monnaie) == 'EUR' ? 'selected' : '' }}>💶 EUR</option>--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <div class="col-md-4">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="taux_conversion">@lang('Taux de Conversion')</label>--}}
{{--                                    <input type="number" step="0.0001" class="form-control" id="taux_conversion" name="taux_conversion"--}}
{{--                                           value="{{ old('taux_conversion', $facture->taux_conversion) }}">--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <div class="col-md-4">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="montant_ttc_local">@lang('Montant TTC Local')</label>--}}
{{--                                    <input type="number" step="0.01" class="form-control" id="montant_ttc_local" name="montant_ttc_local"--}}
{{--                                           value="{{ old('montant_ttc_local', $facture->montant_ttc_local) }}" readonly>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            --}}{{----}}{{-- Observation --}}
{{--                            <div class="col-md-12">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="observation">@lang('Observation')</label>--}}
{{--                                    <textarea class="form-control" id="observation" name="observation" rows="3">{{ old('observation', $facture->observation) }}</textarea>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="d-flex justify-content-between mt-4">--}}
{{--                            <a href="{{ route('admin.dossiers.details', $dossier->id) }}" class="btn btn--dark">--}}
{{--                                <i class="las la-arrow-left"></i> @lang('Annuler')--}}
{{--                            </a>--}}
{{--                            <button type="submit" class="btn btn--primary">--}}
{{--                                <i class="las la-save"></i> @lang('Mettre à jour')--}}
{{--                            </button>--}}
{{--                        </div>--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--@endsection--}}

{{--@push('script')--}}
{{--    <script>--}}
{{--        (function($){--}}
{{--            "use strict";--}}

{{--            function calcTTC() {--}}
{{--                let ht = parseFloat($('#montant_ht').val()) || 0;--}}
{{--                let tva = parseFloat($('#taux_tva').val()) || 0;--}}
{{--                let ttc = ht + (ht * tva / 100);--}}
{{--                $('#montant_ttc').val(ttc.toFixed(2));--}}

{{--                let monnaie = $('#monnaie').val();--}}
{{--                let taux = parseFloat($('#taux_conversion').val()) || 0;--}}
{{--                if(monnaie && taux > 0){--}}
{{--                    let montantLocal = ttc * taux;--}}
{{--                    $('#montant_ttc_local').val(montantLocal.toFixed(2));--}}
{{--                }--}}
{{--            }--}}

{{--            $('#montant_ht, #taux_tva, #taux_conversion, #monnaie').on('input change', calcTTC);--}}
{{--            $(document).ready(calcTTC);--}}

{{--        })(jQuery);--}}
{{--    </script>--}}
{{--@endpush--}}

{{--@extends('admin.layouts.app')--}}

{{--@section('panel')--}}
{{--    <div class="row justify-content-center">--}}
{{--        <div class="col-xl-10 col-lg-12">--}}
{{--            <div class="card box--shadow1 border-0">--}}
{{--                <div class="card-header bg--primary text-white d-flex align-items-center justify-content-between">--}}
{{--                    <h5 class="mb-0">--}}
{{--                        <i class="las la-file-invoice"></i>--}}
{{--                        @lang('Modifier Facture') #{{ $facture->id }}--}}
{{--                        <small class="ms-2">(@lang('Dossier') #{{ $dossier->id }})</small>--}}
{{--                    </h5>--}}
{{--                </div>--}}

{{--                <div class="card-body">--}}
{{--                    <form action="{{ route('admin.dossiers.factures.update', [$dossier->id, $facture->id]) }}" method="POST" enctype="multipart/form-data">--}}
{{--                        @csrf--}}
{{--                        @method('PUT')--}}

{{--                        --}}{{-- Alerts --}}
{{--                        @if ($errors->any())--}}
{{--                            <div class="alert alert--danger">--}}
{{--                                <ul class="mb-0">--}}
{{--                                    @foreach ($errors->all() as $e)--}}
{{--                                        <li>{{ $e }}</li>--}}
{{--                                    @endforeach--}}
{{--                                </ul>--}}
{{--                            </div>--}}
{{--                        @endif--}}

{{--                        <div class="row g-3">--}}
{{--                            --}}{{-- ===== Meta ===== --}}
{{--                            <div class="col-md-3">--}}
{{--                                <label class="form-label" for="type_dossier">@lang('Type')</label>--}}
{{--                                <select name="type_dossier" id="type_dossier" class="form-control" required>--}}
{{--                                    @php $tp = old('type_dossier', $facture->type_dossier ?? 'national'); @endphp--}}
{{--                                    <option value="national" {{ $tp === 'national' ? 'selected' : '' }}>@lang('National')</option>--}}
{{--                                    <option value="international" {{ $tp === 'international' ? 'selected' : '' }}>@lang('International')</option>--}}
{{--                                </select>--}}
{{--                            </div>--}}

{{--                            <div class="col-md-3">--}}
{{--                                <label class="form-label" for="ref_facture">@lang('Réf Facture')</label>--}}
{{--                                <input type="text" id="ref_facture" name="ref_facture" class="form-control"--}}
{{--                                       value="{{ old('ref_facture', $facture->ref_facture) }}">--}}
{{--                            </div>--}}

{{--                            <div class="col-md-3">--}}
{{--                                <label class="form-label" for="date_facture">@lang('Date Facture')</label>--}}
{{--                                <input type="date" id="date_facture" name="date_facture" class="form-control"--}}
{{--                                       value="{{ old('date_facture', $facture->date_facture?->format('Y-m-d')) }}">--}}
{{--                            </div>--}}

{{--                            <div class="col-md-3">--}}
{{--                                <label class="form-label" for="bon_commande">@lang('Bon de Commande')</label>--}}
{{--                                <input type="text" id="bon_commande" name="bon_commande" class="form-control"--}}
{{--                                       value="{{ old('bon_commande', $facture->bon_commande) }}">--}}
{{--                            </div>--}}

{{--                            <div class="col-md-4">--}}
{{--                                <label class="form-label" for="numero_contrat">@lang('Numéro de Contrat')</label>--}}
{{--                                <input type="text" id="numero_contrat" name="numero_contrat" class="form-control"--}}
{{--                                       value="{{ old('numero_contrat', $facture->numero_contrat) }}">--}}
{{--                            </div>--}}

{{--                            <div class="col-md-4">--}}
{{--                                <label class="form-label" for="periode">@lang('Période')</label>--}}
{{--                                <input type="text" id="periode" name="periode" class="form-control"--}}
{{--                                       value="{{ old('periode', $facture->periode) }}">--}}
{{--                            </div>--}}

{{--                            <div class="col-md-4">--}}
{{--                                <label class="form-label" for="direction_id">@lang('Direction')</label>--}}
{{--                                <select name="direction_id" id="direction_id" class="form-control">--}}
{{--                                    <option value="">-- @lang('Choisir une direction') --</option>--}}
{{--                                    @foreach($directions as $direction)--}}
{{--                                        <option value="{{ $direction->id }}"--}}
{{--                                            {{ (string)old('direction_id', $facture->direction_id) === (string)$direction->id ? 'selected' : '' }}>--}}
{{--                                            {{ $direction->name }}--}}
{{--                                        </option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                            </div>--}}

{{--                            --}}{{-- If you also want compte on edit; keep or remove as you wish --}}
{{--                            @isset($comptes)--}}
{{--                                <div class="col-md-6">--}}
{{--                                    <label class="form-label" for="compte_id">@lang('Compte Comptable')</label>--}}
{{--                                    <select name="compte_id" id="compte_id" class="form-control">--}}
{{--                                        <option value="">-- @lang('Choisir un compte') --</option>--}}
{{--                                        @foreach($comptes as $compte)--}}
{{--                                            <option value="{{ $compte->id }}"--}}
{{--                                                {{ (string)old('compte_id', $facture->compte_id) === (string)$compte->id ? 'selected' : '' }}>--}}
{{--                                                {{ $compte->code }} - {{ $compte->name }}--}}
{{--                                            </option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            @endisset--}}

{{--                            <div class="col-12">--}}
{{--                                <label class="form-label" for="objet">@lang('Objet')</label>--}}
{{--                                <input type="text" id="objet" name="objet" class="form-control"--}}
{{--                                       value="{{ old('objet', $facture->objet) }}">--}}
{{--                            </div>--}}

{{--                            --}}{{-- ===== NATIONAL (DZD) ===== --}}
{{--                            <div class="col-12" id="wrapNational">--}}
{{--                                <div class="card border-0 shadow-sm mt-2">--}}
{{--                                    <div class="card-header bg--light-blue">--}}
{{--                                        <strong>@lang('Montants en Dinars (DZD)')</strong>--}}
{{--                                    </div>--}}
{{--                                    <div class="card-body">--}}
{{--                                        <div class="row g-3">--}}
{{--                                            <div class="col-md-3">--}}
{{--                                                <label class="form-label" for="montant_ht">@lang('Montant HT')</label>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    <input type="number" step="0.01" min="0" id="montant_ht" name="montant_ht" class="form-control"--}}
{{--                                                           value="{{ old('montant_ht', $facture->montant_ht) }}">--}}
{{--                                                    <span class="input-group-text">DZD</span>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}

{{--                                            <div class="col-md-3">--}}
{{--                                                <label class="form-label" for="taux_tva">@lang('TVA')</label>--}}
{{--                                                @php--}}
{{--                                                    $tvaVal = old('taux_tva', $facture->taux_tva);--}}
{{--                                                    $isCustom = $tvaVal !== null && !in_array((string)$tvaVal, ['0','9','19'], true);--}}
{{--                                                @endphp--}}
{{--                                                <select id="taux_tva" name="taux_tva" class="form-control">--}}
{{--                                                    <option value="0"  {{ $tvaVal == 0 ? 'selected' : '' }}>0%</option>--}}
{{--                                                    <option value="9"  {{ $tvaVal == 9 ? 'selected' : '' }}>9%</option>--}}
{{--                                                    <option value="19" {{ $tvaVal == 19 ? 'selected' : '' }}>19%</option>--}}
{{--                                                    <option value="custom" {{ $isCustom ? 'selected' : '' }}>@lang('Autre')</option>--}}
{{--                                                </select>--}}
{{--                                            </div>--}}

{{--                                            <div class="col-md-3 {{ $isCustom ? '' : 'd-none' }}" id="boxCustomTva">--}}
{{--                                                <label class="form-label" for="custom_tva">@lang('TVA personnalisée (%)')</label>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    <input type="number" step="0.01" min="0" id="custom_tva" name="custom_tva" class="form-control"--}}
{{--                                                           value="{{ old('custom_tva', $isCustom ? $tvaVal : $facture->custom_tva) }}" placeholder="12.50">--}}
{{--                                                    <span class="input-group-text">%</span>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}

{{--                                            <div class="col-md-3">--}}
{{--                                                <label class="form-label" for="remise_percent">@lang('Remise (%)')</label>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    <input type="number" step="0.01" min="0" id="remise_percent" name="remise_percent" class="form-control"--}}
{{--                                                           value="{{ old('remise_percent', $facture->remise_percent) }}">--}}
{{--                                                    <span class="input-group-text">%</span>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}

{{--                                            <div class="col-md-3">--}}
{{--                                                <label class="form-label" for="taxe_percent">@lang('Taxe (%)')</label>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    <input type="number" step="0.01" min="0" id="taxe_percent" name="taxe_percent" class="form-control"--}}
{{--                                                           value="{{ old('taxe_percent', $facture->taxe_percent) }}">--}}
{{--                                                    <span class="input-group-text">%</span>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}

{{--                                            <div class="col-md-3">--}}
{{--                                                <label class="form-label" for="timbre_percent">@lang('Droit de timbre (%)')</label>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    <input type="number" step="0.01" min="0" id="timbre_percent" name="timbre_percent" class="form-control"--}}
{{--                                                           value="{{ old('timbre_percent', $facture->timbre_percent) }}">--}}
{{--                                                    <span class="input-group-text">%</span>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}

{{--                                            <div class="col-md-3">--}}
{{--                                                <label class="form-label" for="montant_ttc">@lang('Montant TTC')</label>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    <input type="number" step="0.01" id="montant_ttc" name="montant_ttc" class="form-control"--}}
{{--                                                           value="{{ old('montant_ttc', $facture->montant_ttc) }}">--}}
{{--                                                    <span class="input-group-text">DZD</span>--}}
{{--                                                </div>--}}
{{--                                                <div class="form-text">@lang('Aperçu côté client; normalisé côté serveur.')</div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            --}}{{-- ===== INTERNATIONAL (Devise) ===== --}}
{{--                            <div class="col-12" id="wrapInternational">--}}
{{--                                <div class="card border-0 shadow-sm mt-2">--}}
{{--                                    <div class="card-header bg--light-blue d-flex justify-content-between align-items-center">--}}
{{--                                        <strong>@lang('Montants en Devise')</strong>--}}
{{--                                        <span class="badge bg-secondary">@lang('Dinar dérivé pour aperçu')</span>--}}
{{--                                    </div>--}}
{{--                                    <div class="card-body">--}}
{{--                                        <div class="row g-3">--}}
{{--                                            <div class="col-md-3">--}}
{{--                                                <label class="form-label" for="monnaie">@lang('Monnaie')</label>--}}
{{--                                                <select id="monnaie" name="monnaie" class="form-control">--}}
{{--                                                    <option value="">-- @lang('Choisir') --</option>--}}
{{--                                                    <option value="USD" {{ old('monnaie', $facture->monnaie) === 'USD' ? 'selected' : '' }}>💵 USD</option>--}}
{{--                                                    <option value="EUR" {{ old('monnaie', $facture->monnaie) === 'EUR' ? 'selected' : '' }}>💶 EUR</option>--}}
{{--                                                </select>--}}
{{--                                            </div>--}}

{{--                                            <div class="col-md-3">--}}
{{--                                                <label class="form-label" for="montant_devise">@lang('Montant en Devise')</label>--}}
{{--                                                <input type="number" step="0.01" min="0" id="montant_devise" name="montant_devise" class="form-control"--}}
{{--                                                       value="{{ old('montant_devise', $facture->montant_devise) }}">--}}
{{--                                            </div>--}}

{{--                                            <div class="col-md-3">--}}
{{--                                                <label class="form-label" for="ibs_percent">@lang('IBS (%)')</label>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    <input type="number" step="0.01" min="0" id="ibs_percent" name="ibs_percent" class="form-control"--}}
{{--                                                           value="{{ old('ibs_percent', $facture->ibs_percent) }}">--}}
{{--                                                    <span class="input-group-text">%</span>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}

{{--                                            <div class="col-md-3">--}}
{{--                                                <label class="form-label" for="taux_conversion">@lang('Taux de Conversion')</label>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    <input type="number" step="0.0001" min="0" id="taux_conversion" name="taux_conversion" class="form-control"--}}
{{--                                                           value="{{ old('taux_conversion', $facture->taux_conversion) }}">--}}
{{--                                                    <span class="input-group-text">DZD/1</span>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}

{{--                                            --}}{{-- Readonly helpers (preview) --}}
{{--                                            <div class="col-md-4">--}}
{{--                                                <label class="form-label">@lang('IBS (devise)')</label>--}}
{{--                                                <input type="number" step="0.01" class="form-control" id="ibs_devise" name="ibs_devise"--}}
{{--                                                       value="{{ old('ibs_devise', $facture->ibs_devise) }}" readonly>--}}
{{--                                            </div>--}}

{{--                                            <div class="col-md-4">--}}
{{--                                                <label class="form-label">@lang('Devise après IBS')</label>--}}
{{--                                                <input type="number" step="0.01" class="form-control" id="montant_devise_net" name="montant_devise_net"--}}
{{--                                                       value="{{ old('montant_devise_net', $facture->montant_devise_net) }}" readonly>--}}
{{--                                            </div>--}}

{{--                                            <div class="col-md-4">--}}
{{--                                                <label class="form-label">@lang('TTC Local (DZD)')</label>--}}
{{--                                                <input type="number" step="0.01" class="form-control" id="montant_ttc_local" name="montant_ttc_local"--}}
{{--                                                       value="{{ old('montant_ttc_local', $facture->montant_ttc_local) }}" readonly>--}}
{{--                                            </div>--}}

{{--                                            --}}{{-- Also show derived HT local (preview only) --}}
{{--                                            <div class="col-md-4">--}}
{{--                                                <label class="form-label">@lang('HT Local (DZD) après IBS')</label>--}}
{{--                                                <input type="number" step="0.01" class="form-control" id="montant_ht_local" name="montant_ht_local"--}}
{{--                                                       value="{{ old('montant_ht_local', $facture->montant_ht_local) }}" readonly>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            --}}{{-- ===== Observation ===== --}}
{{--                            <div class="col-12">--}}
{{--                                <label class="form-label" for="observation">@lang('Observation')</label>--}}
{{--                                <textarea id="observation" name="observation" rows="3" class="form-control">{{ old('observation', $facture->observation) }}</textarea>--}}
{{--                            </div>--}}

{{--                            --}}{{-- ===== Optional: replace attachments ===== --}}
{{--                            @if(method_exists($facture, 'files'))--}}
{{--                                <div class="col-12">--}}
{{--                                    <label class="form-label d-block">@lang('Pièces jointes')</label>--}}
{{--                                    <div class="mb-2">--}}
{{--                                        @forelse($facture->files as $f)--}}
{{--                                            <div class="small text-muted">• {{ $f->path }}</div>--}}
{{--                                        @empty--}}
{{--                                            <div class="small text-muted">@lang('Aucune pièce jointe.')</div>--}}
{{--                                        @endforelse--}}
{{--                                    </div>--}}
{{--                                    <input type="file" name="attachments[]" multiple class="form-control">--}}
{{--                                    <div class="form-text">@lang('Joindre jusqu’à 5 fichiers (jpeg, png, pdf, doc, docx).')</div>--}}
{{--                                </div>--}}
{{--                            @endif--}}
{{--                        </div>--}}

{{--                        <div class="d-flex justify-content-between mt-4">--}}
{{--                            <a href="{{ route('admin.dossiers.factures.index', $dossier->id) }}" class="btn btn--dark">--}}
{{--                                <i class="las la-arrow-left"></i> @lang('Annuler')--}}
{{--                            </a>--}}
{{--                            <button type="submit" class="btn btn--primary">--}}
{{--                                <i class="las la-save"></i> @lang('Mettre à jour')--}}
{{--                            </button>--}}
{{--                        </div>--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--@endsection--}}

{{--@push('style')--}}
{{--    <style>--}}
{{--        .sticky-summary { position: sticky; top: 12px; }--}}
{{--        .input-group > .input-group-text { min-width: 64px; justify-content: center; }--}}
{{--    </style>--}}
{{--@endpush--}}

{{--@push('script')--}}
{{--    <script>--}}
{{--        (function($){--}}
{{--            "use strict";--}}

{{--            const toNum = (v) => {--}}
{{--                if (v === undefined || v === null) return 0;--}}
{{--                const s = String(v).replace(',', '.');--}}
{{--                const n = parseFloat(s);--}}
{{--                return isFinite(n) ? n : 0;--}}
{{--            };--}}

{{--            function toggleBlocks() {--}}
{{--                const intl = $('#type_dossier').val() === 'international';--}}
{{--                $('#wrapNational').toggleClass('d-none', intl);--}}
{{--                $('#wrapInternational').toggleClass('d-none', !intl);--}}
{{--            }--}}

{{--            function toggleCustomTva() {--}}
{{--                const v = $('#taux_tva').val();--}}
{{--                $('#boxCustomTva').toggleClass('d-none', v !== 'custom');--}}
{{--            }--}}

{{--            function currentTva() {--}}
{{--                const sel = $('#taux_tva').val();--}}
{{--                if (sel === 'custom') return toNum($('#custom_tva').val());--}}
{{--                return toNum(sel);--}}
{{--            }--}}

{{--            // NATIONAL preview calc--}}
{{--            function calcNational() {--}}
{{--                if ($('#wrapNational').hasClass('d-none')) return;--}}

{{--                const ht = toNum($('#montant_ht').val());--}}
{{--                const tva = currentTva();--}}
{{--                const remise = toNum($('#remise_percent').val());--}}
{{--                const taxe   = toNum($('#taxe_percent').val());--}}
{{--                const timbre = toNum($('#timbre_percent').val());--}}

{{--                const remiseAmt = ht * (remise / 100);--}}
{{--                const tvaAmt    = ht * (tva    / 100);--}}
{{--                const taxeAmt   = ht * (taxe   / 100);--}}
{{--                const timbAmt   = ht * (timbre / 100);--}}

{{--                const ttc = (ht - remiseAmt) + tvaAmt + taxeAmt + timbAmt;--}}
{{--                if (!isNaN(ttc)) $('#montant_ttc').val(ttc.toFixed(2));--}}
{{--            }--}}

{{--            // INTERNATIONAL preview calc--}}
{{--            function calcInternational() {--}}
{{--                if ($('#wrapInternational').hasClass('d-none')) return;--}}

{{--                const dev  = toNum($('#montant_devise').val());--}}
{{--                const ibsP = toNum($('#ibs_percent').val());--}}
{{--                const taux = toNum($('#taux_conversion').val());--}}

{{--                const ibsDev = dev * (ibsP / 100);--}}
{{--                const devNet = Math.max(0, dev - ibsDev);--}}
{{--                const ttcLoc = dev  * taux;     // TTC local dérivé--}}
{{--                const htLoc  = devNet * taux;   // HT local après IBS--}}

{{--                $('#ibs_devise').val(ibsDev.toFixed(2));--}}
{{--                $('#montant_devise_net').val(devNet.toFixed(2));--}}
{{--                $('#montant_ttc_local').val(taux > 0 ? ttcLoc.toFixed(2) : '');--}}
{{--                $('#montant_ht_local').val(taux > 0 ? htLoc.toFixed(2) : '');--}}
{{--            }--}}

{{--            function recalc() {--}}
{{--                toggleCustomTva();--}}
{{--                calcNational();--}}
{{--                calcInternational();--}}
{{--            }--}}

{{--            // Events--}}
{{--            $('#type_dossier').on('change', function(){ toggleBlocks(); recalc(); });--}}
{{--            $('#taux_tva, #custom_tva, #montant_ht, #remise_percent, #taxe_percent, #timbre_percent')--}}
{{--                .on('input change', recalc);--}}
{{--            $('#montant_devise, #ibs_percent, #taux_conversion, #monnaie')--}}
{{--                .on('input change', recalc);--}}

{{--            // Init--}}
{{--            toggleBlocks();--}}
{{--            recalc();--}}

{{--        })(jQuery);--}}
{{--    </script>--}}
{{--@endpush--}}

@extends('admin.layouts.app')

@section('panel')
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12">
            <div class="card box--shadow1 border-0">
                <div class="card-header bg--primary text-white d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">
                        <i class="las la-file-invoice"></i>
                        @lang('Modifier Facture') #{{ $facture->id }}
                        <small class="ms-2">(@lang('Dossier') #{{ $dossier->id }})</small>
                    </h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.dossiers.factures.update', [$dossier->id, $facture->id]) }}"
                          method="POST" enctype="multipart/form-data" class="disableSubmission">
                        @csrf
                        @method('PUT')

                        {{-- Alerts --}}
                        @if ($errors->any())
                            <div class="alert alert--danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $e)
                                        <li>{{ $e }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- ===== Meta ===== --}}
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label" for="type_dossier">@lang('Type')</label>
                                @php $tp = old('type_dossier', $facture->type_dossier ?? $dossier->type_dossier ?? 'national'); @endphp
                                <select name="type_dossier" id="type_dossier" class="form-control" required>
                                    <option value="national" {{ $tp === 'national' ? 'selected' : '' }}>@lang('National')</option>
                                    <option value="international" {{ $tp === 'international' ? 'selected' : '' }}>@lang('International')</option>
                                </select>
{{--                                <div class="form-text">@lang('Choisissez “International” pour activer la conversion devise.')</div>--}}
                            </div>

                            <div class="col-md-3">
                                <label class="form-label" for="ref_facture">@lang('Réf Facture')</label>
                                <input type="text" id="ref_facture" name="ref_facture" class="form-control"
                                       value="{{ old('ref_facture', $facture->ref_facture) }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label" for="date_facture">@lang('Date Facture')</label>
                                <input type="date" id="date_facture" name="date_facture" class="form-control"
                                       value="{{ old('date_facture', $facture->date_facture?->format('Y-m-d')) }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label" for="bon_commande">@lang('Bon de Commande')</label>
                                <select name="bon_commande" id="bon_commande" class="form-control" required>
                                    <option value="">-- @lang('Choisir') --</option>
                                    <option value="Bon de Commande" {{ old('bon_commande', $facture->bon_commande) == 'Bon de Commande' ? 'selected' : '' }}>
                                        @lang('Bon Commande')
                                    </option>
                                    <option value="Facture" {{ old('bon_commande', $facture->bon_commande) == 'Facture' ? 'selected' : '' }}>
                                        @lang('Facture')
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label" for="numero_contrat">@lang('Numéro de Contrat')</label>
                                <input type="text" id="numero_contrat" name="numero_contrat" class="form-control"
                                       value="{{ old('numero_contrat', $facture->numero_contrat) }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label" for="periode">@lang('Période')</label>
                                <input type="text" id="periode" name="periode" class="form-control"
                                       value="{{ old('periode', $facture->periode) }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label" for="direction_id">@lang('Direction')</label>
                                <select name="direction_id" id="direction_id" class="form-control select2">
                                    <option value="">-- @lang('Choisir une direction') --</option>
                                    @foreach($directions as $direction)
                                        <option value="{{ $direction->id }}"
                                            {{ (string)old('direction_id', $facture->direction_id) === (string)$direction->id ? 'selected' : '' }}>
                                            {{ $direction->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            @isset($comptes)
                                <div class="col-md-6">
                                    <label class="form-label" for="compte_id">@lang('Compte Comptable')</label>
                                    <select name="compte_id" id="compte_id" class="form-control select2">
                                        <option value="">-- @lang('Choisir un compte') --</option>
                                        @foreach($comptes as $compte)
                                            <option value="{{ $compte->id }}"
                                                {{ (string)old('compte_id', $facture->compte_id) === (string)$compte->id ? 'selected' : '' }}>
                                                {{ $compte->code }} - {{ $compte->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endisset

                            <div class="col-12">
                                <label class="form-label" for="objet">@lang('Objet')</label>
                                <input type="text" id="objet" name="objet" class="form-control"
                                       value="{{ old('objet', $facture->objet) }}">
                            </div>
                        </div>

                        {{-- ===== NATIONAL (DZD) ===== --}}
                        @php
                            $tvaVal   = old('taux_tva', $facture->taux_tva);
                            $isCustom = $tvaVal !== null && !in_array((string)$tvaVal, ['0','9','19'], true);
                        @endphp

                        <div id="wrapNational" class="{{ $tp === 'international' ? 'd-none' : '' }}">
                            <div class="card border-0 shadow-sm mt-3">
                                <div class="card-header bg--light-blue">
                                    <strong>@lang('Montants en Dinars (DZD)')</strong>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label class="form-label" for="montant_ht">@lang('Montant HT')</label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" min="0" id="montant_ht" name="montant_ht" class="form-control"
                                                       value="{{ old('montant_ht', $facture->montant_ht) }}">
                                                <span class="input-group-text">DZD</span>
                                            </div>
                                            <div class="form-text">@lang('Base de tous les calculs.')</div>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label" for="taux_tva">@lang('TVA')</label>
                                            <select id="taux_tva" name="taux_tva" class="form-control">
                                                <option value="0"  {{ $tvaVal == 0 ? 'selected' : '' }}>0%</option>
                                                <option value="9"  {{ $tvaVal == 9 ? 'selected' : '' }}>9%</option>
                                                <option value="19" {{ $tvaVal == 19 ? 'selected' : '' }}>19%</option>
                                                <option value="custom" {{ $isCustom ? 'selected' : '' }}>@lang('Autre')</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3 {{ $isCustom ? '' : 'd-none' }}" id="boxCustomTva">
                                            <label class="form-label" for="custom_tva">@lang('TVA personnalisée (%)')</label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" min="0" id="custom_tva" name="custom_tva" class="form-control"
                                                       value="{{ old('custom_tva', $isCustom ? $tvaVal : $facture->custom_tva) }}" placeholder="12.50">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label" for="remise_percent">@lang('Remise (%)')</label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" min="0" id="remise_percent" name="remise_percent" class="form-control"
                                                       value="{{ old('remise_percent', $facture->remise_percent) }}">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label" for="taxe_percent">@lang('Taxe (%)')</label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" min="0" id="taxe_percent" name="taxe_percent" class="form-control"
                                                       value="{{ old('taxe_percent', $facture->taxe_percent) }}">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label" for="timbre_percent">@lang('Droit de timbre (%)')</label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" min="0" id="timbre_percent" name="timbre_percent" class="form-control"
                                                       value="{{ old('timbre_percent', $facture->timbre_percent) }}">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label" for="montant_ttc">@lang('Montant TTC')</label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" id="montant_ttc" name="montant_ttc" class="form-control"
                                                       value="{{ old('montant_ttc', $facture->montant_ttc) }}" readonly>
                                                <span class="input-group-text">DZD</span>
                                            </div>
                                            <div class="form-text">
                                                @lang('Calcul : (HT − Remise(HT)) + TVA(HT) + Taxe(HT) + Timbre(HT).')
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ===== INTERNATIONAL (Devise) ===== --}}
                        <div id="wrapInternational" class="{{ $tp === 'international' ? '' : 'd-none' }}">
                            <div class="card border-0 shadow-sm mt-3">
                                <div class="card-header bg--light-blue d-flex justify-content-between align-items-center">
                                    <strong>@lang('Montants en Devise')</strong>
                                    <span class="badge bg-secondary">@lang('Le Dinar est dérivé')</span>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label class="form-label" for="monnaie">@lang('Monnaie')</label>
                                            <select id="monnaie" name="monnaie" class="form-control">
                                                <option value="">-- @lang('Choisir') --</option>
                                                <option value="USD" {{ old('monnaie', $facture->monnaie) === 'USD' ? 'selected' : '' }}>💵 USD</option>
                                                <option value="EUR" {{ old('monnaie', $facture->monnaie) === 'EUR' ? 'selected' : '' }}>💶 EUR</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label" for="montant_devise">@lang('Montant en Devise (source)')</label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" min="0" id="montant_devise" name="montant_devise" class="form-control"
                                                       value="{{ old('montant_devise', $facture->montant_devise) }}">
                                                <button type="button" class="btn btn-outline-secondary" id="resetDeviseFromDzd"
                                                        title="@lang('Réappliquer conversion depuis TTC DZD')">↻</button>
                                            </div>
                                            <div class="form-text">@lang('Source de vérité; DZD dérivés.')</div>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label" for="ibs_percent">@lang('IBS (%)')</label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" min="0" id="ibs_percent" name="ibs_percent" class="form-control"
                                                       value="{{ old('ibs_percent', $facture->ibs_percent) }}">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label" for="taux_conversion">@lang('Taux de Conversion')</label>
                                            <div class="input-group">
                                                <input type="number" step="0.0001" min="0" id="taux_conversion" name="taux_conversion" class="form-control"
                                                       value="{{ old('taux_conversion', $facture->taux_conversion) }}">
                                                <span class="input-group-text">DZD/1</span>
                                            </div>
                                        </div>

                                        {{-- Helpers / dérivés (readonly) --}}
                                        <div class="col-md-4">
                                            <label class="form-label" for="ibs_devise">@lang('IBS (devise)')</label>
                                            <input type="number" step="0.01" class="form-control" id="ibs_devise" name="ibs_devise"
                                                   value="{{ old('ibs_devise', $facture->ibs_percent) }}" >
                                            <div class="form-text">@lang('= devise × IBS%.')</div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label" for="montant_devise_net">@lang('Devise après IBS')</label>
                                            <input type="number" step="0.01" class="form-control" id="montant_devise_net" name="montant_devise_net"
                                                   value="{{ old('montant_devise_net', $facture->montant_devise_net) }}" readonly>
                                            <div class="form-text">@lang('= devise − IBS devise.')</div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label" for="montant_ttc_local">@lang('TTC Local (DZD)')</label>
                                            <input type="number" step="0.01" class="form-control" id="montant_ttc_local" name="montant_ttc_local"
                                                   value="{{ old('montant_ttc_local', $facture->montant_ttc_local) }}" readonly>
                                            <div class="form-text">@lang('= devise × taux.')</div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label" for="montant_ht_local">@lang('HT Local (DZD) après IBS')</label>
                                            <input type="number" step="0.01" class="form-control" id="montant_ht_local" name="montant_ht_local"
                                                   value="{{ old('montant_ht_local', $facture->montant_ht_local) }}" readonly>
                                            <div class="form-text">@lang('= devise après IBS × taux.')</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ===== Observation ===== --}}
                        <div class="row g-3 mt-2">
                            <div class="col-12">
                                <label class="form-label" for="observation">@lang('Observation')</label>
                                <textarea id="observation" name="observation" rows="3" class="form-control">{{ old('observation', $facture->observation) }}</textarea>
                            </div>
                        </div>

                        {{-- ===== Pièces jointes ===== --}}
                        @if(method_exists($facture, 'files'))
                            <div class="row g-3 mt-2">
                                <div class="col-12">
                                    <label class="form-label d-block">@lang('Pièces jointes existantes')</label>
                                    <div class="mb-2">
                                        @forelse($facture->files as $f)
                                            <div class="small text-muted">• {{ $f->path }}</div>
                                        @empty
                                            <div class="small text-muted">@lang('Aucune pièce jointe.')</div>
                                        @endforelse
                                    </div>
                                    <label class="form-label d-block">@lang('Remplacer / ajouter des pièces jointes')</label>
                                    <input type="file" name="attachments[]" multiple class="form-control"
                                           accept=".jpeg,.jpg,.png,.pdf,.doc,.docx">
                                    <div class="form-text">@lang('Jusqu’à 5 fichiers.')</div>
                                </div>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.dossiers.factures.index', $dossier->id) }}" class="btn btn--dark">
                                <i class="las la-arrow-left"></i> @lang('Annuler')
                            </a>
                            <button type="submit" class="btn btn--primary">
                                <i class="las la-save"></i> @lang('Mettre à jour')
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .select2-container { flex: 1; min-width: 0; }
        .select2-container .select2-selection { height: 38px !important; border-radius: 4px; }
        .sticky-summary { position: sticky; top: 12px; }
        .input-group > .input-group-text { min-width: 64px; justify-content: center; }
    </style>
@endpush

@push('script')
    <script>
        (function($){
            "use strict";

            const toNum = (v) => {
                if (v === undefined || v === null || v === '') return 0;
                const s = String(v).replace(',', '.');
                const n = parseFloat(s);
                return isFinite(n) ? n : 0;
            };

            function toggleBlocks() {
                const intl = $('#type_dossier').val() === 'international';
                $('#wrapNational').toggleClass('d-none', intl);
                $('#wrapInternational').toggleClass('d-none', !intl);
                // In international, HT is derived (readonly); in national, editable
                $('#montant_ht').prop('readonly', intl);
            }

            function toggleCustomTva() {
                const v = $('#taux_tva').val();
                $('#boxCustomTva').toggleClass('d-none', v !== 'custom');
                if (v !== 'custom') $('#custom_tva').val('');
            }

            function currentTva() {
                const sel = $('#taux_tva').val();
                if (sel === 'custom') return toNum($('#custom_tva').val());
                return toNum(sel);
            }

            // NATIONAL preview calc (server is source of truth)
            function calcNational() {
                if ($('#wrapNational').hasClass('d-none')) return;

                const ht = toNum($('#montant_ht').val());
                const tva = currentTva();
                const remise = toNum($('#remise_percent').val());
                const taxe   = toNum($('#taxe_percent').val());
                const timbre = toNum($('#timbre_percent').val());

                const remiseAmt = ht * (remise / 100);
                const tvaAmt    = ht * (tva    / 100);
                const taxeAmt   = ht * (taxe   / 100);
                const timbAmt   = ht * (timbre / 100);

                const ttc = (ht - remiseAmt) + tvaAmt + taxeAmt + timbAmt;
                $('#montant_ttc').val(ttc.toFixed(2));
            }

            // INTERNATIONAL preview calc (devise is the source)
            function calcInternational() {
                if ($('#wrapInternational').hasClass('d-none')) return;

                const dev  = toNum($('#montant_devise').val());
                const ibsP = toNum($('#ibs_percent').val());
                const taux = toNum($('#taux_conversion').val());

                const ibsDev = dev * (ibsP / 100);
                const devNet = Math.max(0, dev - ibsDev);
                const ttcLoc = dev  * taux;     // TTC local dérivé
                const htLoc  = devNet * taux;   // HT local après IBS

                // Helpers
                $('#ibs_devise').val(ibsDev.toFixed(2));
                $('#montant_devise_net').val(devNet.toFixed(2));
                $('#montant_ttc_local').val(taux > 0 ? ttcLoc.toFixed(2) : '');
                $('#montant_ht_local').val(taux > 0 ? htLoc.toFixed(2) : '');

                // Sync main DZD fields too (so user sees consistency with list/exports)
                $('#montant_ttc').val(taux > 0 ? ttcLoc.toFixed(2) : '');
                $('#montant_ht').val(taux > 0 ? htLoc.toFixed(2) : '');
            }

            function recalc() {
                toggleCustomTva();
                calcNational();
                calcInternational();
            }

            // Events
            $('#type_dossier').on('change', function(){ toggleBlocks(); recalc(); });
            $('#taux_tva, #custom_tva, #montant_ht, #remise_percent, #taxe_percent, #timbre_percent')
                .on('input change', recalc);
            $('#montant_devise, #ibs_percent, #taux_conversion, #monnaie')
                .on('input change', recalc);

            // Init
            toggleBlocks();
            recalc();

        })(jQuery);
    </script>
@endpush
