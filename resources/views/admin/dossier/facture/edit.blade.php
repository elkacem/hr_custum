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
{{--                            --}}{{-- Réf & Date --}}
{{--                            <x-form.input name="ref_facture" label="Réf Facture" value="{{ old('ref_facture', $facture->ref_facture) }}" col="6"/>--}}
{{--                            <x-form.date name="date_facture" label="Date Facture" value="{{ old('date_facture', $facture->date_facture) }}" col="6"/>--}}

{{--                            --}}{{-- Bon Commande & Période --}}
{{--                            <x-form.input name="bon_commande" label="Bon de Commande" value="{{ old('bon_commande', $facture->bon_commande) }}" col="6"/>--}}
{{--                            <x-form.input name="periode" label="Période" value="{{ old('periode', $facture->periode) }}" col="6"/>--}}

{{--                            --}}{{-- Montant HT & TVA --}}
{{--                            <x-form.input type="number" step="0.01" name="montant_ht" id="montant_ht"--}}
{{--                                          label="Montant HT" value="{{ old('montant_ht', $facture->montant_ht) }}" col="6"/>--}}
{{--                            <x-form.input type="number" step="0.01" name="taux_tva" id="taux_tva"--}}
{{--                                          label="TVA (%)" value="{{ old('taux_tva', $facture->taux_tva) }}" col="6"/>--}}

{{--                            --}}{{-- Montant TTC auto --}}
{{--                            <x-form.input type="number" step="0.01" name="montant_ttc" id="montant_ttc"--}}
{{--                                          label="Montant TTC" value="{{ old('montant_ttc', $facture->montant_ttc) }}"--}}
{{--                                          col="6" readonly/>--}}

{{--                            --}}{{-- Observation --}}
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

@extends('admin.layouts.app')

@section('panel')
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12">
            <div class="card box--shadow1 border-0">
                <div class="card-header bg--primary text-white">
                    <h5 class="mb-0">
                        <i class="las la-file-invoice"></i>
                        @lang('Modifier Facture') #{{ $facture->id }}
                        <small class="ms-2">(@lang('Dossier') #{{ $dossier->id }})</small>
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.dossiers.factures.update', [$dossier->id, $facture->id]) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            {{-- Réf & Date --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ref_facture">@lang('Réf Facture')</label>
                                    <input type="text" class="form-control" id="ref_facture" name="ref_facture"
                                           value="{{ old('ref_facture', $facture->ref_facture) }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_facture">@lang('Date Facture')</label>
                                    <input type="date" class="form-control" id="date_facture" name="date_facture"
                                           value="{{ old('date_facture', $facture->date_facture) }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="direction_id">@lang('Direction')</label>
                                    <select name="direction_id" id="direction_id" class="form-control">
                                        <option value="" >-- Choisir une direction --</option>
                                        @foreach($directions as $direction)

                                            <option value="{{ $direction->id }}"
                                                {{ old('direction_id', $facture->direction_id) == $direction->id ? 'selected' : '' }}>
                                                {{ $direction->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Bon Commande & Période --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bon_commande">@lang('Bon de Commande')</label>
                                    <input type="text" class="form-control" id="bon_commande" name="bon_commande"
                                           value="{{ old('bon_commande', $facture->bon_commande) }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="periode">@lang('Période')</label>
                                    <input type="text" class="form-control" id="periode" name="periode"
                                           value="{{ old('periode', $facture->periode) }}">
                                </div>
                            </div>

                            {{-- Montants --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="montant_ht">@lang('Montant HT')</label>
                                    <input type="number" step="0.01" class="form-control" id="montant_ht" name="montant_ht"
                                           value="{{ old('montant_ht', $facture->montant_ht) }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="taux_tva">@lang('TVA (%)')</label>
                                    <input type="number" step="0.01" class="form-control" id="taux_tva" name="taux_tva"
                                           value="{{ old('taux_tva', $facture->taux_tva) }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="montant_ttc">@lang('Montant TTC')</label>
                                    <input type="number" step="0.01" class="form-control" id="montant_ttc" name="montant_ttc"
                                           value="{{ old('montant_ttc', $facture->montant_ttc) }}" readonly>
                                </div>
                            </div>

                            {{-- International fields --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="monnaie">@lang('Monnaie')</label>
                                    <select id="monnaie" name="monnaie" class="form-control">
                                        <option value="" disabled>-- Choisir --</option>
                                        <option value="USD" {{ old('monnaie', $facture->monnaie) == 'USD' ? 'selected' : '' }}>💵 USD</option>
                                        <option value="EUR" {{ old('monnaie', $facture->monnaie) == 'EUR' ? 'selected' : '' }}>💶 EUR</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="taux_conversion">@lang('Taux de Conversion')</label>
                                    <input type="number" step="0.0001" class="form-control" id="taux_conversion" name="taux_conversion"
                                           value="{{ old('taux_conversion', $facture->taux_conversion) }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="montant_ttc_local">@lang('Montant TTC Local')</label>
                                    <input type="number" step="0.01" class="form-control" id="montant_ttc_local" name="montant_ttc_local"
                                           value="{{ old('montant_ttc_local', $facture->montant_ttc_local) }}" readonly>
                                </div>
                            </div>

                            {{-- Observation --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="observation">@lang('Observation')</label>
                                    <textarea class="form-control" id="observation" name="observation" rows="3">{{ old('observation', $facture->observation) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.dossiers.details', $dossier->id) }}" class="btn btn--dark">
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

@push('script')
    <script>
        (function($){
            "use strict";

            function calcTTC() {
                let ht = parseFloat($('#montant_ht').val()) || 0;
                let tva = parseFloat($('#taux_tva').val()) || 0;
                let ttc = ht + (ht * tva / 100);
                $('#montant_ttc').val(ttc.toFixed(2));

                let monnaie = $('#monnaie').val();
                let taux = parseFloat($('#taux_conversion').val()) || 0;
                if(monnaie && taux > 0){
                    let montantLocal = ttc * taux;
                    $('#montant_ttc_local').val(montantLocal.toFixed(2));
                }
            }

            $('#montant_ht, #taux_tva, #taux_conversion, #monnaie').on('input change', calcTTC);
            $(document).ready(calcTTC);

        })(jQuery);
    </script>
@endpush
