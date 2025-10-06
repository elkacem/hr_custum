@php
        $rentalDays = $rent->pick_time ? ceil(\Carbon\Carbon::parse($rent->pick_time)->diffInDays(\Carbon\Carbon::parse($rent->drop_time), false)) : 1;
        $baseVehiclePrice = $rent->price;
        $fixedServiceFee = 3000;
        $baseTotal = $baseVehiclePrice + $fixedServiceFee;

        $insurancePerDay = 6000;
        $insuranceTotal = $insurancePerDay * $rentalDays;

        $minimumCaution = 100000;
        $maximumCaution = 200000;

        // === BASIC PROTECTION (fixed) ===
        $cautionForBasic = 90000;

        // === COMPLETE PROTECTION (fixed) ===
        $cautionForComplete = 20000;


@endphp


@extends('template.layouts.master')

@php
    $babySeats = [
        ['id' => '0', 'title' => 'Sans options', 'price' => 0, 'image' => asset('assets/images/noption.png')],
        ['id' => '1', 'title' => '0-3 ans', 'price' => 0, 'image' => asset('assets/images/0-3.png')],
        ['id' => '2', 'title' => '3-5 ans', 'price' => 0, 'image' => asset('assets/images/3-5.png')],
        ['id' => '3', 'title' => '6-10 ans', 'price' => 0, 'image' => asset('assets/images/6-10.png')],
    ];

    $insurances = [
        [
            'id' => '0',
            'title' => 'Protection Basique',
            'price' => 0,
            'franchise' => 'jusqu\'à la valeur du véhicule',
            'caution' => $cautionForBasic, // caution deposit
            'details' => [
                'Responsabilité civile' => true,
                'Protection contre les dommages résultant d\'une collision' => false,
                'Protection contre le vol' => false,
                'Assistance dépannage' => false,
                'Véhicule de remplacement' => false,
                'Protection bris de glace, phares' => false,
                'Immobilisation' => false,
                'Protection pneumatique' => false,
            ]
        ],
        [
            'id' => '1',
            'title' => 'Protection Complète',
            'price' => $insurancePerDay,
            'franchise' => 'Aucune franchise',
            'caution' => $cautionForComplete, // no caution
//            'caution' => $cautionForBasic, // no caution
            'details' => [
                'Responsabilité civile' => true,
                'Protection contre les dommages résultant d\'une collision' => true,
                'Protection contre le vol' => true,
                'Assistance dépannage' => true,
                'Véhicule de remplacement' => true,
                'Protection bris de glace, phares' => true,
                'Immobilisation' => true,
                'Protection pneumatique' => true,
            ]
        ],
    ];

@endphp

@section('content')
    <div class="pt-60 pb-60 bg-section">
        <div class="container">
            <form action="{{ route('user.dossier.insurance.submit') }}" method="POST">
                @csrf
                <div class="row g-4">
                    <!-- Left Section -->
                    <div class="col-lg-8">
                        <h4 class="mb-3">Siège bébé</h4>
                        <div class="baby-seat-wrapper mb-5">
                            @foreach ($babySeats as $seat)
                                <div class="option-card baby-seat-card {{ $loop->first ? 'selected' : '' }}">
                                    <input type="radio" name="baby_seat" value="{{ $seat['id'] }}" class="d-none baby-seat-radio" data-price="{{ $seat['price'] }}" id="seat_{{ $seat['id'] }}" @if($loop->first) checked @endif>
                                    <label class="selectable baby-label" data-id="{{ $seat['id'] }}">
                                        <img src="{{ $seat['image'] }}" alt="{{ $seat['title'] }}">
                                        <span class="title">{{ $seat['title'] }}</span>
                                        <span class="price text-success">Gratuit</span>
                                        <span class="checkmark">✓</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <h4 class="mb-3">Assurance</h4>

                        <div class="insurance-wrapper">
                            @foreach ($insurances as $insurance)
                                <div class="option-card insurance-option {{ $loop->first ? 'selected' : '' }}">
                                    <input type="radio"
                                           name="insurance"
                                           value="{{ $insurance['id'] }}"
                                           class="d-none insurance-radio"
                                           data-price="{{ $insurance['price'] }}"
                                           id="ins_{{ $insurance['id'] }}"
                                           @if($loop->first) checked @endif>

                                    <label class="selectable insurance-label p-3" data-id="{{ $insurance['id'] }}">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="fas fa-shield-alt fa-lg text-primary"></i>
                                                <div>
                                                    <h6 class="mb-1 fw-bold text-dark">{{ $insurance['title'] }}</h6>
                                                    <small class="text-muted">Franchise : {{ $insurance['franchise'] }}</small>
                                                </div>
                                            </div>
                                            <span class="checkmark">✓</span>
                                        </div>

                                        <ul class="list-unstyled mt-3 mb-3">
                                            @foreach ($insurance['details'] as $label => $enabled)
                                                <li class="mb-1 d-flex align-items-center gap-2">
                                                    <i class="fas fa-{{ $enabled ? 'check-circle text-success' : 'times-circle text-danger' }}"></i>
                                                    <span>{{ $label }}</span>
                                                </li>
                                            @endforeach
                                        </ul>

                                        <div class="text-end mt-3">
{{--                                            <span class="fw-bold text-primary h6">--}}
{{--                                                {{ number_format($insurance['price'], 0, ',', ' ') }} DZD--}}
{{--                                                <small class="text-muted">/jour</small>--}}
{{--                                            </span>--}}
                                            <span class="fw-bold text-primary h6">
                                            {{ number_format($insurance['price'], 0, ',', ' ') }} DZD <small class="text-muted">/jour</small>
                                        </span>


                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>

                    </div>

                    <!-- Summary -->
                    <div class="col-lg-4">
                        <div class="card p-4 shadow rounded-4 bg-white">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="fw-bold mb-1 text--star">{{ $rent->vehicle->name ?? 'SYMBOL 1.6 Extréme Nouvelle' }}</h6>
                                    <span class="badge bg-primary-subtle text-primary small">Récapitulatif</span>
                                </div>
                                <i class="fas fa-car-side fa-lg text-primary"></i>
                            </div>
                            <ul class="list-unstyled small text-muted mb-3">
                                <li class="mb-1">Départ : <strong>{{ $rent->pickUpLocation->name ?? 'Hydra' }}</strong> - {{ \Carbon\Carbon::parse($rent->pick_time ?? '2025-07-02')->translatedFormat('d M Y') }} {{ \Carbon\Carbon::parse($rent->pick_time)->format('H:i') }}</li>
                                <li class="mb-1">Retour : <strong>{{ $rent->dropUpLocation->name ?? 'Aéroport' }}</strong> - {{ \Carbon\Carbon::parse($rent->drop_time)->translatedFormat('d M Y') }} {{ \Carbon\Carbon::parse($rent->drop_time)->format('H:i') }}</li>
                                <li class="mb-1">{{ $rent->pick_time ? ceil(\Carbon\Carbon::parse($rent->pick_time)
                                            ->diffInDays(\Carbon\Carbon::parse($rent->drop_time), false))
                                        : 2  }} jours de location -
                                    <span class="text-success fw-bold">🎁 Kilométrage illimité</span>
                                </li>
                            </ul>
                            <hr>
                            <ul class="list-unstyled small mb-3">
{{--                                <li class="d-flex justify-content-between mb-1"><span>Voiture</span><span>{{ number_format($baseVehiclePrice, 2, ',', ' ') }} DZD</span></li>--}}
{{--                                <li class="d-flex justify-content-between mb-1"><span>Service</span><span>{{ number_format($fixedServiceFee, 0, ',', ' ') }} DZD</span></li>--}}
{{--                                <li class="d-flex justify-content-between mb-1"><span>Options</span><span class="insurance-summary">0 DZD</span></li>--}}
                                <li class="d-flex justify-content-between mb-1">
                                    <span><i class="fas fa-car-side me-1 text-muted"></i> Voiture</span>
                                    <span>{{ number_format($baseVehiclePrice, 2, ',', ' ') }} DZD</span>
                                </li>
                                <li class="d-flex justify-content-between mb-1">
                                    <span><i class="fas fa-gas-pump me-1 text-muted"></i> Carburant et Nettoyage</span>
                                    <span>{{ number_format($fixedServiceFee, 0, ',', ' ') }} DZD</span>
                                </li>
{{--                                <li class="d-flex justify-content-between mb-1">--}}
{{--                                    <span><i class="fas fa-plus-circle me-1 text-muted"></i> Options</span>--}}
{{--                                    <span class="insurance-summary">0 DZD</span>--}}
{{--                                </li>--}}
                                <li class="d-flex justify-content-between mb-1">
                                    <span>
                                        <i class="fas fa-gift me-1 text-muted"></i>
                                        Assurance & options incluses
                                        <small class="text-muted ms-1" data-bs-toggle="tooltip" title="Inclut l'assurance sélectionnée et un siège bébé gratuit si choisi.">
                                            <i class="fas fa-info-circle"></i>
                                        </small>
                                    </span>
                                    <span class="insurance-summary">0 DZD</span>
                                </li>


                            </ul>
                            <hr>
                            <p class="d-flex justify-content-between fw-bold"><span>Total HT</span><span class="total-summary">{{ number_format($baseTotal, 2, ',', ' ') }} DZD</span></p>
                            <div id="caution-box"
                                 class="alert d-flex justify-content-between align-items-center py-2 px-3 rounded-3 mt-2 mb-0 small"
                                 style="font-size: 14px;">
                                <div class="caution-label d-flex align-items-center">
                                    <i class="fas fa-exclamation-triangle me-2 caution-icon"></i>
                                    <strong>Caution (à l'agence)</strong>
                                    <small class="text-muted ms-2" data-bs-toggle="tooltip" title="La protection complète réduit fortement la caution à verser.">
                                        <i class="fas fa-info-circle"></i>
                                    </small>
                                </div>
{{--                                <div class="fw-bold caution-summary text-dark">240 000 DZD</div>--}}
                                <div class="fw-bold caution-summary text-dark">
                                    {{ number_format($cautionForBasic, 0, ',', ' ') }} DZD
                                </div>

                            </div>



                            <input type="hidden" name="insurance_price" value="0">
                            <input type="hidden" name="caution" value="{{ $cautionForBasic }}">

                            <input type="hidden" id="caution-basic" value="{{ $cautionForBasic }}">
                            <input type="hidden" id="caution-complete" value="{{ $cautionForComplete }}">


                            <button type="submit" class="btn btn-primary w-100 mt-3">Suivant</button>
                            <p class="text-muted text-center mt-4 mb-0 small">
                                En cliquant sur <strong>Suivant</strong>, vous passez à la confirmation.
                            </p>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .option-card {
            border: 1px solid #e0e6ed;
            border-radius: 12px;
            background: #fff;
            padding: 1rem;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.03);
            transition: border-color 0.3s, box-shadow 0.3s;
            position: relative;
            cursor: pointer;
        }
        .option-card:hover {
            border-color: #0d6efd33;
            box-shadow: 0 3px 10px rgba(13, 110, 253, 0.06);
        }
        .option-card.selected {
            border-color: #0d6efd;
            background: #f0faff;
        }

        .baby-seat-wrapper {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(110px, 1fr));
            gap: 1rem;
        }
        .baby-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 0.25rem;
        }
        .baby-label img {
            width: 50px;
            height: auto;
        }

        .insurance-wrapper {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }
        .insurance-label {
            display: block;
            padding: 0;
            position: relative;
        }
        .insurance-label ul {
            list-style: none;
            padding-left: 0;
            margin-bottom: 0;
            font-size: 13px;
        }

        .selectable .checkmark {
            position: absolute;
            top: 10px;
            right: 10px;
            color: #28a745;
            font-weight: bold;
            display: none;
        }
        .option-card.selected .checkmark {
            display: inline !important;
        }

        .title {
            font-weight: 600;
            font-size: 14px;
        }

        .insurance-wrapper {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1rem;
        }

        .option-card {
            border: 2px solid #e0e6ed;
            border-radius: 12px;
            background-color: #fff;
            transition: all 0.3s ease;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.04);
        }

        .option-card:hover {
            border-color: #0d6efd44;
            box-shadow: 0 3px 10px rgba(13, 110, 253, 0.08);
            cursor: pointer;
        }

        .option-card.selected {
            border-color: #0d6efd;
            background-color: #f5faff;
        }

        .insurance-label {
            display: block;
            position: relative;
            gap: 1.25rem;
        }

        .insurance-label .checkmark {
            display: none;
            font-size: 1.2rem;
            color: #198754;
        }

        .option-card.selected .checkmark {
            display: block;
        }

        .insurance-label ul li i {
            width: 18px;
        }

        .option-card {
            cursor: pointer;
        }
        .option-card input[type="radio"] {
            pointer-events: none;
        }

        @media (max-width: 768px) {
            .insurance-wrapper {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
@endpush

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.forEach(function (tooltipTriggerEl) {
                new bootstrap.Tooltip(tooltipTriggerEl);
            });

            setupCardGroup('baby_seat', '.baby-label');
            setupCardGroup('insurance', '.insurance-label');
            updateSummary();
        });

        function updateSummary() {
            const baseTotal = {{ $baseTotal }};
            const selectedInsurance = document.querySelector('input[name="insurance"]:checked');
            const selectedBaby = document.querySelector('input[name="baby_seat"]:checked');

            // const insurancePrice = selectedInsurance ? parseFloat(selectedInsurance.dataset.price || 0) : 0;
            const rentalDays = {{ $rentalDays }}; // passed from Blade
            const perDayInsurancePrice = selectedInsurance ? parseFloat(selectedInsurance.dataset.price || 0) : 0;
            const insurancePrice = perDayInsurancePrice * rentalDays;

            const babyPrice = selectedBaby ? parseFloat(selectedBaby.dataset.price || 0) : 0;

            const insuranceId = selectedInsurance ? selectedInsurance.value : '0';
            const cautionBasic = parseInt(document.getElementById('caution-basic').value || '0');
            const cautionComplete = parseInt(document.getElementById('caution-complete').value || '0');

            const cautionValue = insuranceId === '0' ? cautionBasic : cautionComplete;

            document.querySelector('[name=insurance_price]').value = insurancePrice + babyPrice;
            document.querySelector('[name=caution]').value = cautionValue;
            document.querySelector('.insurance-summary').innerText = new Intl.NumberFormat('fr-FR').format(insurancePrice + babyPrice) + ' DZD';
            document.querySelector('.total-summary').innerText = new Intl.NumberFormat('fr-FR').format(baseTotal + insurancePrice + babyPrice) + ' DZD';
            // document.querySelector('.caution-summary').innerText = new Intl.NumberFormat('fr-FR').format(cautionValue) + ' DZD';
            document.querySelector('.caution-summary').innerText = new Intl.NumberFormat('fr-FR').format(cautionValue) + ' DZD';

            // Update caution box color and icon
            const cautionBox = document.getElementById('caution-box');
            const cautionLabel = cautionBox.querySelector('.caution-label');
            const cautionIcon = cautionBox.querySelector('.caution-icon');
            if (insuranceId === '1') {
                // Protection Complète
                cautionBox.classList.remove('alert-warning');
                cautionBox.classList.add('alert-success');
                cautionLabel.innerHTML = '<i class="fas fa-check-circle me-2 text-success caution-icon"></i><strong>Caution réduite (à l\'agence)</strong>' +
                    '<small class="text-muted ms-2" data-bs-toggle="tooltip" title="Vous bénéficiez d\'une couverture maximale avec un dépôt réduit."><i class="fas fa-info-circle"></i></small>';
                // Refresh tooltip after updating the label
                const newTooltip = cautionBox.querySelector('[data-bs-toggle="tooltip"]');
                if (newTooltip) {
                    new bootstrap.Tooltip(newTooltip);
                }

            } else {
                // Protection Basique
                cautionBox.classList.remove('alert-success');
                cautionBox.classList.add('alert-warning');
                cautionLabel.innerHTML = '<i class="fas fa-exclamation-triangle me-2 text-warning caution-icon"></i><strong>Caution élevée (à l\'agence)</strong>' +
                    '<small class="text-muted ms-2" data-bs-toggle="tooltip" title="Choisir la protection complète permet de réduire la caution."><i class="fas fa-info-circle"></i></small>';
                // Refresh tooltip after updating the label
                const newTooltip = cautionBox.querySelector('[data-bs-toggle="tooltip"]');
                if (newTooltip) {
                    new bootstrap.Tooltip(newTooltip);
                }

            }


        }

        function setupCardGroup(radioName, labelClass) {
            document.querySelectorAll(labelClass).forEach(label => {
                const id = label.dataset.id;
                const radio = document.querySelector(`input[name="${radioName}"][value="${id}"]`);
                const allRadios = document.querySelectorAll(`input[name="${radioName}"]`);

                if (!radio) return;

                label.addEventListener('click', () => {
                    radio.checked = true;
                    allRadios.forEach(r => r.closest('.option-card')?.classList.remove('selected'));
                    radio.closest('.option-card')?.classList.add('selected');
                    updateSummary();
                });

                if (radio.checked) {
                    radio.closest('.option-card')?.classList.add('selected');
                }
            });
        }

        setupCardGroup('baby_seat', '.baby-label');
        setupCardGroup('insurance', '.insurance-label');
        updateSummary();
    </script>
@endpush
