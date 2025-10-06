@extends('admin.layouts.app')

@section('panel')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5>Factures du Dossier #{{ $dossier->id }}</h5>
            <a href="{{ route('dossiers.factures.create', $dossier->id) }}" class="btn btn-primary">Ajouter Facture</a>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                <tr>
                    <th>Réf</th><th>Date</th><th>Montant HT</th><th>TVA</th><th>TTC</th><th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($factures as $facture)
                    <tr>
                        <td>{{ $facture->ref_facture }}</td>
                        <td>{{ $facture->date_facture }}</td>
                        <td>{{ $facture->montant_ht }}</td>
                        <td>{{ $facture->taux_tva }}%</td>
                        <td>{{ $facture->montant_ttc }}</td>
                        <td>
                            <a href="{{ route('admin.dossiers.factures.edit', [$dossier->id, $facture->id]) }}" class="btn btn-sm btn-warning">✏</a>
                            <form method="POST" action="{{ route('admin.dossiers.factures.destroy', [$dossier->id, $facture->id]) }}" style="display:inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">🗑</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $factures->links() }}
        </div>
    </div>
@endsection
