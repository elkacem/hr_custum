{{--@extends('admin.layouts.app')--}}

{{--@section('panel')--}}
{{--    <div class="row">--}}
{{--        <div class="col-lg-12">--}}
{{--            <div class="card">--}}
{{--                <form action="{{ route('admin.dossiers.update', $dossier->id) }}" method="post" class="disableSubmission">--}}
{{--                    @csrf--}}
{{--                    <div class="card-body">--}}
{{--                        <div class="row">--}}

{{--                            --}}{{-- ================== ENGAGEMENT ================== --}}
{{--                            <h5 class="mb-3">@lang('Engagement')</h5>--}}

{{--                            <x-form.date name="engagement_date" label="Date Engagement" col="4"--}}
{{--                                         value="{{ old('engagement_date', $dossier->engagement_date) }}" />--}}

{{--                            --}}{{-- Fournisseur select --}}
{{--                            <div class="col-md-4">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="fournisseur">@lang('Fournisseur')</label>--}}
{{--                                    <div class="d-flex">--}}
{{--                                        <select name="fournisseur" id="fournisseur" class="form-control select2" required>--}}
{{--                                            <option value="">-- @lang('Sélectionner un fournisseur') --</option>--}}
{{--                                            @foreach($fournisseurs as $fournisseur)--}}
{{--                                                <option value="{{ $fournisseur->id }}"--}}
{{--                                                    {{ old('fournisseur', $dossier->fournisseur) == $fournisseur->id ? 'selected' : '' }}>--}}
{{--                                                    {{ $fournisseur->name }}--}}
{{--                                                </option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                        <button type="button" class="btn btn-sm btn-outline--primary ms-2"--}}
{{--                                                data-bs-toggle="modal"--}}
{{--                                                data-bs-target="#fournisseurModal">--}}
{{--                                            <i class="las la-plus"></i>--}}
{{--                                        </button>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <x-form.input name="ref_facture" label="Réf Facture" col="4"--}}
{{--                                          value="{{ old('ref_facture', $dossier->ref_facture) }}" />--}}
{{--                            <x-form.date name="date_facture" label="Date Facture" col="4"--}}
{{--                                         value="{{ old('date_facture', $dossier->date_facture) }}" />--}}
{{--                            <x-form.input name="bon_commande" label="Bon de Commande" col="4"--}}
{{--                                          value="{{ old('bon_commande', $dossier->bon_commande) }}" />--}}
{{--                            <x-form.input name="periode" label="Périodicité" col="4"--}}
{{--                                          value="{{ old('periode', $dossier->periode) }}" />--}}

{{--                            --}}{{-- ================== DEMANDE PAIEMENT ================== --}}
{{--                            <h5 class="mt-4 mb-3">@lang('Demande de Paiement')</h5>--}}

{{--                            <x-form.date name="ordonnancement_date" label="Date Ordonnancement" col="4"--}}
{{--                                         value="{{ old('ordonnancement_date', $dossier->ordonnancement_date) }}" />--}}
{{--                            <x-form.input name="demande_number" label="N° Demande Paiement" col="4"--}}
{{--                                          value="{{ old('demande_number', $dossier->demande_number) }}" />--}}

{{--                            <x-form.checkbox name="dossier_rejete" label="Dossier Rejeté" col="2"--}}
{{--                                             :checked="old('dossier_rejete', $dossier->dossier_rejete)" />--}}
{{--                            <x-form.input name="be_number" label="N° BE" col="4"--}}
{{--                                          value="{{ old('be_number', $dossier->be_number) }}" />--}}
{{--                            <x-form.date name="date_envoi" label="Date Envoi" col="3"--}}
{{--                                         value="{{ old('date_envoi', $dossier->date_envoi) }}" />--}}
{{--                            <x-form.date name="date_retour" label="Date Retour" col="3"--}}
{{--                                         value="{{ old('date_retour', $dossier->date_retour) }}" />--}}

{{--                            <x-form.input name="direction_emettrice" label="Direction Émettrice" col="6"--}}
{{--                                          value="{{ old('direction_emettrice', $dossier->direction_emettrice) }}" />--}}
{{--                            <x-form.input name="condition_paiement" label="Condition de Paiement" col="6"--}}
{{--                                          value="{{ old('condition_paiement', $dossier->condition_paiement) }}" />--}}
{{--                            <x-form.input name="echeancier" label="Échéancier" col="6"--}}
{{--                                          value="{{ old('echeancier', $dossier->echeancier) }}" />--}}

{{--                            <x-form.input type="number" step="0.01" name="montant_ht" label="Montant HT" col="3"--}}
{{--                                          value="{{ old('montant_ht', $dossier->montant_ht) }}" />--}}
{{--                            <x-form.input type="number" step="0.01" name="taux_tva" label="TVA (%)" col="3"--}}
{{--                                          value="{{ old('taux_tva', $dossier->taux_tva) }}" />--}}
{{--                            <x-form.input type="number" step="0.01" name="montant_ttc" label="Montant TTC" col="3"--}}
{{--                                          value="{{ old('montant_ttc', $dossier->montant_ttc) }}" />--}}


{{--                            <h5 class="mt-4 mb-3">@lang('Fichiers du Dossier')</h5>--}}
{{--                            <div class="col-md-12 mb-3">--}}
{{--                                @if($dossier->attachments->count())--}}
{{--                                    <ul class="list-group">--}}
{{--                                        @foreach($dossier->attachments as $attachment)--}}
{{--                                            <li class="list-group-item d-flex justify-content-between align-items-center">--}}
{{--                                                <a href="{{ route('admin.dossiers.download', $attachment->id) }}" target="_blank">--}}
{{--                                                    <i class="las la-paperclip"></i> {{ basename($attachment->file_path) }}--}}
{{--                                                </a>--}}
{{--                                                <form action="{{ route('admin.dossiers.delete-attachment', $attachment->id) }}" method="POST" class="d-inline">--}}
{{--                                                    @csrf--}}
{{--                                                    @method('DELETE')--}}
{{--                                                    <button type="submit" class="btn btn-sm btn-outline--danger">--}}
{{--                                                        <i class="las la-trash"></i>--}}
{{--                                                    </button>--}}
{{--                                                </form>--}}
{{--                                            </li>--}}
{{--                                        @endforeach--}}
{{--                                    </ul>--}}
{{--                                @else--}}
{{--                                    <p class="text-muted">@lang('Aucun fichier attaché')</p>--}}
{{--                                @endif--}}
{{--                            </div>--}}

{{--                            <div class="col-md-9">--}}
{{--                                <button type="button" class="btn btn--dark btn--sm addAttachment my-3">--}}
{{--                                    <i class="fas fa-plus"></i> @lang('Ajouter un fichier')--}}
{{--                                </button>--}}
{{--                                <p class="mb-2">--}}
{{--                                    <span class="text--info">--}}
{{--                                        @lang('Max 5 fichiers | Taille max : ' . convertToReadableSize(ini_get('upload_max_filesize')))--}}
{{--                                        <br>@lang('Extensions autorisées : .jpg, .jpeg, .png, .pdf, .doc, .docx')--}}
{{--                                    </span>--}}
{{--                                </p>--}}
{{--                                <div class="row fileUploadsContainer"></div>--}}
{{--                            </div>000--}}

{{--                            <x-form.textarea name="observation" label="Observation" col="12">--}}
{{--                                {{ old('observation', $dossier->observation) }}--}}
{{--                            </x-form.textarea>--}}

{{--                            <div class="col-md-12 mt-3">--}}
{{--                                <button type="submit" class="btn btn--primary w-100">@lang('Mettre à jour')</button>--}}
{{--                            </div>--}}

{{--                        </div>--}}
{{--                    </div>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    --}}{{-- Modal Fournisseur (same as create) --}}
{{--    <div class="modal fade" id="fournisseurModal" tabindex="-1" role="dialog">--}}
{{--        <div class="modal-dialog" role="document">--}}
{{--            <div class="modal-content">--}}
{{--                <form action="{{ route('admin.supplier.store') }}" method="POST">--}}
{{--                    @csrf--}}
{{--                    <div class="modal-header">--}}
{{--                        <h5 class="modal-title">@lang('Ajouter Fournisseur')</h5>--}}
{{--                        <button type="button" class="close" data-bs-dismiss="modal">--}}
{{--                            <i class="las la-times"></i>--}}
{{--                        </button>--}}
{{--                    </div>--}}
{{--                    <div class="modal-body">--}}
{{--                        <div class="form-group">--}}
{{--                            <label>@lang('Nom')</label>--}}
{{--                            <input type="text" name="name" class="form-control" required>--}}
{{--                        </div>--}}
{{--                        <div class="form-group">--}}
{{--                            <label>@lang('Email')</label>--}}
{{--                            <input type="email" name="email" class="form-control">--}}
{{--                        </div>--}}
{{--                        <div class="form-group">--}}
{{--                            <label>@lang('Téléphone')</label>--}}
{{--                            <input type="text" name="phone" class="form-control">--}}
{{--                        </div>--}}
{{--                        <div class="form-group">--}}
{{--                            <label>@lang('Adresse')</label>--}}
{{--                            <input type="text" name="address" class="form-control">--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="modal-footer">--}}
{{--                        <button type="submit" class="btn btn--primary">@lang('Enregistrer')</button>--}}
{{--                    </div>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--@endsection--}}

@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route('admin.dossiers.update', $dossier->id) }}" method="post" class="disableSubmission" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        <div class="row">

                            {{-- ================== ENGAGEMENT ================== --}}
                            <h5 class="mb-3">@lang('Engagement')</h5>

                            {{-- Type de dossier --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="type_dossier">@lang('Type de Dossier')</label>
                                    <select name="type_dossier" id="type_dossier" class="form-control" required>
                                        <option value="national" {{ old('type_dossier', $dossier->type_dossier) == 'national' ? 'selected' : '' }}>@lang('National')</option>
                                        <option value="international" {{ old('type_dossier', $dossier->type_dossier) == 'international' ? 'selected' : '' }}>@lang('International')</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Date engagement --}}
                            <x-form.date name="engagement_date" label="Date Engagement" col="4" :value="old('engagement_date', $dossier->engagement_date)" />

                            {{-- Numéro demande --}}
                            <x-form.input name="demande_number" label="N° Demande Paiement" col="4" :value="old('demande_number', $dossier->demande_number)" />

                            {{-- Fournisseur --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fournisseur_id">@lang('Fournisseur')</label>
                                    <select name="fournisseur_id" id="fournisseur_id" class="form-control select2" required>
                                        <option value="">-- @lang('Sélectionner un fournisseur') --</option>
                                        @foreach($fournisseurs as $fournisseur)
                                            <option value="{{ $fournisseur->id }}"
                                                {{ old('fournisseur_id', $dossier->fournisseur_id) == $fournisseur->id ? 'selected' : '' }}>
                                                {{ $fournisseur->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Direction --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="direction_id">@lang('Direction Émettrice')</label>
                                    <select name="direction_id" id="direction_id" class="form-control select2">
                                        <option value="">-- @lang('Sélectionner une direction') --</option>
                                        @foreach($directions as $direction)
                                            <option value="{{ $direction->id }}"
                                                {{ old('direction_id', $dossier->direction_id) == $direction->id ? 'selected' : '' }}>
                                                {{ $direction->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Condition de paiement --}}
                            <x-form.input name="condition_paiement" label="Condition de Paiement" col="6" :value="old('condition_paiement', $dossier->condition_paiement)" />

                            {{-- Montant HT --}}
                            <x-form.input type="number" step="0.01" name="montant_ht" label="Montant HT" col="3" :value="old('montant_ht', $dossier->montant_ht)" />

                            {{-- TVA --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="taux_tva">@lang('TVA')</label>
                                    <select id="taux_tva" name="taux_tva" class="form-control">
                                        <option value="0" {{ old('taux_tva', $dossier->taux_tva) == 0 ? 'selected' : '' }}>0%</option>
                                        <option value="9" {{ old('taux_tva', $dossier->taux_tva) == 9 ? 'selected' : '' }}>9%</option>
                                        <option value="19" {{ old('taux_tva', $dossier->taux_tva) == 19 ? 'selected' : '' }}>19%</option>
                                        <option value="custom" {{ old('taux_tva', $dossier->taux_tva) == 'custom' ? 'selected' : '' }}>@lang('Autre')</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Montant TTC --}}
                            <x-form.input type="number" step="0.01" name="montant_ttc" label="Montant TTC" col="3" :value="old('montant_ttc', $dossier->montant_ttc)" readonly/>

                            {{-- Champs International --}}
                            <div id="internationalFields" class="row {{ old('type_dossier', $dossier->type_dossier) == 'international' ? '' : 'd-none' }} mt-3">
                                <x-form.input type="number" step="0.01" name="montant_devise" label="Montant en Devise" col="3" :value="old('montant_devise', $dossier->montant_devise)" />

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="monnaie">@lang('Monnaie')</label>
                                        <select id="monnaie" name="monnaie" class="form-control">
                                            <option value="USD" {{ old('monnaie', $dossier->monnaie) == 'USD' ? 'selected' : '' }}>💵 Dollar ($)</option>
                                            <option value="EUR" {{ old('monnaie', $dossier->monnaie) == 'EUR' ? 'selected' : '' }}>💶 Euro (€)</option>
                                        </select>
                                    </div>
                                </div>

                                <x-form.input type="number" step="0.0001" name="taux_conversion" label="Taux de Conversion" col="3" :value="old('taux_conversion', $dossier->taux_conversion)" />
                                <x-form.input type="number" step="0.01" name="montant_ttc_local" id="montant_ttc_local" label="Montant TTC (Local)" col="3" :value="old('montant_ttc_local', $dossier->montant_ttc_local)" readonly />
                            </div>

                            {{-- Fichiers du dossier --}}
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

                                {{-- déjà enregistrés --}}
                                @foreach($dossier->attachments as $file)
                                    <div class="mb-2">
                                        <a href="{{ route('admin.dossiers.download', $file->id) }}" class="text-primary">
                                            {{ basename($file->file_path) }}
                                        </a>
                                        <a href="{{ route('admin.dossiers.delete-attachment', $file->id) }}" class="text-danger ms-2">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                @endforeach

                                <div class="row fileUploadsContainer"></div>
                            </div>

                            {{-- Périodicité --}}
                            <x-form.input name="periode" label="Périodicité" col="4" :value="old('periode', $dossier->periode)" />

                            {{-- Dossier rejeté --}}
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>@lang('Dossier Rejeté')</label><br>
                                    <input type="checkbox" id="dossier_rejete" name="dossier_rejete" value="1"
                                        {{ old('dossier_rejete', $dossier->dossier_rejete) ? 'checked' : '' }}>
                                </div>
                            </div>

                            {{-- Conteneur des infos de rejet --}}
                            <div class="row {{ old('dossier_rejete', $dossier->dossier_rejete) ? '' : 'd-none' }}" id="rejetContainer">
                                <div class="col-md-3">
                                    <x-form.date name="date_envoi" label="Date Envoi" :value="old('date_envoi', $dossier->date_envoi)" />
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="rejection_reason">@lang('Raison du rejet')</label>
                                        <textarea name="rejection_reason" id="rejection_reason"
                                                  class="form-control" rows="2">{{ old('rejection_reason', $dossier->rejection_reason) }}</textarea>
                                    </div>
                                </div>
                            </div>

                            {{-- Bouton --}}
                            <div class="col-md-12 mt-3">
                                <button type="submit" class="btn btn--primary w-100">@lang('Mettre à jour')</button>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

