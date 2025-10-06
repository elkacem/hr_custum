@forelse($plans as $plan)
    <div class="col-sm-10 col-md-6 col-xl-3">
        <div class="plan__item">
            <div class="plan__header">
                <h5 class="plan__title">{{ __(@$plan->name) }}</h5>
                <div class="plan__header-price">
                    <div class="price">
                        <span class="d-block title">{{ getAmount($plan->price) }}</span>
                        <span class="info d-block">/ @lang('per ride')</span>
                    </div>
                </div>
            </div>
            <div class="plan__body">
                <ul>
                    @foreach (@$plan->included ?? [] as $in)
                        <li><i class="las la-check"></i> {{ __(@$in) }}</li>
                    @endforeach
                    @foreach (@$plan->excluded ?? [] as $ex)
                        <li><i class="las la-times"></i> {{ __(@$ex) }}</li>
                    @endforeach

                </ul>
                @auth
                    <button type="button" class="cmn--btn plan_modal" data-bs-toggle="modal" data-bs-target="#planModal"
                            data-url="{{ route('user.plan.booking', [$plan->id, slug($plan->name)]) }}">@lang('Book Now')</button>
                @else
                    <a href="{{ route('user.login') }}" class="cmn--btn">@lang('book now')</a>
                @endauth
            </div>
        </div>
    </div>
@empty
    @include('template.partials.empty', ['message' => 'Plan Not Found!'])
@endforelse


<!-- Modal -->
<div class="modal fade custom--modal" id="planModal" tabindex="-1" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">@lang('Plan Purchase Form')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">x</button>
            </div>
            <form method="post" class="planForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form--label">@lang('Pick Up Point')</label>
                        <select name="location_id" class="form-control form--control select2modal">
                            @foreach (@$locations as $location)
                                <option value="{{ $location->id }}">@lang($location->name)</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form--label">@lang('Pick Up Date & Time')</label>
                        <input type="text" name="pick_time" placeholder="@lang('Pick Up Date & Time')"
                               id="planDateAndTimePicker" autocomplete="off" data-position='top right'
                               class="form-control form--control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn cmn--btn">@lang('Submit')</button>
                </div>
            </form>
        </div>
    </div>
</div>


@push('style')
    <style>
        .datepicker {
            z-index: 9999999999999999;
        }
    </style>
@endpush


@push('script-lib')
    <script src="{{ asset('assets/admin/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/daterangepicker.min.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
@endpush
@push('script')
    <script>
        (function($) {
            "use strict"
            $(document).on('click', '.plan_modal', function() {
                var url = $(this).data('url');
                $('.planForm').attr('action', url);
            });

            $('.select2modal').select2({
                dropdownParent: '#planModal'
            });
            $('input[name="pick_time"]').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                timePicker: true,
                minYear: 1901,
                maxYear: parseInt(moment().format('YYYY'), 10),
                locale: {
                    format: 'Y-MM-DD hh:mm A'
                }
            }, function(start, end, label) {
                var years = moment().diff(start, 'years');
                $(this).val(years)
            });
        })(jQuery)
    </script>
@endpush
