@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route('admin.dossiers.store') }}" method="post" class="disableSubmission" enctype="multipart/form-data">
{{--                <form action="" method="post" class="disableSubmission">--}}
                    @csrf
                    <div class="card-body">
                        <div class="row">

                            {{-- ================== ENGAGEMENT ================== --}}
                            <h5 class="mb-3">@lang('Engagement')</h5>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="type_dossier">@lang('Type de Dossier')</label>
                                    <select name="type_dossier" id="type_dossier" class="form-control" required>
                                        <option value="national" {{ old('type_dossier') == 'national' ? 'selected' : '' }}>@lang('National')</option>
                                        <option value="international" {{ old('type_dossier') == 'international' ? 'selected' : '' }}>@lang('International')</option>
                                    </select>
                                </div>
                            </div>


                            <x-form.date name="engagement_date" label="Date Engagement" col="4" />
                            <x-form.input name="demande_number" label="N° Demande Paiement" col="4" />


                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fournisseur">@lang('Fournisseur')</label>
                                    <div class="d-flex align-items-start">
                                        <div class="flex-grow-1">
                                            <select name="fournisseur_id" id="fournisseur" class="form-control select2" required>
                                                <option value="">-- @lang('Sélectionner un fournisseur') --</option>
                                                @foreach($fournisseurs as $fournisseur)
                                                    <option value="{{ $fournisseur->id }}" {{ old('fournisseur') == $fournisseur->id ? 'selected' : '' }}>
                                                        {{ $fournisseur->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline--primary ms-2 cuModalBtn"
                                                data-modal_title="@lang('Ajouter Fournisseur')"
                                                data-bs-toggle="modal"
                                                data-bs-target="#fournisseurModal">
                                            <i class="las la-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="direction_id">@lang('Direction Émettrice')</label>
                                    <select name="direction_id" id="direction_id" class="form-control select2" required>
                                        <option value="">-- @lang('Sélectionner une direction') --</option>
                                        @foreach($directions as $direction)
                                            <option value="{{ $direction->id }}" {{ old('direction_id') == $direction->id ? 'selected' : '' }}>
                                                {{ $direction->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="condition_paiement">@lang('Condition de Paiement')</label>
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

                            <x-form.input type="number" step="0.01" name="montant_ht" label="Montant HT" col="3" />

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="taux_tva">@lang('TVA')</label>
                                    <select id="taux_tva" name="taux_tva" class="form-control">
                                        <option value="0" {{ old('taux_tva') == 0 ? 'selected' : '' }}>0%</option>
                                        <option value="9" {{ old('taux_tva') == 9 ? 'selected' : '' }}>9%</option>
                                        <option value="19" {{ old('taux_tva') == 19 ? 'selected' : '' }}>19%</option>
                                        <option value="custom" {{ old('taux_tva') == 'custom' ? 'selected' : '' }}>@lang('Autre')</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3 d-none" id="customTvaContainer">
                                <div class="form-group">
                                    <label for="custom_tva">@lang('TVA personnalisée (%)')</label>
                                    <input type="number" step="0.01" name="custom_tva" id="custom_tva" class="form-control" value="{{ old('custom_tva') }}">
                                </div>
                            </div>

                            <x-form.input type="number" step="0.01" name="montant_ttc" label="Montant TTC" col="3" readonly/>

                            {{-- Champs International --}}
                            <div id="internationalFields" class="row d-none mt-3">
                                <x-form.input type="number" step="0.01" name="montant_devise" label="Montant en Devise" col="3" />

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="monnaie">@lang('Monnaie')</label>
                                        <select id="monnaie" name="monnaie" class="form-control">
                                            <option value="USD" {{ old('monnaie') == 'USD' ? 'selected' : '' }}>💵 Dollar ($)</option>
                                            <option value="EUR" {{ old('monnaie') == 'EUR' ? 'selected' : '' }}>💶 Euro (€)</option>
                                        </select>
                                    </div>
                                </div>

                                <x-form.input type="number" step="0.0001" name="taux_conversion" label="Taux de Conversion" col="3" />
                                <x-form.input type="number" step="0.01" name="montant_ttc_local" id="montant_ttc_local" label="Montant TTC (Local)" col="3" readonly />
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


{{--                            <x-form.checkbox name="dossier_rejete" label="Dossier Rejeté" col="2" />--}}
{{--                            <x-form.date name="date_envoi" label="Date Envoi" col="3" />--}}
{{--                            <x-form.date name="date_retour" label="Date Retour" col="3" />--}}

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

</style>
@push('script')
    <script>
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

        (function($){
            "use strict";

            function calculateTTC() {
                let ht = parseFloat($('#montant_ht').val()) || 0;
                let tva = $('#taux_tva').val();
                if (tva === 'custom') {
                    tva = parseFloat($('#custom_tva').val()) || 0;
                } else {
                    tva = parseFloat(tva) || 0;
                }
                let ttc = ht + (ht * tva / 100);
                $('#montant_ttc').val(ttc.toFixed(2));
            }

            // Show custom TVA input if needed
            $('#taux_tva').on('change', function() {
                if ($(this).val() === 'custom') {
                    $('#customTvaContainer').removeClass('d-none');
                } else {
                    $('#customTvaContainer').addClass('d-none');
                    $('#custom_tva').val('');
                }
                calculateTTC();
            });

            // Recalculate on input changes
            $('#montant_ht, #taux_tva, #custom_tva').on('input change', calculateTTC);

        })(jQuery);

        (function($){
            "use strict";

            function calculateTTC() {
                let ht = parseFloat($('#montant_ht').val()) || 0;
                let tva = $('#taux_tva').val();
                if (tva === 'custom') {
                    tva = parseFloat($('#custom_tva').val()) || 0;
                } else {
                    tva = parseFloat(tva) || 0;
                }
                let ttc = ht + (ht * tva / 100);
                $('#montant_ttc').val(ttc.toFixed(2));

                // If international → calculate TTC Local
                if($('#type_dossier').val() === 'international'){
                    let montantDevise = parseFloat($('#montant_devise').val()) || 0;
                    let taux = parseFloat($('#taux_conversion').val()) || 0;
                    let montantLocal = montantDevise * taux;
                    $('#montant_ttc_local').val(montantLocal.toFixed(2));
                }
            }

            // Show/hide international fields
            $('#type_dossier').on('change', function(){
                if($(this).val() === 'international'){
                    $('#internationalFields').removeClass('d-none');
                } else {
                    $('#internationalFields').addClass('d-none');
                    $('#montant_devise, #taux_conversion, #montant_ttc_local').val('');
                }
                calculateTTC();
            });

            // Recalculate on input
            $('#montant_ht, #taux_tva, #custom_tva, #montant_devise, #taux_conversion').on('input change', calculateTTC);

        })(jQuery);

        (function($){
            "use strict";

            function toggleRejetFields() {
                if($('#dossier_rejete').is(':checked')){
                    $('#rejetContainer').removeClass('d-none');
                } else {
                    $('#rejetContainer').addClass('d-none');
                    $('#rejection_reason, #date_envoi, #date_retour').val('');
                }
            }

            // on load
            toggleRejetFields();

            // on change
            $('#dossier_rejete').on('change', toggleRejetFields);

        })(jQuery);



    </script>
@endpush


