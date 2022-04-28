@extends('template.app')
@section('title', ucwords(str_replace([':', '_', '-', '*'], ' ', $title)))

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- page statustic chart start -->
            <div class="col-xl-3 col-md-6">
                <div class="card card-red text-white">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h4 class="mb-0">{{ __('2,563') }}</h4>
                                <p class="mb-0">{{ __('Aduan') }}</p>
                            </div>
                            <div class="col-4 text-right">
                                <i class="fas fa-cube f-30"></i>
                            </div>
                        </div>
                        <div id="Widget-line-chart1" class="chart-line chart-shadow"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-blue text-white">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h4 class="mb-0">{{ __('3,612') }}</h4>
                                <p class="mb-0">{{ __('Pekerjaan') }}</p>
                            </div>
                            <div class="col-4 text-right">
                                <i class="ik ik-shopping-cart f-30"></i>
                            </div>
                        </div>
                        <div id="Widget-line-chart2" class="chart-line chart-shadow"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-green text-white">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h4 class="mb-0">{{ __('865') }}</h4>
                                <p class="mb-0">{{ __('Rekanan') }}</p>
                            </div>
                            <div class="col-4 text-right">
                                <i class="ik ik-user f-30"></i>
                            </div>
                        </div>
                        <div id="Widget-line-chart3" class="chart-line chart-shadow"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-yellow text-white">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h4 class="mb-0">{{ __('35,500') }}</h4>
                                <p class="mb-0">{{ __('Tagihan') }}</p>
                            </div>
                            <div class="col-4 text-right">
                                <i class="ik f-30">৳</i>
                            </div>
                        </div>
                        <div id="Widget-line-chart4" class="chart-line chart-shadow"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-xl-12">
                <div class="card sale-card">
                    <div class="card-header">
                        <h3>Daftar Aduan Tahun ini</h3>
                    </div>
                    <div class="card-block text-center">
                        <div id="listAduan" class="chart-shadow"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('chart')
@endpush
@push('style')
    <style>
        @media (max-width: 500px) {
            #perda {
                height: 52px;
            }
        }

        .modal {
            text-align: center;
        }

        @media screen and (min-width: 768px) {
            .modal:before {
                display: inline-block;
                vertical-align: middle;
                content: " ";
                position: absolute;
                height: 100%;

            }
        }

        .modal-dialog {
            display: inline-block;
            text-align: left;
            vertical-align: middle;
            top: 50%;
        }

    </style>
@endpush
@push('script')
    <script src="{{ asset('plugins/owl.carousel/dist/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('plugins/chartist/dist/chartist.min.js') }}"></script>
    <script src="{{ asset('plugins/flot-charts/jquery.flot.js') }}"></script>
    <script src="{{ asset('plugins/flot-charts/jquery.flot.categories.js') }}"></script>
    <script src="{{ asset('plugins/flot-charts/curvedLines.js') }}"></script>
    <script src="{{ asset('plugins/flot-charts/jquery.flot.tooltip.min.js') }}"></script>

    <script src="{{ asset('plugins/amcharts/amcharts.js') }}"></script>
    <script src="{{ asset('plugins/amcharts/serial.js') }}"></script>
    <script src="{{ asset('plugins/amcharts/themes/light.js') }}"></script>


    <script src="{{ asset('js/widget-statistic.js') }}"></script>
    <script src="{{ asset('js/widget-data.js') }}"></script>
    <script src="{{ asset('js/dashboard-charts.js') }}"></script>
    <script>
        var chart = AmCharts.makeChart("listAduan", {
            "type": "serial",
            "theme": "light",
            "dataDateFormat": "YYYY-MM-DD",
            "precision": 0,
            "valueAxes": [{
                "id": "v1",
                "fontSize": 0,
                "axisAlpha": 0,
                "lineAlpha": 0,
                "gridAlpha": 0,
                "position": "left",
                "autoGridCount": false,

            }],
            "graphs": [{
                "id": "g3",
                "valueAxis": "v1",
                "lineColor": "#2ed8b6",
                "fillColors": "#2ed8b6",
                "fillAlphas": 0.3,
                "type": "column",
                "title": "Aduan",
                "valueField": "sales2",
                "columnWidth": 0.5,
                "legendValueText": "[[value]]",
                "balloonText": "[[title]]<br /><b style='font-size: 130%'>[[value]]</b>"
            }, {
                "id": "g4",
                "valueAxis": "v1",
                "lineColor": "#2ed8b6",
                "fillColors": "#2ed8b6",
                "fillAlphas": 1,
                "type": "column",
                "title": "Pekerjaan",
                "valueField": "sales1",
                "columnWidth": 0.5,
                "legendValueText": "[[value]]",
                "balloonText": "[[title]]<br /><b style='font-size: 130%'>[[value]]</b>"
            }],
            "chartCursor": {
                "pan": true,
                "valueLineEnabled": true,
                "valueLineBalloonEnabled": true,
                "cursorAlpha": 0,
                "valueLineAlpha": 0.2
            },
            "categoryField": "date",
            "categoryAxis": {
                "parseDates": true,
                "axisAlpha": 0,
                "lineAlpha": 0,
                "gridAlpha": 0,
                "minorGridEnabled": true,
            },
            "balloon": {
                "borderThickness": 1,
                "shadowAlpha": 0
            },
            "export": {
                "enabled": true
            },
            "dataProvider": [{
                "date": "2020-01-01",
                "sales1": 5,
                "sales2": 8
            }, {
                "date": "2020-01-02",
                "sales1": 4,
                "sales2": 6
            }, {
                "date": "2020-01-18",
                "sales1": 5,
                "sales2": 2
            }, {
                "date": "2020-01-19",
                "sales1": 8,
                "sales2": 9
            }, {
                "date": "2020-01-20",
                "sales1": 9,
                "sales2": 6
            }]
        });
    </script>
@endpush
