
@extends('admin.layouts.app')
@section('panel')
    @push('topBar')
        @include('admin.gateways.top_bar')
    @endpush
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route('admin.gateway.automatic.update', $gateway->code) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <input name="alias" type="hidden" value="{{ $gateway->alias }}">
                    <input name="description" type="hidden" value="{{ $gateway->description }}">

                    <div class="card-body">
                        <div class="payment-method-item block-item">
                            <div class="row align-items-center gy-1">
                                <div class="col-lg-8 col-sm-6">
                                    <h3>{{ __($gateway->name) }}</h3>
                                </div>
                                <div class="col-lg-4 col-sm-6">
                                    @if (count($supportedCurrencies) > 0)
                                        <div class="input-group d-flex flex-nowrap justify-content-sm-end">
                                            <select class="newCurrencyVal ">
                                                <option value="">@lang('Select currency')</option>
                                                @forelse($supportedCurrencies as $currency => $symbol)
                                                    <option data-symbol="{{ $symbol }}" value="{{ $currency }}">{{ __($currency) }} </option>
                                                @empty
                                                    <option value="">@lang('No available currency support')</option>
                                                @endforelse

                                            </select>
                                            <button class="btn btn--primary input-group-text newCurrencyBtn" data-crypto="{{ $gateway->crypto }}" data-name="{{ $gateway->name }}" type="button">@lang('Add new')</button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="gateway-body">
                                <div class="gateway-thumb">
                                    <div class="thumb">
                                        <x-image-uploader class="w-100" type="gateway" image="{{ $gateway->image }}" :required=false />
                                    </div>
                                </div>
                                <div class="gateway-content">
                                    @if ($gateway->code < 1000 && $gateway->extra)
                                        <div class="payment-method-body mt-2">
                                            <h4 class="mb-3 payment-method-body-title">@lang('Configurations')</h4>
                                            <div class="row">
                                                @foreach ($gateway->extra as $key => $param)
                                                    <div class="form-group col-lg-6">
                                                        <label>{{ __(@$param->title) }}</label>
                                                        <div class="input-group">
                                                            <input class="form-control" type="text" value="{{ route($param->value) }}" readonly>
                                                            <button class="copyInput input-group-text" type="button" title="@lang('Copy')"><i class="fas fa-copy"></i></button>
                                                        </div>
                                                        @if ($key == 'cron')
                                                            <small><i class="las la-info-circle"></i> @lang('Set the URL to your server\'s cron job to validate the payment.')</small>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    <div class="payment-method-body mt-2">
                                        <h4 class="mb-3 payment-method-body-title">@lang('Global Setting for') {{ __($gateway->name) }}</h4>
                                        <div class="row">
                                            @foreach ($parameters->where('global', true) as $key => $param)
                                                <div class="form-group col-xl-6 col-lg-12 col-md-6">
                                                    <label>{{ __(@$param->title) }}</label>
                                                    <input class="form-control" name="global[{{ $key }}]" type="text" value="{{ @$param->value }}" required>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="form-group col-xl-6 col-lg-12 col-md-6">
                                            <label>@lang('Mode')</label>
                                            <select class="form-control" name="mode" required>
                                                <option value="sandbox" {{ $gateway->mode === 'sandbox' ? 'selected' : '' }}>@lang('Sandbox')</option>
                                                <option value="live" {{ $gateway->mode === 'live' ? 'selected' : '' }}>@lang('Live')</option>
                                            </select>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- payment-method-item start -->

                        @isset($gateway->currencies)
                            @foreach ($gateway->currencies as $gatewayCurrency)
                                <input name="currency[{{ $currencyIndex }}][symbol]" type="hidden" value="{{ $gatewayCurrency->symbol }}">
                                <div class="payment-method-item block-item child--item">
                                    <div class="payment-method-header">
                                        <div class="content">
                                            <div class="d-flex justify-content-between">
                                                <h4 class="mb-3">{{ __($gateway->name) }} - {{ __($gatewayCurrency->currency) }}</h4>
                                                <div class="remove-btn">
                                                    <button class="btn btn--danger confirmationBtn" data-question="@lang('Are you sure to delete this gateway currency?')" data-action="{{ route('admin.gateway.automatic.remove', $gatewayCurrency->id) }}" type="button">
                                                        <i class="la la-trash-o me-2"></i>@lang('Remove')
                                                    </button>
                                                </div>

                                            </div>
                                            <div class="form-group payment-method-title-input">
                                                <input class="form-control" name="currency[{{ $currencyIndex }}][name]" type="text" value="{{ $gatewayCurrency->name }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="payment-method-body">
                                        <div class="row g-4">
                                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                                                <div class="card border--primary">
                                                    <h5 class="card-header bg--primary">@lang('Range')</h5>
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label>@lang('Minimum Amount')</label>
                                                            <div class="input-group">
                                                                <input class="form-control minAmount" name="currency[{{ $currencyIndex }}][min_amount]" type="number" value="{{ getAmount($gatewayCurrency->min_amount) }}" step="any" required>
                                                                <div class="input-group-text">{{ __(gs('cur_text')) }}</div>
                                                            </div>
                                                            <span class="min-amount-error-message text--danger"></span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>@lang('Maximum Amount')</label>
                                                            <div class="input-group">
                                                                <input class="form-control maxAmount" name="currency[{{ $currencyIndex }}][max_amount]" type="number" value="{{ getAmount($gatewayCurrency->max_amount) }}" step="any" required>
                                                                <div class="input-group-text">{{ __(gs('cur_text')) }}</div>
                                                            </div>
                                                            <span class="max-amount-error-message text--danger"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                                                <div class="card border--primary">
                                                    <h5 class="card-header bg--primary">@lang('Charge')</h5>
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label>@lang('Fixed Charge')</label>
                                                            <div class="input-group">
                                                                <input class="form-control" name="currency[{{ $currencyIndex }}][fixed_charge]" type="number" value="{{ getAmount($gatewayCurrency->fixed_charge) }}" step="any" required>
                                                                <div class="input-group-text">{{ __(gs('cur_text')) }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>@lang('Percent Charge')</label>
                                                            <div class="input-group">
                                                                <input class="form-control" name="currency[{{ $currencyIndex }}][percent_charge]" type="number" value="{{ getAmount($gatewayCurrency->percent_charge) }}" step="any" required>
                                                                <div class="input-group-text">%</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                                                <div class="card border--primary">
                                                    <h5 class="card-header bg--primary">@lang('Currency')</h5>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>@lang('Currency')</label>
                                                                    <input class="form-control border-radius-5 " name="currency[{{ $currencyIndex }}][currency]" type="text" value="{{ $gatewayCurrency->currency }}" readonly>
                                                                </div>

                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>@lang('Symbol')</label>
                                                                    <input class="form-control border-radius-5 symbl" name="currency[{{ $currencyIndex }}][symbol]" data-crypto="{{ $gateway->crypto }}" type="text" value="{{ $gatewayCurrency->symbol }}" required>
                                                                </div>

                                                            </div>
                                                        </div>
{{--                                                        <div class="form-group">--}}
{{--                                                            <label>@lang('Rate')</label>--}}
{{--                                                            <div class="input-group">--}}
{{--                                                                <div class="input-group-text">1 {{ gs('cur_text') }} =</div>--}}
{{--                                                                <input class="form-control" name="currency[{{ $currencyIndex }}][rate]" type="number" value="{{ getAmount($gatewayCurrency->rate) }}" step="any" required>--}}
{{--                                                                <div class="input-group-text"><span class="currency_symbol">{{ __($gatewayCurrency->baseSymbol()) }}</span></div>--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}

                                                        <div class="form-group">
                                                            <label>@lang('Taux de Change')</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text">
                                                                    1 {{ __(gs('cur_text')) }} =
                                                                </span>
                                                                <input class="form-control exchange_rate_input" name="currency[{{ $currencyIndex }}][rate]" type="number" step="any" required readonly>
                                                                <div class="input-group-text"><span id="currency_symbol">€</span></div>
                                                            </div>
                                                            <small class="form-text text-muted">
                                                                @lang('Taux mis à jour automatiquement depuis CurrencyLayer')
                                                            </small>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>@lang('Full Payment Discount (%)')</label>
                                                                    <input class="form-control" name="currency[{{ $currencyIndex }}][full_payment_discount]" type="number" step="0.01" value="{{ getAmount($gatewayCurrency->full_payment_discount) ?? 0 }}">
                                                                </div>
                                                            </div>

{{--                                                            <div class="col-md-6">--}}
{{--                                                                <div class="form-group">--}}
{{--                                                                    <label>@lang('Advance Payement (%)')</label>--}}
{{--                                                                    <input class="form-control" name="currency[{{ $currencyIndex }}][allow_one_day_pay]" type="number" step="0.01" value="{{ getAmount($gatewayCurrency->allow_one_day_pay) ?? 0 }}"  >--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label d-flex justify-content-between align-items-center">
                                                                        @lang('Paiement 1 journée')
                                                                        <div class="form-check form-switch m-0">
                                                                            <input class="form-check-input"
                                                                                   type="checkbox"
                                                                                   name="currency[{{ $currencyIndex }}][allow_one_day_pay]"
                                                                                   value="1"
                                                                                {{ $gatewayCurrency->allow_one_day_pay ? 'checked' : '' }}>
                                                                        </div>
                                                                    </label>
                                                                </div>
                                                            </div>


                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                            @if ($parameters->where('global', false)->count() != 0)
                                                @php
                                                    $globalParameters = json_decode($gatewayCurrency->gateway_parameter);
                                                @endphp
                                                <div class="col-lg-12">
                                                    <div class="card border--primary mt-4">
                                                        <h5 class="card-header bg--dark">@lang('Configuration')</h5>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                @foreach ($parameters->where('global', false) as $key => $param)
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>{{ __($param->title) }}</label>
                                                                            <input class="form-control" name="currency[{{ $currencyIndex }}][param][{{ $key }}]" type="text" value="{{ $globalParameters->$key }}" required>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @php $currencyIndex++ @endphp
                            @endforeach
                        @endisset

                        <!-- payment-method-item end -->

                        <!-- **new payment-method-item start -->
                        <div class="payment-method-item child--item newMethodCurrency d-none">
                            <input class="currencySymbol" name="currency[{{ $currencyIndex }}][symbol]" type="hidden" disabled>
                            <div class="payment-method-header">
                                <div class="content">
                                    <div class="d-flex justify-content-between">
                                        <div class="form-group">
                                            <h4 class="mb-3" id="payment_currency_name">@lang('Name')</h4>
                                            <input class="form-control" name="currency[{{ $currencyIndex }}][name]" type="text" disabled required>
                                        </div>
                                        <div class="remove-btn">
                                            <button class="btn btn-danger newCurrencyRemove" type="button">
                                                <i class="la la-trash-o me-2"></i>@lang('Remove')
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="payment-method-body">
                                <div class="row g-4">
                                    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                                        <div class="card border--primary">
                                            <h5 class="card-header bg--primary">@lang('Range')</h5>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label>@lang('Minimum Amount')</label>
                                                    <div class="input-group">
                                                        <div class="input-group-text">{{ __(gs('cur_text')) }}</div>
                                                        <input class="form-control minAmount" name="currency[{{ $currencyIndex }}][min_amount]" type="number" disabled step="any" required>
                                                    </div>
                                                    <span class="min-amount-error-message text--danger"></span>
                                                </div>

                                                <div class="form-group">
                                                    <label>@lang('Maximum Amount')</label>
                                                    <div class="input-group">
                                                        <div class="input-group-text">{{ __(gs('cur_text')) }}</div>
                                                        <input class="form-control maxAmount" name="currency[{{ $currencyIndex }}][max_amount]" type="number" disabled step="any" required>
                                                    </div>
                                                    <span class="max-amount-error-message text--danger"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                                        <div class="card border--primary">
                                            <h5 class="card-header bg--primary">@lang('Charge')</h5>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label>@lang('Fixed Charge')</label>
                                                    <div class="input-group">
                                                        <div class="input-group-text">{{ __(gs('cur_text')) }}</div>
                                                        <input class="form-control" name="currency[{{ $currencyIndex }}][fixed_charge]" type="number" disabled step="any" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>@lang('Percent Charge')</label>
                                                    <div class="input-group">
                                                        <div class="input-group-text">%</div>
                                                        <input class="form-control" name="currency[{{ $currencyIndex }}][percent_charge]" type="number" disabled step="any" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                                        <div class="card border--primary">
                                            <h5 class="card-header bg--primary">@lang('Currency')</h5>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>@lang('Currency')</label>
                                                            <input class="form-control currencyText border-radius-5" name="currency[{{ $currencyIndex }}][currency]" type="step" disabled readonly>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>@lang('Symbol')</label>
                                                            <input class="form-control border-radius-5 symbl" name="currency[{{ $currencyIndex }}][symbol]" type="text" disabled ata-crypto="{{ $gateway->crypto }}" disabled>
                                                        </div>
                                                    </div>
                                                </div>

{{--                                                <div class="form-group">--}}
{{--                                                    <label>@lang('Rate')</label>--}}
{{--                                                    <div class="input-group">--}}
{{--                                                        <span class="input-group-text">--}}
{{--                                                            1 {{ __(gs('cur_text')) }} =--}}
{{--                                                        </span>--}}
{{--                                                        <input class="form-control" name="currency[{{ $currencyIndex }}][rate]" type="number" disabled step="any" required>--}}
{{--                                                        <div class="input-group-text"><span class="currency_symbol"></span></div>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}

                                                <div class="form-group">
                                                    <label>@lang('Taux de Change')</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">
                                                            1 {{ __(gs('cur_text')) }} =
                                                        </span>
                                                        <input class="form-control exchange_rate_input" name="currency[{{ $currencyIndex }}][rate]" type="number" step="any" required readonly disabled>
                                                        <div class="input-group-text"><span id="currency_symbol">€</span></div>
                                                    </div>
                                                    <small class="form-text text-muted">
                                                        @lang('Taux mis à jour automatiquement depuis CurrencyLayer')
                                                    </small>
                                                </div>


                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>@lang('Full Payment Discount (%)')</label>
                                                            <input class="form-control" name="currency[{{ $currencyIndex }}][full_payment_discount]" type="number" step="0.01" value="{{ getAmount($gatewayCurrency->full_payment_discount) ?? 0 }}" disabled >
                                                        </div>
                                                    </div>

{{--                                                    <div class="col-md-6">--}}
{{--                                                        <div class="form-group">--}}
{{--                                                            <label>@lang('Advance Payement (%)')</label>--}}
{{--                                                            <input class="form-control" name="currency[{{ $currencyIndex }}][allow_one_day_pay]" type="number" step="0.01" value="{{ getAmount($gatewayCurrency->allow_one_day_pay) ?? 0 }}" disabled >--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label d-flex justify-content-between align-items-center">
                                                                @lang('Paiement 1 journée')
                                                                <div class="form-check form-switch m-0">
                                                                    <input class="form-check-input"
                                                                           type="checkbox"
                                                                           name="currency[{{ $currencyIndex }}][allow_one_day_pay]"
                                                                           value="1"
                                                                        {{ $gatewayCurrency->allow_one_day_pay ? 'checked' : '' }} disabled >
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($parameters->where('global', false)->count() != 0)
                                        <div class="col-lg-12">
                                            <div class="card border--primary mt-4">
                                                <h5 class="card-header bg--dark">@lang('Configuration')</h5>
                                                <div class="card-body">
                                                    <div class="row">
                                                        @foreach ($parameters->where('global', false) as $key => $param)
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>{{ __($param->title) }}</label>
                                                                    <input class="form-control" name="currency[{{ $currencyIndex }}][param][{{ $key }}]" type="text" disabled required>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                        <!-- **new payment-method-item end -->
                    </div>
                    <div class="card-footer">
                        <button class="btn btn--primary w-100 h-45" type="submit">
                            @lang('Submit')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-confirmation-modal />

@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('admin.gateway.automatic.index') }}" />
@endpush

@push('script')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const base = "{{ gs('cur_text') }}"; // e.g., "DZD"
            const target = "EUR"; // Hardcoded target currency
            const apiKey = "7072647c873db9daca3ebfc6ea3c653d";

            const rateKey = base + target; // e.g., "DZDEUR"
            const url = `https://api.currencylayer.com/live?access_key=${apiKey}&currencies=${target}&source=${base}&format=1`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.quotes && data.quotes[rateKey]) {
                        const rate = parseFloat(data.quotes[rateKey]).toFixed(4);

                        // Apply to all visible rate inputs
                        document.querySelectorAll('.exchange_rate_input').forEach(input => {
                            input.value = rate;

                            const symbolSpan = input
                                .closest('.input-group')
                                ?.querySelector('.currency_symbol');
                            if (symbolSpan) {
                                symbolSpan.innerText = target;
                            }
                        });
                    } else {
                        console.error("CurrencyLayer Error:", data.error || 'Missing rate');
                    }
                })
                .catch(error => {
                    console.error("Exchange rate fetch failed:", error);
                });
        });
    </script>
    <script>
        (function($) {
            "use strict";

            $('.newCurrencyBtn').on('click', function() {
                var form = $('.newMethodCurrency');
                var getCurrencySelected = $('.newCurrencyVal').find(':selected').val();
                var currency = $(this).data('crypto') == 1 ? 'USD' : `${getCurrencySelected}`;
                if (!getCurrencySelected) return;
                form.find('input').removeAttr('disabled');
                var symbol = $('.newCurrencyVal').find(':selected').data('symbol');
                form.find('.currencyText').val(getCurrencySelected);
                form.find('.currency_symbol').text(currency);
                $('#payment_currency_name').text(`${$(this).data('name')} - ${getCurrencySelected}`);
                form.removeClass('d-none');
                $('html, body').animate({
                    scrollTop: $('html, body').height()
                }, 'slow');

                $('.newCurrencyRemove').on('click', function() {
                    form.find('input').val('');
                    form.remove();
                });
            });

            $('.symbl').on('input', function() {
                var curText = $(this).data('crypto') == 1 ? 'USD' : $(this).val();
                $(this).parents('.payment-method-body').find('.currency_symbol').text(curText);
            });

            $('.copyInput').on('click', function(e) {
                var copybtn = $(this);
                var input = copybtn.closest('.input-group').find('input');
                if (input && input.select) {
                    input.select();
                    try {
                        document.execCommand('SelectAll')
                        document.execCommand('Copy', false, null);
                        input.blur();
                        notify('success', `Copied: ${copybtn.closest('.input-group').find('input').val()}`);
                    } catch (err) {
                        alert('Please press Ctrl/Cmd + C to copy');
                    }
                }
            });


            let minAmountValue;
            let maxAmountValue;
            let hasError = false;

            function validateInput() {
                let input = $(this);
                let container = input.closest('.card-body');
                let minAmount = container.find('.minAmount');
                let maxAmount = container.find('.maxAmount');
                let minAmountErrorMessage = container.find('.min-amount-error-message');
                let maxAmountErrorMessage = container.find('.max-amount-error-message');

                minAmountValue = Number(minAmount.val());
                maxAmountValue = Number(maxAmount.val());

                if (!minAmountValue) {
                    minAmountErrorMessage.text('@lang('Minimum amount field is required')');
                    maxAmountErrorMessage.empty();
                    return;
                }

                if (!maxAmountValue) {
                    maxAmountErrorMessage.text('@lang('Maximum amount field is required')');
                    minAmountErrorMessage.empty();
                    return;
                }

                if (minAmountValue >= maxAmountValue) {
                    minAmountErrorMessage.text('@lang('Minimum amount should be less than maximum amount')');
                    maxAmountErrorMessage.empty();
                    hasError = true;
                    return;
                }
                minAmountErrorMessage.empty();
                maxAmountErrorMessage.empty();
                hasError = false;
            }


            $(document).on('input', '.minAmount, .maxAmount', validateInput);

            $('form').on('submit', function(e) {
                validateInput();
                if (hasError) {
                    e.preventDefault();
                }
            });

        })(jQuery);
    </script>
@endpush
