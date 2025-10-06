@php
    $amountInDZD = $deposit->rate > 0 ? $deposit->final_amount / $deposit->rate : 0;
@endphp

    <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture - {{ $deposit->trx }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10.8px;
            margin: 10mm 10mm;
            color: #2c3e50;
            background: #fff;
        }

        .logo {
            width: 260px;
        }

        h4 {
            font-size: 12.5px;
            color: #1a1a1a;
            margin: 10px 0 8px;
            padding-bottom: 4px;
            border-bottom: 2px solid #007BFF;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
        }

        .info-box {
            flex: 1;
            background-color: #f0f6ff;
            border: 1px solid #bdd7f0;
            border-radius: 8px;
            padding: 12px 14px;
        }

        .info-box p {
            margin: 4px 0;
            line-height: 1.4;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
            margin-bottom: 16px;
        }

        .table th,
        .table td {
            border: 1px solid #ccc;
            padding: 6px 8px;
        }

        .table th {
            background: #e8f0fe;
            text-align: left;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .footer {
            font-size: 9.3px;
            text-align: center;
            color: #777;
            border-top: 1px dashed #bbb;
            padding-top: 10px;
            margin-top: 20px;
        }

        .footer p {
            margin: 2px 0;
            line-height: 1.4;
        }
    </style>
</head>

<body>
<div class="header">
    <img src="{{ public_path('assets/images/logo_icon/logo.png') }}" class="logo" alt="Logo">
    <div style="text-align: right;">
        <p>
            <strong style="font-size: 15px; color: #007BFF;">AeroCar</strong><br>
            Facture N° : <strong>{{ strtoupper($deposit->trx) }}</strong><br>
            Date : {{ \Carbon\Carbon::parse($deposit->created_at)->format('d/m/Y') }}
        </p>
    </div>
</div>

<table style="width: 100%; margin-bottom: 20px; border-collapse: collapse;">
    <tr>
        <!-- Informations client -->
        <td style="width: 50%; vertical-align: top; padding-right: 10px;">
            <h4 style="margin-bottom: 10px; color: #333;">Informations client</h4>
            <p><strong>Nom :</strong> {{ $deposit->user->fullname }}</p>
            <p><strong>Email :</strong> {{ $deposit->user->email }}</p>
            <p><strong>Téléphone :</strong> {{ $deposit->user->mobile }}</p>
        </td>

        <!-- Détails de la réservation -->
        <td style="width: 50%; vertical-align: top; padding-left: 10px;">
            <h4 style="margin-bottom: 10px; color: #333;">Détails de la réservation</h4>
            <p><strong>Véhicule :</strong> {{ optional($deposit->rentLog->vehicle)->name ?? '-' }}</p>
            <p><strong>Départ :</strong> {{ optional($deposit->rentLog->pickUpLocation)->name ?? '-' }}</p>
            <p><strong>Retour :</strong> {{ optional($deposit->rentLog->dropLocation)->name ?? '-' }}</p>
            <p><strong>Date de départ :</strong>
                {{ $deposit->rentLog->pick_time ? \Carbon\Carbon::parse($deposit->rentLog->pick_time)->format('d/m/Y H:i') : '-' }}
            </p>
            <p><strong>Date de retour :</strong>
                {{ $deposit->rentLog->drop_time ? \Carbon\Carbon::parse($deposit->rentLog->drop_time)->format('d/m/Y H:i') : '-' }}
            </p>
        </td>
    </tr>
</table>


<h4>Détails du montant</h4>
<table class="table">
    <thead>
    <tr>
        <th>Description</th>
        <th class="right">Montant</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Montant de la réservation</td>
        <td class="right">{{ number_format($deposit->amount, 2, ',', ' ') }} DZD</td>
    </tr>
    <tr>
        <td>Frais de transaction</td>
        <td class="right">{{ number_format($deposit->charge, 2, ',', ' ') }} DZD</td>
    </tr>
    @if ($deposit->full_payment_discount)
        <tr>
            <td>Remise pour paiement complet</td>
            <td class="right">-{{ number_format($deposit->full_payment_discount, 2, ',', ' ') }} %</td>
        </tr>
    @endif
    @if ($deposit->allow_one_day_pay)
        <tr>
            <td>Paiement anticipé (1 jour)</td>
            <td class="right">{{ number_format($deposit->allow_one_day_pay, 0) }} jour</td>
        </tr>
    @endif
    @if ($deposit->rest_amount > 0)
        <tr>
            <td class="bold">Reste à payer (au comptoir)</td>
            <td class="right bold">{{ number_format($deposit->rest_amount, 2, ',', ' ') }} DZD</td>
        </tr>
    @endif
    <tr>
        <td class="bold">Montant total payé</td>
        <td class="right bold">{{ number_format($amountInDZD, 2, ',', ' ') }} DZD</td>
    </tr>
    </tbody>
</table>

<h4>Détails du paiement</h4>
<table class="table">
    <tbody>
    @if ($deposit->method_code == 101)
        <tr>
            <td>Référence PayPal</td>
            <td>{{ $deposit->paypal_order_id ?? '-' }}</td>
        </tr>
    @else
        <tr>
            <td>Référence Transaction</td>
            <td>{{ $deposit->trx ?? '-' }}</td>
        </tr>
    @endif
    <tr>
        <td>Devise</td>
        <td>{{ $deposit->method_currency }}</td>
    </tr>
    <tr>
        <td>Montant reçu ({{ $deposit->method_currency }})</td>
        <td>{{ number_format($deposit->final_amount, 2, ',', ' ') }} {{ $deposit->method_currency }}</td>
    </tr>
    <tr>
        <td>Méthode</td>
        <td>
            @if ($deposit->method_code == 101)
                PayPal
            @elseif ($deposit->method_code > 999)
                Paiement à la livraison (CoD)
            @else
                {{ $deposit->gateway->name ?? 'N/A' }}
            @endif
        </td>
    </tr>
{{--    <tr>--}}
{{--        <td>Statut</td>--}}
{{--        <td>--}}
{{--            @if ($deposit->method_code > 999)--}}
{{--                --}}{{----}}{{-- Paiement à la livraison --}}
{{--                @if ($deposit->status == \App\Constants\Status::PAYMENT_PENDING)--}}
{{--                    @if ($deposit->final_amount > 0 && $deposit->rest_amount > 0)--}}
{{--                        Partiellement payé (reste à régler : {{ number_format($deposit->rest_amount, 2, ',', ' ') }} DZD)--}}
{{--                    @else--}}
{{--                        À payer à la livraison--}}
{{--                    @endif--}}
{{--                @elseif ($deposit->status == \App\Constants\Status::PAYMENT_SUCCESS)--}}
{{--                    Payé à la livraison--}}
{{--                @else--}}
{{--                    Annulé--}}
{{--                @endif--}}
{{--            @else--}}
{{--            @php--}}
{{--                // On force le reste à être positif ou nul--}}
{{--                $rest = $deposit->rest_amount > 0 ? $deposit->rest_amount : 0;--}}
{{--                $paid = $deposit->final_amount ?? 0;--}}
{{--            @endphp--}}

{{--            --}}{{-- Cas paiement à la livraison (CoD) --}}
{{--            @if ($deposit->method_code > 999)--}}
{{--                @if ($deposit->status == \App\Constants\Status::PAYMENT_PENDING)--}}
{{--                    @if ($paid > 0 && $rest > 0)--}}
{{--                        Partiellement payé (reste à régler : {{ number_format($rest, 2, ',', ' ') }} DZD)--}}
{{--                    @elseif ($paid == 0)--}}
{{--                        À payer à la livraison--}}
{{--                    @endif--}}
{{--                @elseif ($deposit->status == \App\Constants\Status::PAYMENT_SUCCESS)--}}
{{--                    @if ($paid == 0)--}}
{{--                        À payer à la livraison--}}
{{--                    @elseif ($rest > 0)--}}
{{--                        Partiellement payé (reste à régler : {{ number_format($rest, 2, ',', ' ') }} DZD)--}}
{{--                    @else--}}
{{--                        Payé à la livraison--}}
{{--                    @endif--}}
{{--                @elseif ($deposit->status == \App\Constants\Status::PAYMENT_REJECT)--}}
{{--                    Annulé--}}
{{--                @else--}}
{{--                    État inconnu--}}
{{--                @endif--}}
{{--            @else--}}
{{--            --}}{{-- Paiement en ligne (PayPal, carte, etc.) --}}
{{--                --}}{{-- Paiement en ligne (PayPal, carte, etc.) --}}
{{--                @if ($deposit->status == \App\Constants\Status::PAYMENT_SUCCESS)--}}
{{--                    @if ($deposit->rest_amount > 0)--}}
{{--                        Partiellement payé (reste à régler : {{ number_format($deposit->rest_amount, 2, ',', ' ') }} DZD)--}}
{{--                    @else--}}
{{--                        {{ $deposit->full_payment_discount ? 'Payé (avec remise)' : 'Payé' }}--}}
{{--                    @endif--}}
{{--                @elseif ($deposit->status == \App\Constants\Status::PAYMENT_PENDING)--}}
{{--                    En attente--}}
{{--                @else--}}
{{--                    Échoué--}}
{{--                @endif--}}
{{--            @endif--}}
{{--        </td>--}}
{{--    </tr>--}}

    <tr>
        <td>Statut</td>
        <td>
            @php
                $rest = max($deposit->rest_amount, 0);
                $paid = $deposit->final_amount ?? 0;
            @endphp

            {{-- Paiement en ligne --}}
            @if ($deposit->status == \App\Constants\Status::PAYMENT_SUCCESS)
                @if ($paid == 0)
                    {{-- Succès mais 0 payé → correction --}}
                    À payer
                @elseif ($rest > 0 && $paid > 0)
                    Partiellement payé (reste à régler : {{ number_format($rest, 2, ',', ' ') }} DZD)
                @else
                    {{ $deposit->full_payment_discount ? 'Payé (avec remise)' : 'Payé' }}
                @endif

            @elseif ($deposit->status == \App\Constants\Status::PAYMENT_PENDING)
                En attente

            @elseif ($deposit->status == \App\Constants\Status::PAYMENT_REJECT)
                Annulé

            @else
                Échoué
            @endif
        </td>
    </tr>


    </tbody>
</table>

<div class="footer">
    <p>
        Aéroport International d'Alger Houari Boumediène,<br>
        Alger Dar El Beïda 16000.
    </p>
</div>

</body>
</html>
