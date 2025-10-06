<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Contrat de location - {{ $rent->trx }}</title>

    <style>
        @page { margin: 25px 35px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11.5px; color: #333; line-height: 1.4; }

        .header { width:100%; display:table; border-bottom:2px solid #3b82f6; padding-bottom:8px; margin-bottom:10px; }
        .title { text-align:center; font-size:16px; font-weight:bold; margin:8px 0; text-transform:uppercase; }

        h3 { margin:4px 0 3px; font-size:12px; }   /* 🔥 Fix gap above titles */

        table { width:100%; border-collapse:collapse; margin-bottom:6px; }
        th { background:#f3f4f6; font-size:11px; }
        td, th { border:1px solid #aaa; padding:4px; font-size:10.5px; }

        .section { margin-bottom:6px; }            /* 🔥 Reduce section spacing */
        .highlight { background:#eef6ff; padding:5px; border-radius:3px; font-size:10.5px; line-height:1.3; }

        .signatures { width:100%; margin-top:25px; display:table; }
        .signatures .left, .signatures .right { display:table-cell; width:50%; text-align:center; vertical-align:bottom; }
        .signatures .line { margin-top:25px; border-top:1px solid #000; width:80%; margin-left:auto; margin-right:auto; }

        .page-break { page-break-before: always; }
    </style>

</head>
<body>

{{-- 🔹 PAGE 1 --}}
<div class="header">
    <div style="display:table-cell; width:25%; vertical-align:middle;">
        <img src="{{ public_path('assets/images/logo_icon/logo.png') }}" style="height:55px;">
    </div>
    <div style="display:table-cell; width:50%; text-align:center; font-size:11px; line-height:1.5; vertical-align:middle;">
        <strong style="font-size:13px;">AGENCE AÉROPORT D'ALGER</strong><br>
        Aéroport International Houari Boumediène, Dar El Beida – Alger<br>
        Tél : +213 560 155 491 | Email : support@aerocar.dz
    </div>
    <div style="display:table-cell; width:25%; text-align:right; vertical-align:middle;">
        <div style="display:inline-block; padding:6px 10px; background:#f3f4f6; border-left:3px solid #3b82f6; font-size:11.5px; line-height:1.6;">
            <div><b>Contrat N° :</b> {{ $rent->trx }}</div>
            <div><b>Date :</b> {{ now()->format('d/m/Y H:i') }}</div>
            <div><b>Agent :</b> {{ $admin->name ?? 'Admin' }}</div>
        </div>
    </div>
</div>

<div class="title">Contrat de Location de Véhicule</div>

{{-- Client + Conducteurs --}}
<div class="section" style="margin-bottom:6px;">
    <h3 style="margin-bottom:3px;">Informations Client</h3>
    <div class="highlight" style="padding:6px 8px; font-size:10.5px; line-height:1.3;">
        <b>Nom :</b> {{ $user->firstname }} {{ $user->lastname }} &nbsp; | &nbsp;
        <b>Email :</b> {{ $user->email }} <br>
        <b>Téléphone :</b> +{{ $user->dial_code }} {{ $user->mobile }} <br>
        <b>Adresse :</b> {{ $user->address ?? '' }}, {{ $user->city ?? '' }} ({{ $user->country_name ?? '' }})

{{--        --}}{{-- Conducteurs supplémentaires --}}
{{--        @if($rent->drivers && $rent->drivers->count())--}}
{{--            <div style="margin-top:4px; border-top:1px dashed #ccc; padding-top:3px;">--}}
{{--                <b>Conducteurs Supplémentaires :</b>--}}
{{--                @foreach($rent->drivers->take(3) as $driver)--}}
{{--                    <div style="margin:2px 0 0 8px; font-size:10px; line-height:1.3; padding:2px 4px; background:#f9fafb; border-left:2px solid #3b82f6;">--}}
{{--                        <span style="font-weight:bold; color:#111;">{{ $driver->name }}</span>--}}
{{--                        <span style="margin-left:6px; color:#444;"><b>Permis :</b> {{ $driver->license_number }}</span>--}}
{{--                        <span style="margin-left:6px; color:#444;"><b>Date :</b> {{ $driver->license_date ? \Carbon\Carbon::parse($driver->license_date)->format('d/m/Y') : '---' }}</span>--}}
{{--                        <span style="margin-left:6px; color:#444;"><b>Lieu :</b> {{ $driver->license_place ?? '---' }}</span>--}}
{{--                    </div>--}}
{{--                @endforeach--}}
{{--            </div>--}}
{{--        @endif--}}
        {{-- Conducteurs supplémentaires --}}
        @if($rent->drivers && $rent->drivers->count())
            <div class="section" style="margin-top:6px;">
                <h3 style="margin-bottom:4px;">Conducteurs</h3>
                <table style="width:100%; border-collapse:collapse; font-size:10.5px; margin-top:4px;">
                    <thead>
                    <tr style="background:#f9fafb;">
                        <th style="border:1px solid #ddd; padding:4px; text-align:left;">Nom & Prénom</th>
                        <th style="border:1px solid #ddd; padding:4px; text-align:left;">Permis</th>
                        <th style="border:1px solid #ddd; padding:4px; text-align:left;">Date</th>
                        <th style="border:1px solid #ddd; padding:4px; text-align:left;">Lieu</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($rent->drivers->take(3) as $driver)
                        <tr>
                            <td style="border:1px solid #ddd; padding:4px;">{{ $driver->name }}</td>
                            <td style="border:1px solid #ddd; padding:4px;">{{ $driver->license_number }}</td>
                            <td style="border:1px solid #ddd; padding:4px;">
                                 {{ $driver->license_date ? \Carbon\Carbon::parse($driver->license_date)->format('d/m/Y') : '---' }}
                            </td>
                            <td style="border:1px solid #ddd; padding:4px;">{{ $driver->license_place ?? '---' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif


    </div>
</div>


{{-- Véhicule --}}
<div class="section">
    <h3>Véhicule</h3>
    <table>
        <tr><th>Marque & Modèle</th><th>Immatriculation</th><th>Transmission</th><th>Carburant</th></tr>
        <tr>
            <td>{{ $vehicle->brand->name }} {{ $vehicle->model }}</td>
            <td>{{ $vehicle->matriculation ?? '---' }}</td>
            <td>{{ $vehicle->transmission }}</td>
            <td>{{ $vehicle->fuel_type }}</td>
        </tr>
    </table>
</div>

{{-- Période --}}
<div class="section">
    <h3>Période</h3>
    <table>
        <tr><th>Départ</th><th>Retour</th><th>Durée</th></tr>
        <tr>
            <td>{{ $rent->pickUpLocation->name ?? '' }}<br>{{ $rent->pick_time->format('d/m/Y H:i') }}</td>
            <td>{{ $rent->dropLocation->name ?? '' }}<br>{{ $rent->drop_time->format('d/m/Y H:i') }}</td>
            <td>{{ max(1, ceil($rent->pick_time->diffInHours($rent->drop_time)/24)) }} jour(s)</td>
        </tr>
    </table>
</div>

{{-- Tarifs --}}
<div class="section">
    <h3>Règlement</h3>
    <table>
        <tr><th>Jours</th><th>Tarif/Jour</th><th>Sous-total</th></tr>
        <tr>
            <td>{{ max(1, ceil($rent->pick_time->diffInHours($rent->drop_time)/24)) }}</td>
            <td>{{ number_format($vehicle->price,2) }} {{ gs()->cur_text }}</td>
            <td>{{ number_format($subtotal,2) }} {{ gs()->cur_text }}</td>
        </tr>
    </table>

    <table>
        <tr><th>Assurance</th><th>Carburant / Nettoyage</th><th>Taxe ({{ gs()->tax }}%)</th></tr>
        <tr>
            <td>{{ number_format($insurance,2) }} {{ gs()->cur_text }}</td>
            <td>{{ number_format($carburant,2) }} {{ gs()->cur_text }}</td>
            <td>{{ number_format($tax,2) }} {{ gs()->cur_text }}</td>
        </tr>
    </table>

    <table>
        <tr><th>Total TTC</th><th>Montant Payé</th><th>Reste à Payer</th><th>Caution</th></tr>
        <tr>
            <td>{{ number_format($amount,2) }} {{ gs()->cur_text }}</td>
            <td>{{ number_format($paid,2) }} {{ gs()->cur_text }}</td>
            <td>{{ number_format($remaining,2) }} {{ gs()->cur_text }}</td>
            <td>{{ number_format($rent->caution,2) }} {{ gs()->cur_text }}</td>
        </tr>
    </table>
</div>

{{-- Avantages inclus --}}
<div class="section">
    <h3>Avantages Inclus</h3>
    <div style="background:#ecfdf5; border:1px solid #10b981; padding:8px; border-radius:6px; font-size:11px; color:#065f46;">
        <ul style="margin:0; padding-left:18px; list-style:disc;">
            <li><b>Kilométrage illimité</b> – aucun frais supplémentaire</li>
            @php
                $babySeatTypes = [
                    0 => __('Sans options'),
                    1 => __('Siège bébé (0–3 ans)'),
                    2 => __('Siège enfant (3–5 ans)'),
                    3 => __('Siège enfant (6–10 ans)'),
                ];
            @endphp
            <li>
                <b>Siège bébé fourni gratuitement :</b>
                {{ $babySeatTypes[$rent->baby_seat] ?? __('Sans options') }}
            </li>
        </ul>
    </div>
</div>


{{-- Signatures Page 1 --}}
<div class="signatures">
    <div class="left"><div class="line"></div><b>AeroCar</b></div>
    <div class="right"><div class="line"></div><b>Signature Client</b></div>
</div>

<div class="page-break"></div>
<div class="title">Conditions Générales de Location</div>

<style>
    .conditions { font-size: 10.5px; line-height: 1.4; color:#222; }
    .conditions h3 { font-size: 11.5px; margin:10px 0 4px; color:#1f2937; border-left:3px solid #3b82f6; padding-left:6px; }
    .conditions ul { margin:0 0 6px 16px; padding:0; }
    .conditions li { margin-bottom:3px; }
    .highlight-box { background:#eef6ff; border:1px solid #3b82f6; padding:8px; border-radius:4px; margin-top:12px; font-size:10.5px; }
</style>

<div class="conditions">

    <h3>1. Conducteurs autorisés</h3>
    <ul>
        <li>Âge minimum : <b>28 ans</b> et <b>5 ans de permis</b>.</li>
        <li>Location personnelle, non transmissible, durée précisée au recto.</li>
        <li>Restitution obligatoire avec clés et papiers (sinon facturation).</li>
        <li>Non-respect = résiliation immédiate par AEROCAR.</li>
    </ul>

    <h3>2. Utilisation du véhicule</h3>
    <ul>
        <li>Conduite par conducteurs autorisés uniquement.</li>
        <li>Interdits : usage illicite, transport payant, alcool/drogues, compétitions, remorquage, modifications, publicité.</li>
        <li>Circulation limitée au <b>territoire algérien</b>.</li>
        <li>Le locataire reste responsable des infractions routières.</li>
    </ul>

    <h3>3. État du véhicule</h3>
    <ul>
        <li>Livré en bon état (mécanique, pneus, accessoires).</li>
        <li>Contrôles d’usage à la charge du locataire (huile, pneus...).</li>
        <li>Toute anomalie doit être signalée <b>dans les 15 min</b> après départ, sinon véhicule réputé conforme.</li>
        <li>Pneu endommagé = remplacement immédiat à l’identique.</li>
        <li>Doit être restitué dans le même état qu’au départ.</li>
    </ul>

    <h3>4. Assurances</h3>
    <ul>
        <li>Couvrent : tiers, passagers, vol, incendie, dommages véhicule.</li>
        <li><b>Franchise</b> selon modèle + forfait immobilisation en cas de sinistre.</li>
        <li>Déclaration obligatoire sous <b>24h</b> + constat remis sous <b>48h</b> avec rapport police/gendarmerie.</li>
        <li>Perte documents : <b>10 000 DA</b> + <b>50 000 DA</b> pour déclaration.</li>
        <li>Exclusions : effets personnels, animaux, fausse identité, faits volontaires.</li>
        <li>Capotage sans tiers : indemnisation <b>300 000 DA</b> + rapatriement.</li>
        <li>Restitution hors procédure (05h–08h) = supplément <b>3 000 DA</b> + perte d’assurance possible.</li>
    </ul>

    <h3>5. Tarifs & Paiements</h3>
    <ul>
        <li>Paiement d’avance, non remboursable.</li>
        <li>Carburant : <b>plein à plein</b>, sinon facturation du manque + frais.</li>
        <li>Nettoyage : 1 000 DA lavage / 3 000 DA lifting.</li>
        <li>Prolongation : prévenir <b>48h avant</b> expiration.</li>
        <li>Responsabilité du locataire : amendes, contraventions, PV.</li>
        <li>Paiement CB = autorisation des suppléments et amendes.</li>
        <li>Siège bébé : 12 000 DA (non rendu) / 4 000 DA (endommagé).</li>
        <li>Enjoliveur : 2 000 DA pièce.</li>
    </ul>

    <h3>6. Litiges</h3>
    <p>Tout litige relève du <b>tribunal compétent du siège social AEROCAR</b>.</p>

    <div class="highlight-box">
        Le signataire reconnaît avoir pris connaissance des présentes conditions générales
        et les accepte <b>sans réserve</b>.
    </div>
</div>


{{-- Signatures --}}
<div class="signatures">
    <div class="left"><div class="line"></div><b>AeroCar</b></div>
    <div class="right"><div class="line"></div><b>Signature Client</b></div>
</div>


</body>
</html>
