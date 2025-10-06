@extends('admin.layouts.app')
@section('panel')
    <div class="row justify-content-center">
        @if ((request()->routeIs('admin.deposit.list') || request()->routeIs('admin.deposit.method')) && hasRole('admin'))
            <div class="col-12">
                @include('admin.deposit.widget')
            </div>
        @endif

        <div class="col-md-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>@lang('Gateway | Transaction')</th>
                                <th>@lang('Initiated')</th>
                                <th>@lang('User')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Deposit')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Payment Type')</th>
                                <th>@lang('Remaining')</th>
                                <th>@lang('Discount')</th>
                                <th>@lang('Assurance')</th>
                                <th>@lang('Caution')</th>
                                <th>@lang('Baby Seat')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($deposits as $deposit)
                                @php
                                    $details = $deposit->detail ? json_encode($deposit->detail) : null;
                                @endphp
                                <tr>
                                    <td>
                                            <span class="fw-bold">
                                                <a href="{{ appendQuery('method', $deposit->method_code < 5000 ? @$deposit->gateway->alias : $deposit->method_code) }}">
                                                    @if ($deposit->method_code < 5000)
                                                        {{ __(@$deposit->gateway->name) }}
                                                    @else
                                                        @lang('Google Pay')
                                                    @endif
                                                </a>
                                            </span>
                                        <br>
                                        <small> {{ $deposit->trx }} </small>
                                    </td>

                                    <td>
                                        {{ showDateTime($deposit->created_at) }}
                                        <br>{{ diffForHumans($deposit->created_at) }}
                                    </td>
                                    <td>
                                        <span
                                            class="fw-bold">{{ optional($deposit->user)->fullname ?? __('User not found') }}</span>
                                        <br>
                                        @if($deposit->user)
                                            <span class="small">
                                                <a href="{{ appendQuery('search', $deposit->user->username) }}">
                                                    <span>@</span>{{ $deposit->user->username }}
                                                </a>
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ showAmount($deposit->amount) }} + <span class="text--danger"
                                                                                   title="@lang('charge')">{{ showAmount($deposit->charge) }} </span>
                                        <br>
                                        <strong title="@lang('Amount with charge')">
                                            {{ showAmount($deposit->amount + $deposit->charge) }}
                                        </strong>
                                    </td>
                                    <td>
                                        <strong>{{ showAmount($deposit->final_amount, currencyFormat: false) }} {{ __($deposit->method_currency) }}</strong>
                                    </td>
                                    <td>
                                        @php echo $deposit->statusBadge @endphp
                                    </td>

                                    <td>
                                        @if($deposit->rest_amount > 0)
                                            <span class="badge bg-warning">@lang('Partial')</span>
                                        @else
                                            <span class="badge bg-success">@lang('Full')</span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ showAmount($deposit->rest_amount) }}
                                    </td>

                                    <td>
                                        @if($deposit->full_payment_discount > 0)
                                            {{ $deposit->full_payment_discount }}%
                                        @else
                                            —
                                        @endif
                                    </td>

                                    <td>
                                        @php
                                            $insuranceTypes = [
                                                0 => __('Basic'),
                                                1 => __('Complet')
                                            ];
                                            $insuranceType = $insuranceTypes[$deposit->rentLog->insurance ?? 0] ?? '—';
                                            $insurancePrice = $deposit->rentLog->insurance_price ?? 0;
                                        @endphp
                                        {{ $insuranceType }}
                                        @if($insurancePrice > 0)
                                            <br><small class="text-muted">{{ showAmount($insurancePrice) }}</small>
                                        @endif
                                    </td>

                                    <td>
                                        @if(!empty($deposit->rentLog->caution))
                                            {{ showAmount($deposit->rentLog->caution) }}
                                        @else
                                            —
                                        @endif
                                    </td>

                                    <td>
                                        @php
                                            $babySeatTypes = [
                                                0 => __('Sans options'),
                                                1 => __('0-3'),
                                                2 => __('3-5'),
                                                3 => __('6-10')
                                            ];
                                        @endphp
                                        {{ $babySeatTypes[$deposit->rentLog->baby_seat ?? 0] ?? '—' }}
                                    </td>



                                    <td>
                                        <a href="{{ route('admin.deposit.details', $deposit->id) }}"
                                           class="btn btn-sm btn-outline--primary ms-1">
                                            <i class="la la-desktop"></i> @lang('Details')
                                        </a>

                                        @if($deposit->invoice)
                                            <a href="{{ route('admin.admin.invoice.view', $deposit->trx) }}"
                                               class="btn btn-sm btn-outline--info" target="_blank">
                                                <i class="la la-file-invoice"></i> @lang('View Invoice')
                                            </a>
                                        @endif

                                        @if($deposit->contract)
                                            <a href="{{ route('admin.admin.contract.view', $deposit->trx) }}"
                                               class="btn btn-sm btn-outline--info" target="_blank">
                                                <i class="la la-file-invoice"></i> @lang('View Contract')
                                            </a>
                                        @endif

                                        <a href="{{ route('admin.booking.edit',$deposit->id) }}"
                                           class="btn btn-sm btn-outline--warning">
                                            <i class="la la-edit"></i> @lang('Edit')
                                        </a>

                                        <form action="{{ route('admin.booking.delete',$deposit->id) }}" method="POST" style="display:inline-block"
                                              onsubmit="return confirm('Confirmer la suppression ?');">
                                            @csrf
                                            <button class="btn btn-sm btn-outline--danger">
                                                <i class="la la-trash"></i> @lang('Delete')
                                            </button>
                                        </form>



                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                @if ($deposits->hasPages())
                    <div class="card-footer py-4">
                        @php echo paginateLinks($deposits) @endphp
                    </div>
                @endif
            </div><!-- card end -->
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-search-form dateSearch='yes' placeholder='Username / Email'/>
@endpush
