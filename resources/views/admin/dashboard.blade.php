{{--@extends('admin.layouts.app')--}}

{{--@section('panel')--}}

{{--    <div class="row gy-4">--}}
{{--        <div class="col-xxl-3 col-sm-6">--}}
{{--            <x-widget--}}
{{--                style="6"--}}
{{--                link="{{route('admin.users.all')}}"--}}
{{--                icon="las la-users"--}}
{{--                title="Total Users"--}}
{{--                value="{{$widget['total_users']}}"--}}
{{--                bg="primary"--}}
{{--            />--}}
{{--        </div><!-- dashboard-w1 end -->--}}
{{--        <div class="col-xxl-3 col-sm-6">--}}
{{--            <x-widget--}}
{{--                style="6"--}}
{{--                link="{{route('admin.users.active')}}"--}}
{{--                icon="las la-user-check"--}}
{{--                title="Active Users"--}}
{{--                value="{{$widget['verified_users']}}"--}}
{{--                bg="success"--}}
{{--            />--}}
{{--        </div><!-- dashboard-w1 end -->--}}
{{--    </div><!-- row end-->--}}

{{--    @if(auth()->guard('admin')->user()->isAdmin())--}}
{{--    <div class="row mt-2 gy-4">--}}
{{--        <div class="col-xxl-6">--}}
{{--            <div class="card box-shadow3 h-100">--}}
{{--                <div class="card-body">--}}
{{--                    <h5 class="card-title">@lang('Payment History')</h5>--}}
{{--                    <div class="widget-card-wrapper">--}}

{{--                        <div class="widget-card bg--success">--}}
{{--                            <a href="{{ route('admin.deposit.list') }}" class="widget-card-link"></a>--}}
{{--                            <div class="widget-card-left">--}}
{{--                                <div class="widget-card-icon">--}}
{{--                                    <i class="fas fa-hand-holding-usd"></i>--}}
{{--                                </div>--}}
{{--                                <div class="widget-card-content">--}}
{{--                                    <h6 class="widget-card-amount">{{ showAmount($deposit['total_deposit_amount']) }}</h6>--}}
{{--                                    <p class="widget-card-title">@lang('Total Deposited')</p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <span class="widget-card-arrow">--}}
{{--                                <i class="las la-angle-right"></i>--}}
{{--                            </span>--}}
{{--                        </div>--}}

{{--                        <div class="widget-card bg--warning">--}}
{{--                            <a href="{{ route('admin.deposit.pending') }}" class="widget-card-link"></a>--}}
{{--                            <div class="widget-card-left">--}}
{{--                                <div class="widget-card-icon">--}}
{{--                                    <i class="fas fa-spinner"></i>--}}
{{--                                </div>--}}
{{--                                <div class="widget-card-content">--}}
{{--                                    <h6 class="widget-card-amount">{{ $deposit['total_deposit_pending'] }}</h6>--}}
{{--                                    <p class="widget-card-title">@lang('Pending Deposits')</p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <span class="widget-card-arrow">--}}
{{--                                <i class="las la-angle-right"></i>--}}
{{--                            </span>--}}
{{--                        </div>--}}

{{--                        <div class="widget-card bg--danger">--}}
{{--                            <a href="{{ route('admin.deposit.rejected') }}" class="widget-card-link"></a>--}}
{{--                            <div class="widget-card-left">--}}
{{--                                <div class="widget-card-icon">--}}
{{--                                    <i class="fas fa-ban"></i>--}}
{{--                                </div>--}}
{{--                                <div class="widget-card-content">--}}
{{--                                    <h6 class="widget-card-amount">{{ $deposit['total_deposit_rejected'] }}</h6>--}}
{{--                                    <p class="widget-card-title">@lang('Rejected Deposits')</p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <span class="widget-card-arrow">--}}
{{--                                <i class="las la-angle-right"></i>--}}
{{--                            </span>--}}
{{--                        </div>--}}

{{--                        <div class="widget-card bg--primary">--}}
{{--                            <a href="{{ route('admin.deposit.list') }}" class="widget-card-link"></a>--}}
{{--                            <div class="widget-card-left">--}}
{{--                                <div class="widget-card-icon">--}}
{{--                                    <i class="fas fa-percentage"></i>--}}
{{--                                </div>--}}
{{--                                <div class="widget-card-content">--}}
{{--                                    <h6 class="widget-card-amount">{{ showAmount($deposit['total_deposit_charge']) }}</h6>--}}
{{--                                    <p class="widget-card-title">@lang('Deposited Charge')</p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <span class="widget-card-arrow">--}}
{{--                                <i class="las la-angle-right"></i>--}}
{{--                            </span>--}}
{{--                        </div>--}}

{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="col-xxl-6">--}}
{{--            <div class="card box-shadow3 h-100">--}}
{{--                <div class="card-body">--}}
{{--                    <h5 class="card-title">@lang('Vehicale History')</h5>--}}
{{--                    <div class="widget-card-wrapper">--}}
{{--                        <div class="widget-card bg--success">--}}
{{--                            <a href="{{ route('admin.vehicles.booking.log') }}" class="widget-card-link"></a>--}}
{{--                            <div class="widget-card-left">--}}
{{--                                <div class="widget-card-icon">--}}
{{--                                    <i class="las la-car"></i>--}}
{{--                                </div>--}}
{{--                                <div class="widget-card-content">--}}
{{--                                    <h6 class="widget-card-amount">{{ $widget['total_vehicle_booking'] }}</h6>--}}
{{--                                    <p class="widget-card-title">@lang('Total Vehicle Booking')</p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <span class="widget-card-arrow">--}}
{{--                                <i class="las la-angle-right"></i>--}}
{{--                            </span>--}}
{{--                        </div>--}}

{{--                        <div class="widget-card bg--warning">--}}
{{--                            <a href="{{ route('admin.vehicles.running.booking.log') }}" class="widget-card-link"></a>--}}
{{--                            <div class="widget-card-left">--}}
{{--                                <div class="widget-card-icon">--}}
{{--                                    <i class="fas fa-spinner"></i>--}}
{{--                                </div>--}}
{{--                                <div class="widget-card-content">--}}
{{--                                    <h6 class="widget-card-amount">{{ $widget['running_vehicle_booking'] }}</h6>--}}
{{--                                    <p class="widget-card-title">@lang('Running Vehicle Booking')</p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <span class="widget-card-arrow">--}}
{{--                                <i class="las la-angle-right"></i>--}}
{{--                            </span>--}}
{{--                        </div>--}}

{{--                        <div class="widget-card bg--danger">--}}
{{--                            <a href="{{ route('admin.vehicles.upcoming.booking.log') }}" class="widget-card-link"></a>--}}
{{--                            <div class="widget-card-left">--}}
{{--                                <div class="widget-card-icon">--}}
{{--                                    <i class="las la-hourglass-half"></i>--}}
{{--                                </div>--}}
{{--                                <div class="widget-card-content">--}}
{{--                                    <h6 class="widget-card-amount">{{ $widget['upcoming_vehicle_booking'] }}</h6>--}}
{{--                                    <p class="widget-card-title">@lang('Upcoming Vehicle Booking')</p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <span class="widget-card-arrow">--}}
{{--                                <i class="las la-angle-right"></i>--}}
{{--                            </span>--}}
{{--                        </div>--}}

{{--                        <div class="widget-card bg--primary">--}}
{{--                            <a href="{{ route('admin.vehicles.completed.booking.log') }}" class="widget-card-link"></a>--}}
{{--                            <div class="widget-card-left">--}}
{{--                                <div class="widget-card-icon">--}}
{{--                                    <i class="las la-check-circle"></i>--}}
{{--                                </div>--}}
{{--                                <div class="widget-card-content">--}}
{{--                                    <h6 class="widget-card-amount">{{ $widget['completed_vehicle_booking'] }}</h6>--}}
{{--                                    <p class="widget-card-title">@lang('Completed Vehicle Booking')</p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <span class="widget-card-arrow">--}}
{{--                                <i class="las la-angle-right"></i>--}}
{{--                            </span>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}


{{--    <div class="row mb-none-30 mt-30">--}}
{{--        <div class="col-xl-6 mb-30">--}}
{{--            <div class="card">--}}
{{--              <div class="card-body">--}}
{{--                <div class="d-flex flex-wrap justify-content-between">--}}
{{--                    <h5 class="card-title">@lang('Payment Report')</h5>--}}

{{--                    <div id="dwDatePicker" class="border p-1 cursor-pointer rounded">--}}
{{--                        <i class="la la-calendar"></i>&nbsp;--}}
{{--                        <span></span> <i class="la la-caret-down"></i>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div id="dwChartArea"> </div>--}}
{{--              </div>--}}
{{--            </div>--}}
{{--          </div>--}}
{{--        <div class="col-xl-6 mb-30">--}}
{{--            <div class="card">--}}
{{--              <div class="card-body">--}}
{{--                <div class="d-flex flex-wrap justify-content-between">--}}
{{--                    <h5 class="card-title">@lang('Transactions Report')</h5>--}}

{{--                    <div id="trxDatePicker" class="border p-1 cursor-pointer rounded">--}}
{{--                        <i class="la la-calendar"></i>&nbsp;--}}
{{--                        <span></span> <i class="la la-caret-down"></i>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div id="transactionChartArea"></div>--}}
{{--              </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    <div class="row mb-none-30 mt-5">--}}
{{--        <div class="col-xl-4 col-lg-6 mb-30">--}}
{{--            <div class="card overflow-hidden">--}}
{{--                <div class="card-body">--}}
{{--                    <h5 class="card-title">@lang('Login By Browser') (@lang('Last 30 days'))</h5>--}}
{{--                    <canvas id="userBrowserChart"></canvas>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="col-xl-4 col-lg-6 mb-30">--}}
{{--            <div class="card">--}}
{{--                <div class="card-body">--}}
{{--                    <h5 class="card-title">@lang('Login By OS') (@lang('Last 30 days'))</h5>--}}
{{--                    <canvas id="userOsChart"></canvas>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="col-xl-4 col-lg-6 mb-30">--}}
{{--            <div class="card">--}}
{{--                <div class="card-body">--}}
{{--                    <h5 class="card-title">@lang('Login By Country') (@lang('Last 30 days'))</h5>--}}
{{--                    <canvas id="userCountryChart"></canvas>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    @endif--}}

{{--@endsection--}}



{{--@push('script-lib')--}}
{{--    <script src="{{ asset('assets/admin/js/vendor/apexcharts.min.js') }}"></script>--}}
{{--    <script src="{{ asset('assets/admin/js/vendor/chart.js.2.8.0.js') }}"></script>--}}
{{--    <script src="{{ asset('assets/admin/js/moment.min.js') }}"></script>--}}
{{--    <script src="{{ asset('assets/admin/js/daterangepicker.min.js') }}"></script>--}}
{{--    <script src="{{ asset('assets/admin/js/charts.js') }}"></script>--}}
{{--@endpush--}}

{{--@push('style-lib')--}}
{{--    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">--}}
{{--@endpush--}}

{{--@push('script')--}}
{{--    <script>--}}
{{--        "use strict";--}}

{{--        const start = moment().subtract(14, 'days');--}}
{{--        const end = moment();--}}

{{--        const dateRangeOptions = {--}}
{{--            startDate: start,--}}
{{--            endDate: end,--}}
{{--            ranges: {--}}
{{--                'Today': [moment(), moment()],--}}
{{--                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],--}}
{{--                'Last 7 Days': [moment().subtract(6, 'days'), moment()],--}}
{{--                'Last 15 Days': [moment().subtract(14, 'days'), moment()],--}}
{{--                'Last 30 Days': [moment().subtract(30, 'days'), moment()],--}}
{{--                'This Month': [moment().startOf('month'), moment().endOf('month')],--}}
{{--                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],--}}
{{--                'Last 6 Months': [moment().subtract(6, 'months').startOf('month'), moment().endOf('month')],--}}
{{--                'This Year': [moment().startOf('year'), moment().endOf('year')],--}}
{{--            },--}}
{{--            maxDate: moment()--}}
{{--        }--}}

{{--        const changeDatePickerText = (element, startDate, endDate) => {--}}
{{--            $(element).html(startDate.format('MMMM D, YYYY') + ' - ' + endDate.format('MMMM D, YYYY'));--}}
{{--        }--}}

{{--        let dwChart = barChart(--}}
{{--            document.querySelector("#dwChartArea"),--}}
{{--            @json(__(gs('cur_text'))),--}}
{{--            [{--}}
{{--                    name: 'Deposited',--}}
{{--                    data: []--}}
{{--                }--}}
{{--            ],--}}
{{--            [],--}}
{{--        );--}}

{{--        let trxChart = lineChart(--}}
{{--            document.querySelector("#transactionChartArea"),--}}
{{--            [{--}}
{{--                    name: "Plus Transactions",--}}
{{--                    data: []--}}
{{--                },--}}
{{--                {--}}
{{--                    name: "Minus Transactions",--}}
{{--                    data: []--}}
{{--                }--}}
{{--            ],--}}
{{--            []--}}
{{--        );--}}


{{--        const depositWithdrawChart = (startDate, endDate) => {--}}

{{--            const data = {--}}
{{--                start_date: startDate.format('YYYY-MM-DD'),--}}
{{--                end_date: endDate.format('YYYY-MM-DD')--}}
{{--            }--}}

{{--            const url = @json(route('admin.chart.deposit.withdraw'));--}}

{{--            $.get(url, data,--}}
{{--                function(data, status) {--}}
{{--                    if (status == 'success') {--}}
{{--                        dwChart.updateSeries(data.data);--}}
{{--                        dwChart.updateOptions({--}}
{{--                            xaxis: {--}}
{{--                                categories: data.created_on,--}}
{{--                            }--}}
{{--                        });--}}
{{--                    }--}}
{{--                }--}}
{{--            );--}}
{{--        }--}}

{{--        const transactionChart = (startDate, endDate) => {--}}

{{--            const data = {--}}
{{--                start_date: startDate.format('YYYY-MM-DD'),--}}
{{--                end_date: endDate.format('YYYY-MM-DD')--}}
{{--            }--}}

{{--            const url = @json(route('admin.chart.transaction'));--}}


{{--            $.get(url, data,--}}
{{--                function(data, status) {--}}
{{--                    if (status == 'success') {--}}


{{--                        trxChart.updateSeries(data.data);--}}
{{--                        trxChart.updateOptions({--}}
{{--                            xaxis: {--}}
{{--                                categories: data.created_on,--}}
{{--                            }--}}
{{--                        });--}}
{{--                    }--}}
{{--                }--}}
{{--            );--}}
{{--        }--}}



{{--        $('#dwDatePicker').daterangepicker(dateRangeOptions, (start, end) => changeDatePickerText('#dwDatePicker span', start, end));--}}
{{--        $('#trxDatePicker').daterangepicker(dateRangeOptions, (start, end) => changeDatePickerText('#trxDatePicker span', start, end));--}}

{{--        changeDatePickerText('#dwDatePicker span', start, end);--}}
{{--        changeDatePickerText('#trxDatePicker span', start, end);--}}

{{--        depositWithdrawChart(start, end);--}}
{{--        transactionChart(start, end);--}}

{{--        $('#dwDatePicker').on('apply.daterangepicker', (event, picker) => depositWithdrawChart(picker.startDate, picker.endDate));--}}
{{--        $('#trxDatePicker').on('apply.daterangepicker', (event, picker) => transactionChart(picker.startDate, picker.endDate));--}}

{{--        piChart(--}}
{{--            document.getElementById('userBrowserChart'),--}}
{{--            @json(@$chart['user_browser_counter']->keys()),--}}
{{--            @json(@$chart['user_browser_counter']->flatten())--}}
{{--        );--}}

{{--        piChart(--}}
{{--            document.getElementById('userOsChart'),--}}
{{--            @json(@$chart['user_os_counter']->keys()),--}}
{{--            @json(@$chart['user_os_counter']->flatten())--}}
{{--        );--}}

{{--        piChart(--}}
{{--            document.getElementById('userCountryChart'),--}}
{{--            @json(@$chart['user_country_counter']->keys()),--}}
{{--            @json(@$chart['user_country_counter']->flatten())--}}
{{--        );--}}
{{--    </script>--}}
{{--@endpush--}}
{{--@push('style')--}}
{{--    <style>--}}
{{--        .apexcharts-menu {--}}
{{--            min-width: 120px !important;--}}
{{--        }--}}
{{--    </style>--}}
{{--@endpush--}}

@extends('admin.layouts.app')

@section('panel')
    <div class="row gy-4">

        {{-- Dossiers --}}
        <div class="col-xxl-3 col-sm-6">
            <x-widget style="6" link="{{ route('admin.dossiers.index') }}" icon="las la-folder-open"
                      title="Total Dossiers" value="{{ $widget['dossiers_total'] }}" bg="primary"/>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget style="6" link="{{ route('admin.dossiers.index', ['status'=>'PENDING']) }}" icon="las la-hourglass-half"
                      title="En attente" value="{{ $widget['dossiers_pending'] }}" bg="warning"/>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget style="6" link="{{ route('admin.dossiers.index', ['status'=>'APPROVED']) }}" icon="las la-check-circle"
                      title="Approuvés" value="{{ $widget['dossiers_approved'] }}" bg="success"/>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget style="6" link="{{ route('admin.dossiers.index', ['status'=>'REJECTED']) }}" icon="las la-ban"
                      title="Rejetés" value="{{ $widget['dossiers_rejected'] }}" bg="danger"/>
        </div>

        {{-- Finance / fournisseurs --}}
        <div class="col-xxl-3 col-sm-6">
            <x-widget style="6" link="{{ route('admin.supplier.index') }}" icon="las la-industry"
                      title="Fournisseurs" value="{{ $widget['fournisseurs_total'] }}" bg="info"/>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget style="6" link="{{ route('admin.dossiers.index') }}" icon="las la-file-invoice-dollar"
                      title="Factures (avec réf.)" value="{{ $widget['factures_total'] }}" bg="dark"/>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget style="6" link="{{ route('admin.dossiers.index') }}" icon="las la-coins"
                      title="TTC Total" value="{{ showAmount($widget['ttc_sum']) }}" bg="primary"/>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget style="6" link="{{ route('admin.dossiers.index') }}" icon="las la-calendar-check"
                      title="TTC Ce mois" value="{{ showAmount($widget['ttc_this_month']) }}" bg="success"/>
        </div>
    </div>

    <div class="row mt-2 gy-4">
        <div class="col-xxl-6">
            <div class="card box-shadow3 h-100">
                <div class="card-body">
                    <h5 class="card-title">@lang('Dossiers (créés / approuvés / rejetés)')</h5>
                    <div id="dossierDatePicker" class="border p-1 cursor-pointer rounded mb-3">
                        <i class="la la-calendar"></i>&nbsp;<span></span> <i class="la la-caret-down"></i>
                    </div>
                    <div id="dossiersChartArea"></div>
                </div>
            </div>
        </div>

        <div class="col-xxl-6">
            <div class="card box-shadow3 h-100">
                <div class="card-body">
                    <h5 class="card-title">@lang('Montants (HT / TTC)')</h5>
                    <div id="amountDatePicker" class="border p-1 cursor-pointer rounded mb-3">
                        <i class="la la-calendar"></i>&nbsp;<span></span> <i class="la la-caret-down"></i>
                    </div>
                    <div id="amountChartArea"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-none-30 mt-4">
        <div class="col-xl-6 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">@lang('Top Fournisseurs (TTC)')</h5>
                    <div id="topFournisseursArea"></div>
                    <small class="text-muted d-block mt-2">@lang('Période par défaut : 30 jours')</small>
                </div>
            </div>
        </div>
        <div class="col-xl-6 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">@lang('Rejets sur 30 jours')</h5>
                    <div class="fs-1 fw-bold">{{ $rejections_30d }}</div>
                    <div class="text-muted">@lang('Événements REJECT enregistrés')</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/admin/js/vendor/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/daterangepicker.min.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
@endpush

@push('script')
    <script>
        "use strict";

        const start = moment().subtract(14, 'days');
        const end   = moment();

        const drpOpts = {
            startDate: start, endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Last 7 Days': [moment().subtract(6,'days'), moment()],
                'Last 30 Days': [moment().subtract(30,'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'This Year': [moment().startOf('year'), moment().endOf('year')],
            }, maxDate: moment()
        };
        const setText = (el,s,e)=> $(el).find('span').html(s.format('MMM D, YYYY')+' - '+e.format('MMM D, YYYY'));

        $('#dossierDatePicker').daterangepicker(drpOpts,(s,e)=>setText('#dossierDatePicker',s,e));
        $('#amountDatePicker').daterangepicker(drpOpts,(s,e)=>setText('#amountDatePicker',s,e));
        setText('#dossierDatePicker', start, end);
        setText('#amountDatePicker', start, end);

        // Charts
        let dossiersChart = new ApexCharts(document.querySelector("#dossiersChartArea"), {
            chart:{ type:'line', height:320, toolbar:{show:false}},
            dataLabels:{enabled:false},
            stroke:{width:3, curve:'smooth'},
            series: [],
            xaxis:{ categories: [] },
        });
        dossiersChart.render();

        let amountChart = new ApexCharts(document.querySelector("#amountChartArea"), {
            chart:{ type:'bar', height:320, stacked:false, toolbar:{show:false}},
            plotOptions:{ bar:{ columnWidth:'50%'}},
            dataLabels:{enabled:false},
            series: [],
            xaxis:{ categories: [] },
        });
        amountChart.render();

        let topFournisseurs = new ApexCharts(document.querySelector("#topFournisseursArea"), {
            chart:{ type:'donut', height:320, toolbar:{show:false}},
            series: [], labels: []
        });
        topFournisseurs.render();

        // Loaders
        function loadDossiers(s, e){
            $.get(@json(route('admin.chart.dossiers')), {start_date:s.format('YYYY-MM-DD'), end_date:e.format('YYYY-MM-DD')}, (res)=>{
                dossiersChart.updateSeries(res.data);
                dossiersChart.updateOptions({ xaxis:{ categories: res.created_on }});
            });
        }
        function loadAmounts(s, e){
            $.get(@json(route('admin.chart.amounts')), {start_date:s.format('YYYY-MM-DD'), end_date:e.format('YYYY-MM-DD')}, (res)=>{
                amountChart.updateSeries(res.data);
                amountChart.updateOptions({ xaxis:{ categories: res.created_on }});
            });
        }
        function loadTopFournisseurs(s, e){
            $.get(@json(route('admin.chart.top_fournisseurs')), {start_date:s.format('YYYY-MM-DD'), end_date:e.format('YYYY-MM-DD')}, (res)=>{
                topFournisseurs.updateOptions({ labels: res.labels });
                topFournisseurs.updateSeries(res.data);
            });
        }

        // initial
        loadDossiers(start, end);
        loadAmounts(start, end);
        loadTopFournisseurs(moment().subtract(30,'days'), moment());

        // react to range changes
        $('#dossierDatePicker').on('apply.daterangepicker', (ev,p)=> loadDossiers(p.startDate, p.endDate));
        $('#amountDatePicker').on('apply.daterangepicker', (ev,p)=> { loadAmounts(p.startDate, p.endDate); loadTopFournisseurs(p.startDate, p.endDate); });

    </script>
@endpush
