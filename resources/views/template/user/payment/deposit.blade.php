@extends('template.layouts.master')

@section('content')
    <div class="pt-60 pb-60">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <form class="deposit-form" action="{{ route('user.deposit.insert') }}" method="post">
                    @csrf
                    <input name="currency" type="hidden">
                    <div class="card shadow-lg border-0 p-4">
                        <div class="row g-4">
                            <!-- Payment Method Cards -->
                            <div class="col-lg-6">
                                <h5 class="mb-3 fw-semibold text-primary">@lang('Choose Payment Method')</h5>
                                <div class="row g-3 gateway-option-list">
                                    @foreach ($gatewayCurrency as $data)
                                        @php
                                            $discount = floatval($data->full_payment_discount ?? 0);
                                            $partial = floatval($data->allow_one_day_pay ?? 0);
                                        @endphp

                                        <div class="col-12">
                                            <input type="radio"
                                                   class="btn-check gateway-input"
                                                   name="gateway_currency_id"
                                                   id="{{ titleToKey($data->name) }}"
                                                   value="{{ $data->id }}"
                                                   data-gateway-id="{{ $data->id }}"
                                                   data-gateway='@json($data)'
                                                   data-min-amount="{{ showAmount($data->min_amount) }}"
                                                   data-max-amount="{{ showAmount($data->max_amount) }}"
                                                   @if (old('gateway')) @checked(old('gateway') == $data->method_code)
                                                   @else @checked($loop->first) @endif
                                                   autocomplete="off">

                                            <label class="card shadow-sm gateway-option p-3 btn btn-outline-light text-start w-100" for="{{ titleToKey($data->name) }}">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div class="icon-wrapper bg-light rounded-circle p-2 border">
                                                            <img src="{{ getImage(getFilePath('gateway') . '/' . $data->method->image) }}"
                                                                 alt="{{ $data->name }}" class="img-fluid" style="height: 32px; width: auto; max-width: 100%;">
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-1 text--1">{{ __($data->name) }}</h6>
                                                            @if ($discount > 0)
                                                                <span class="badge bg-success">@lang('vous économisez') {{ $discount }}%</span>
                                                            @elseif ($partial > 0)
{{--                                                                <span class="badge bg-warning text-dark">{{ $partial }}% @lang("Jour 1 payé, reste à l'agence")</span>--}}
                                                                <span class="badge bg-warning text-dark">@lang("Payer 1 journée le reste à l'agence")</span>
                                                            @else
                                                                <small class="text-muted">@lang('Standard Payment')</small>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <span class="badge bg-secondary" style="font-size: 0.8rem;">{{ $data->currency }}</span>
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                            </div>

                            <!-- Résumé du Paiement -->
                            <div class="col-lg-6">
                                <h5 class="mb-3 fw-semibold text-primary">@lang('Résumé du paiement')</h5>
                                <div class="bg-light p-4 rounded shadow-sm">

                                    <!-- Montant -->
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">@lang('Montant total')</label>
                                        <div class="d-flex align-items-center">
                                            <input
                                                class="form-control amount text-end"
                                                name="amount"
                                                type="text"
                                                value="{{ old('amount', $amount) }}"
                                                readonly
                                                style="max-width: 200px;"
                                            >
                                            <span class="me-2 fs-5 text-muted" style="margin-left: 10px">{{ gs('cur_sym') }}</span>

                                        </div>
                                    </div>


                                    <!-- Frais de traitement -->
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold d-flex justify-content-between align-items-center">
                                            @lang('Frais de traitement')
                                            <i class="las la-info-circle text-muted" data-bs-toggle="tooltip" title="@lang('Frais appliqués selon le moyen de paiement choisi')"></i>
                                        </label>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>@lang('Frais estimés')</span>
                                            <span class="processing-fee fw-bold">0.00</span>
                                            <span class="ms-1">{{ __(gs('cur_text')) }}</span>
                                        </div>
                                    </div>

                                    <!-- Option de paiement (remise ou paiement partiel) -->
                                    <div class="payment-condition d-none pt-2">
                                        <label class="form-label fw-semibold">@lang('Option de paiement')</label>
                                        <div class="payment-condition-message small text-muted"></div>
                                    </div>

                                    <!-- Total à payer -->
                                    <div class="mt-3 border-top pt-3">
                                        <label class="form-label fw-bold text-dark mb-1">@lang('Montant à payer')</label>
                                        <div class="d-flex justify-content-between align-items-center h5">
                                            <span class="final-amount fw-bold text-primary">0.00</span>
                                            <span class="ms-1 fw-semibold text-primary">{{ __(gs('cur_text')) }}</span>
                                        </div>
                                    </div>

                                    <!-- Note éventuelle -->
                                    <div class="deposit-note d-none mt-3">
                                        <label class="form-label fw-semibold">@lang('Remarque')</label>
                                        <div class="deposit-note-text small text-muted"></div>
                                    </div>

                                    <!-- Conversion de devise -->
                                    <div class="conversion-currency d-none mt-3">
                                        <label class="form-label fw-semibold">
                                            @lang('Montant converti en') <span class="gateway-currency fw-semibold"></span>
                                        </label>
                                        <div class="in-currency text-muted small"></div>
                                    </div>

                                    <!-- Message crypto -->
                                    <div class="crypto-message d-none text-info small mt-2">
                                        @lang('Conversion en') <span class="gateway-currency"></span> — @lang('le montant exact s’affichera à l’étape suivante.')
                                    </div>

                                    <!-- Bouton -->
                                    <button class="btn btn-primary w-100 mt-4" type="submit" disabled>
                                        @lang('Confirmer le paiement')
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                    <input type="hidden" name="gateway_currency_id" id="gateway_currency_id_input">
                    <input type="hidden" name="gateway" id="gateway_input">
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <style>
        .btn-check:checked + .gateway-option {
            border: 2px solid #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            background-color: #e9f3ff;
        }

        .gateway-option:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }
        .icon-wrapper {
            min-width: 48px;
            width: 100px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .gateway-option .d-flex {
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        @media (max-width: 576px) {
            .gateway-option h6,
            .gateway-option .badge,
            .gateway-option small {
                font-size: 0.875rem;
            }
        }


    </style>
@endpush

@push('script')
    <script>
        const oneDayPrice = {{ $oneDayPrice }};
        const fuelServiceFee = {{ $fuelServiceFee ?? 0 }};
        const totalDays = {{ $totalDays }};
    </script>

    <script>
        "use strict";
        (function ($) {
            let amount = parseFloat($('.amount').val() || 0);
            let gateway, minAmount, maxAmount;

            $('.amount').on('input', function () {
                amount = parseFloat($(this).val()) || 0;
                calculation();
            });

            $('.gateway-input').on('change', gatewayChange);

            {{--function gatewayChange() {--}}
            {{--    let selected = $('.gateway-input:checked');--}}
            {{--    gateway = selected.data('gateway');--}}
            {{--    minAmount = selected.data('min-amount');--}}
            {{--    maxAmount = selected.data('max-amount');--}}

            {{--    // ✅ Update the hidden input with the correct ID--}}
            {{--    $('#gateway_currency_id_input').val(selected.data('gateway-id'));--}}
            {{--    $('#gateway_input').val(selected.val()); // ✅ add this--}}

            {{--    const tooltipText = `${parseFloat(gateway.percent_charge).toFixed(2)}% + ${parseFloat(gateway.fixed_charge).toFixed(2)} {{ __(gs('cur_text')) }}`;--}}
            {{--    $(".proccessing-fee-info").attr("data-bs-original-title", tooltipText);--}}
            {{--    calculation();--}}
            {{--}--}}

            function gatewayChange() {
                let selected = $('.gateway-input:checked');
                gateway = selected.data('gateway');
                minAmount = selected.data('min-amount');
                maxAmount = selected.data('max-amount');

                // Update hidden inputs
                $('#gateway_currency_id_input').val(selected.data('gateway-id'));
                $('#gateway_input').val(selected.val());

                // Disable/enable options based on duration
                if (totalDays === 1) {
                    // Filter to only gateways that allow one day payment
                    $('.gateway-input').each(function () {
                        const $input = $(this);
                        const data = $input.data('gateway');
                        if (parseFloat(data.allow_one_day_pay || 0) > 0) {
                            $input.closest('.col-12').show();
                        } else {
                            $input.closest('.col-12').hide();
                        }
                    });

                    // Re-select the first valid option if current is hidden
                    if (!selected.closest('.col-12').is(':visible')) {
                        const $first = $('.gateway-input:visible').first();
                        $first.prop('checked', true);
                        gateway = $first.data('gateway');
                        $('#gateway_currency_id_input').val($first.data('gateway-id'));
                        $('#gateway_input').val($first.val());
                    }

                } else {
                    $('.gateway-input').closest('.col-12').show(); // Show all options again
                }

                // Set tooltip info
                const tooltipText = `${parseFloat(gateway.percent_charge).toFixed(2)}% + ${parseFloat(gateway.fixed_charge).toFixed(2)} {{ __(gs('cur_text')) }}`;
                $(".proccessing-fee-info").attr("data-bs-original-title", tooltipText);

                calculation();
            }


            gatewayChange();

            function calculation() {
                if (!gateway) return;

                const percent = parseFloat(gateway.percent_charge);
                const fixed = parseFloat(gateway.fixed_charge);
                const discount = parseFloat(gateway.full_payment_discount || 0);
                const partial = parseFloat(gateway.allow_one_day_pay || 0);

                let charge = (amount * percent / 100) + fixed;
                let total = amount + charge;

                // Apply discount
                let finalAmount = total;
                let message = '';
                let badge = '';

                const $finalText = $(".final-amount");
                const $inCurrency = $(".in-currency");
                const $message = $(".payment-condition-message");
                const $condition = $(".payment-condition");

                $condition.addClass('d-none');

                if (discount > 0) {
                    $('#payment_mode_input').val('full');
                } else if (partial > 0) {
                    $('#payment_mode_input').val('partial');
                } else {
                    $('#payment_mode_input').val('standard');
                }


                if (discount > 0) {
                    const discountedBase = amount - (amount * discount / 100);
                    finalAmount = discountedBase + charge;

                    badge = `<span class="badge bg-success me-2">Remise</span>`;
                    message = `${badge} <strong>${discount}%</strong> de réduction appliquée.<br>Montant à payer : <strong>${finalAmount.toFixed(2)} {{ __(gs('cur_text')) }}</strong>.`;
                    $condition.removeClass('d-none');

                } else if(partial > 0) {
                    console.log(partial);
                    const partialBase = oneDayPrice + fuelServiceFee;
                    finalAmount = partialBase + charge;

                    badge = `<span class="badge bg-warning text-dark me-2">Paiement 1 jour</span>`;
                    message = `${badge} Vous payez <strong>${partialBase.toFixed(2)} {{ __(gs('cur_text')) }}</strong> pour la première journée. Le reste sera payé à l'agence.`;
                    $condition.removeClass('d-none');
                }

            // Display summary
                $finalText.text(finalAmount.toFixed(2));
                $(".processing-fee").text(charge.toFixed(2));
                $message.html(message);

                // Currency conversion
                $("input[name=currency]").val(gateway.currency);
                $(".gateway-currency").text(gateway.currency);

                if (gateway.currency !== "{{ gs('cur_text') }}" && gateway.method.crypto !== 1) {
                    $(".conversion-currency").removeClass('d-none');
                    // $inCurrency.text((finalAmount * gateway.rate).toFixed(gateway.method.crypto ? 8 : 2));
                    const convertedAmount = (finalAmount * gateway.rate).toFixed(gateway.method.crypto ? 8 : 2);
                    $inCurrency.text(`${convertedAmount} ${gateway.currency}`);

                } else {
                    $(".conversion-currency").addClass('d-none');
                }

                // Crypto message
                if (gateway.method.crypto === 1) {
                    $(".crypto-message").removeClass('d-none');
                } else {
                    $(".crypto-message").addClass('d-none');
                }

                // Enable or disable button
                if (amount < minAmount || amount > maxAmount) {
                    $(".deposit-form button[type=submit]").prop("disabled", true);
                } else {
                    $(".deposit-form button[type=submit]").prop("disabled", false);
                }
            }

            // Enable tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

        })(jQuery);
    </script>
@endpush


{{--@push('script')--}}
{{--    <script>--}}
{{--        "use strict";--}}
{{--        (function($) {--}}

{{--            var amount = parseFloat($('.amount').val() || 0);--}}
{{--            var gateway, minAmount, maxAmount;--}}


{{--            $('.amount').on('input', function(e) {--}}
{{--                amount = parseFloat($(this).val());--}}
{{--                if (!amount) {--}}
{{--                    amount = 0;--}}
{{--                }--}}
{{--                calculation();--}}
{{--            });--}}

{{--            $('.gateway-input').on('change', function(e) {--}}
{{--                gatewayChange();--}}
{{--            });--}}

{{--            function gatewayChange() {--}}
{{--                let gatewayElement = $('.gateway-input:checked');--}}
{{--                let methodCode = gatewayElement.val();--}}

{{--                gateway = gatewayElement.data('gateway');--}}
{{--                minAmount = gatewayElement.data('min-amount');--}}
{{--                maxAmount = gatewayElement.data('max-amount');--}}

{{--                let processingFeeInfo =--}}
{{--                    `${parseFloat(gateway.percent_charge).toFixed(2)}% with ${parseFloat(gateway.fixed_charge).toFixed(2)} {{ __(gs('cur_text')) }} charge for payment gateway processing fees`--}}
{{--                $(".proccessing-fee-info").attr("data-bs-original-title", processingFeeInfo);--}}
{{--                calculation();--}}
{{--            }--}}

{{--            gatewayChange();--}}

{{--            $(".more-gateway-option").on("click", function(e) {--}}
{{--                let paymentList = $(".gateway-option-list");--}}
{{--                paymentList.find(".gateway-option").removeClass("d-none");--}}
{{--                $(this).addClass('d-none');--}}
{{--                paymentList.animate({--}}
{{--                    scrollTop: (paymentList.height() - 60)--}}
{{--                }, 'slow');--}}
{{--            });--}}

{{--            function calculation() {--}}
{{--                if (!gateway) return;--}}
{{--                $(".gateway-limit").text(minAmount + " - " + maxAmount);--}}

{{--                let percentCharge = 0;--}}
{{--                let fixedCharge = 0;--}}
{{--                let totalPercentCharge = 0;--}}

{{--                if (amount) {--}}
{{--                    percentCharge = parseFloat(gateway.percent_charge);--}}
{{--                    fixedCharge = parseFloat(gateway.fixed_charge);--}}
{{--                    totalPercentCharge = parseFloat(amount / 100 * percentCharge);--}}
{{--                }--}}

{{--                let totalCharge = parseFloat(totalPercentCharge + fixedCharge);--}}
{{--                let totalAmount = parseFloat((amount || 0) + totalPercentCharge + fixedCharge);--}}

{{--                $(".final-amount").text(totalAmount.toFixed(2));--}}
{{--                $(".processing-fee").text(totalCharge.toFixed(2));--}}
{{--                $("input[name=currency]").val(gateway.currency);--}}
{{--                $(".gateway-currency").text(gateway.currency);--}}

{{--                if (amount < Number(gateway.min_amount) || amount > Number(gateway.max_amount)) {--}}
{{--                    $(".deposit-form button[type=submit]").attr('disabled', true);--}}
{{--                } else {--}}
{{--                    $(".deposit-form button[type=submit]").removeAttr('disabled');--}}
{{--                }--}}

{{--                if (gateway.currency != "{{ gs('cur_text') }}" && gateway.method.crypto != 1) {--}}
{{--                    $('.deposit-form').addClass('adjust-height')--}}

{{--                    $(".gateway-conversion, .conversion-currency").removeClass('d-none');--}}
{{--                    $(".gateway-conversion").find('.deposit-info__input .text').html(--}}
{{--                        `1 {{ __(gs('cur_text')) }} = <span class="rate">${parseFloat(gateway.rate).toFixed(2)}</span>  <span class="method_currency">${gateway.currency}</span>`--}}
{{--                    );--}}
{{--                    $('.in-currency').text(parseFloat(totalAmount * gateway.rate).toFixed(gateway.method.crypto == 1 ? 8 : 2))--}}
{{--                } else {--}}
{{--                    $(".gateway-conversion, .conversion-currency").addClass('d-none');--}}
{{--                    $('.deposit-form').removeClass('adjust-height')--}}
{{--                }--}}

{{--                if (gateway.method.crypto == 1) {--}}
{{--                    $('.crypto-message').removeClass('d-none');--}}
{{--                } else {--}}
{{--                    $('.crypto-message').addClass('d-none');--}}
{{--                }--}}

{{--                // === Apply Full Payment Discount or Allow One Day Pay ===--}}
{{--                const discount = parseFloat(gateway.full_payment_discount || 0);--}}
{{--                const allowOneDay = parseFloat(gateway.allow_one_day_pay || 0);--}}
{{--                const $paymentCondition = $('.payment-condition');--}}
{{--                const $message = $('.payment-condition-message');--}}

{{--                $paymentCondition.addClass('d-none'); // Hide default--}}

{{--// Only one condition applies at a time--}}
{{--                if (discount > 0) {--}}
{{--                    const discountedAmount = parseFloat(amount - (amount * discount / 100));--}}
{{--                    const finalAmount = parseFloat(discountedAmount + totalCharge);--}}

{{--                    $(".final-amount").text(finalAmount.toFixed(2));--}}
{{--                    $(".in-currency").text((finalAmount * gateway.rate).toFixed(gateway.method.crypto == 1 ? 8 : 2));--}}

{{--                    $paymentCondition.removeClass('d-none');--}}
{{--                    $message.text(`A discount of ${discount}% has been applied. You will pay ${finalAmount.toFixed(2)} {{ __(gs('cur_text')) }}`);--}}
{{--                    $paymentCondition.removeClass('d-none');--}}
{{--                    $message.html(`--}}
{{--    <span class="badge bg-success me-2">Discount</span>--}}
{{--    <strong>${discount}%</strong> discount applied.--}}
{{--    <br>--}}
{{--    Total payable now: <strong>${finalAmount.toFixed(2)} {{ __(gs('cur_text')) }}</strong>.--}}
{{--`);--}}

{{--                } else if (allowOneDay > 0) {--}}
{{--                const partialPay = parseFloat(amount * allowOneDay / 100);--}}
{{--                const finalAmount = parseFloat(partialPay + totalCharge);--}}

{{--                $(".final-amount").text(finalAmount.toFixed(2));--}}
{{--                $(".in-currency").text((finalAmount * gateway.rate).toFixed(gateway.method.crypto == 1 ? 8 : 2));--}}

{{--                $paymentCondition.removeClass('d-none');--}}
{{--                $message.text(`You will pay only ${partialPay.toFixed(2)} {{ __(gs('cur_text')) }} now (${allowOneDay}% of the total), and the rest at the counter.`);--}}
{{--                $paymentCondition.removeClass('d-none');--}}
{{--                    $message.html(`--}}
{{--    <span class="badge bg-warning text-dark me-2">Partial Payment</span>--}}
{{--    You will pay <strong>${partialPay.toFixed(2)} {{ __(gs('cur_text')) }}</strong> now--}}
{{--    (${allowOneDay}% of the total),--}}
{{--    <br>--}}
{{--    and the remaining amount at the counter.--}}
{{--`);--}}

{{--                }--}}
{{--        }--}}
{{--            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))--}}
{{--            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {--}}
{{--                return new bootstrap.Tooltip(tooltipTriggerEl)--}}
{{--            })--}}
{{--            $('.gateway-input').change();--}}
{{--        })(jQuery);--}}
{{--    </script>--}}
{{--@endpush--}}
