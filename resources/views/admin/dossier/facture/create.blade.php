@extends('admin.layouts.app')

@section('panel')
    <div class="card">
        <div class="card-header"><h5>Ajouter une Facture (Dossier #{{ $dossier->id }})</h5></div>
        <div class="card-body">
            <form action="{{ route('admin.dossiers.factures.store', $dossier->id) }}" method="POST">
                @csrf
                <div class="row">
                    <x-form.input name="ref_facture" label="Réf Facture" col="6"/>
                    <x-form.date name="date_facture" label="Date Facture" col="6"/>
                    <x-form.input name="bon_commande" label="Bon Commande" col="6"/>
                    <x-form.input name="periode" label="Période" col="6"/>
                    <x-form.input type="number" step="0.01" name="montant_ht" label="Montant HT" col="6"/>
                    <x-form.input type="number" step="0.01" name="taux_tva" label="TVA (%)" col="6"/>

                    {{-- ✅ Nouveau champ Objet --}}
                    <x-form.input name="objet" label="Objet" col="12"/>

                    {{-- ✅ Select Direction --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="direction_id">Direction</label>
                            <select name="direction_id" id="direction_id" class="form-control">
                                <option value="">-- Choisir une direction --</option>
                                @foreach($directions as $direction)
                                    <option value="{{ $direction->id }}">{{ $direction->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- ✅ Select Compte Comptable --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="compte_id">Compte Comptable</label>
                            <select name="compte_id" id="compte_id" class="form-control">
                                <option value="">-- Choisir un compte --</option>
                                @foreach($comptes as $compte)
                                    <option value="{{ $compte->id }}">{{ $compte->code }} - {{ $compte->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <x-form.textarea name="observation" label="Observation" col="12"/>



                </div>
                <button type="submit" class="btn btn--primary">Enregistrer</button>
            </form>
        </div>
    </div>
@endsection
