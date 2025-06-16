<x-app-layout title="Dashboard">
    <div class="flex-grow-1 container-p-y container-fluid">

        <form
            method="GET"
            action=""
        >
            <div class="row text-end float-right" x-data="{
                customDate: '{{ request('interval') ?? 'last_month' }}',
            }">
                <div class="col-xl-2 col-md-3 col-sm-4 mb-4 text-end float-right">
                    <div class="align-items-right text-end float-right">
                        <select
                            name="interval"
                            class="form-select lazySelector submitOnChange"
                            data-selected="{{ request('interval') }}"
                            x-model="customDate"
                        >
                            <option value="last_month">Last Month</option>
                            <option value="this_month">This Month</option>
                            <option value="last_3_months">Last 3 months</option>
                            <option value="last_6_months">Last 6 months</option>
                            <option value="last_year">Last 12 Months</option>
                            <option value="custom">Custom</option>
                        </select>
                    </div>
                </div>
                <div x-cloak class="col-12 col-sm-6 col-lg-4" x-show="customDate === 'custom'">
                    <div
                        id="bs-datepicker-daterange"
                        class="input-group input-daterange"
                    >
                        <input
                            type="text"
                            placeholder="MM-DD-YYYY"
                            class="form-control submitOnChange"
                            value="{{ request('date_from', date('m-d-Y')) }}"
                            name="date_from"
                        />
                        <span class="input-group-text">to</span>
                        <input
                            type="text"
                            placeholder="MM-DD-YYYY"
                            class="form-control submitOnChange"
                            value="{{ request('date_to') }}"
                            name="date_to"
                        />
                    </div>
                </div>
            </div>
        </form>

        <div class="row">
            <div class="col-sm-12 col-md-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between mb-lg-n4 pb-0">
                        <div class="card-title mb-0">
                            <h5 class="mb-0">Bid win Rate (Volume)</h5>
                            <small class="text-muted">Data for {{ $optionLabel }}</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <div
                                    id="pie-won-chart"
                                    style="height: 250px;"
                                ></div>
                            </div>
                        </div>
                        <p class="text-muted align-items-center text-center">Based on {{ $projects->count() }} bids
                            submitted in {{ $optionLabel }}</p>

                    </div>
                </div>

            </div>
            <div class="col-sm-12 col-md-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between mb-lg-n4 pb-0">
                        <div class="card-title mb-0">
                            <h5 class="mb-0">Bid win Rate (Value)</h5>
                            <small class="text-muted">Data for {{ $optionLabel }}</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <div
                                    id="pie-won-sum-chart"
                                    style="height: 250px;"
                                ></div>
                            </div>
                        </div>
                        <p class="text-muted align-items-center text-center">Based on
                            {{ money($projects->sum('final_estimate')) }} of bids submitted in {{ $optionLabel }}.</p>
                    </div>
                </div>

            </div>
            <div class="col-sm-12 col-md-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between mb-lg-n4 pb-0">
                        <div class="card-title mb-0">
                            <h5 class="mb-0">Total Bids Submitted</h5>
                            <small class="text-muted">Data for {{ $optionLabel }}</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <h2 class="mt-4">{{ $projects->count() }}</h2>
                    </div>
                </div>

            </div>
            <div class="col-sm-12 col-md-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between mb-lg-n4 pb-0">
                        <div class="card-title mb-0">
                            <h5 class="mb-0">Total Value Submitted</h5>
                            <small class="text-muted">Data for {{ $optionLabel }}</small>

                        </div>
                    </div>
                    <div class="card-body">
                        <h2 class="mt-4">{{ money($projects->sum('final_estimate')) }}</h2>

                    </div>
                </div>

            </div>
            <div class="col-sm-12 col-md-12 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between mb-lg-n4 pb-0">
                        <div class="card-title mb-0">
                            <h5 class="mb-0">Bids Submitted Weekly</h5>
                            <small class="text-muted">Data for {{ $optionLabel }}</small>

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <div
                                    id="volume-chart"
                                    style="height: 450px;"
                                ></div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            {{-- <div class="col-sm-12 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between mb-lg-n4 pb-0">
                        <div class="card-title mb-0">
                            <h5 class="mb-0">Value ($) of Bids Submitted Weekly</h5>
                            <small class="text-muted">Data for {{ $optionLabel }}</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <div
                                    id="value-chart"
                                    style="height: 350px;"
                                ></div>
                            </div>
                        </div>

                    </div>
                </div>

            </div> --}}
            <div class="col-sm-12 col-md-6 mb-4">
                <div class="card">
                    <div class="card-header ">
                        <div class="card-title mb-0">
                            <h5 class="mb-0">Key Account Focus</h5>
                            <small class="text-muted">Data for {{ $optionLabel }}</small>

                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th colspan="2">Volume</th>
                                    <th colspan="2">Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Number</td>
                                    <td>{{ $keyProjects->count() }}</td>
                                    <td>Value</td>
                                    <td>{{ money($keyProjects->sum('final_estimate')) }}</td>
                                </tr>
                                <tr>
                                    <td>% of total</td>
                                    <td>{{ number_format(($keyProjects->count() / $projects->count()) * 100, 2) }}%
                                    </td>
                                    <td>% of total</td>
                                    <td>{{ number_format(($keyProjects->sum('final_estimate') / $projects->sum('final_estimate')) * 100, 2) }}%
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <p class="text-muted">
                            Data for {{ $optionLabel }}
                        </p>
                    </div>
                </div>
            </div>
            {{-- <div class="col-sm-12 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between mb-lg-n4 pb-0">
                        <div class="card-title mb-0">
                            <h5 class="mb-0">Bid win Rate (Value)</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <div id="weeklyEarningReports"></div>
                            </div>
                        </div>
                        <div class="mt-4 rounded border p-3">
                            <div class="row gap-sm-0 gap-2">
                                <div class="col-12 col-sm-3">
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0">Win Rate</h6>
                                    </div>
                                    <h4 class="my-2 pt-1">{{ $won->count() }} / {{ $projects->count() }} -
                                        {{ $projects->count() ? number_format(($won->count() * 100) / $projects->count(), 2) : '0' }}%
                                    </h4>
                                </div>

                                <div class="col-12 col-sm-3">
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0">Bid Amount</h6>
                                    </div>
                                    <h4 class="my-2 pt-1">{{ money($projects->sum('final_estimate')) }}</h4>
                                </div>

                                <div class="col-12 col-sm-3">
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0">Won Amount</h6>
                                    </div>
                                    <h4 class="my-2 pt-1">{{ money($won->sum('final_estimate')) }}</h4>
                                </div>

                                <div class="col-12 col-sm-3">
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0">Won Amount Ratio</h6>
                                    </div>
                                    <h4 class="my-2 pt-1">
                                        {{ $projects->sum('final_estimate') > 0 ? number_format(($won->sum('final_estimate') * 100) / $projects->sum('final_estimate'), 2) : '0' }}%

                                    </h4>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div> --}}
        </div>
    </div>

    <x-slot:styles>
        @if (isset($jsData))
            {!! jsVar($jsData) !!}
        @endif
    </x-slot:styles>

    <x-slot:scripts>
        <script>
            const weeklyEarningReportsEl = document.querySelector('#weeklyEarningReports');
            var options = {
                series: [{
                    name: 'Final Estimate',
                    type: 'line',
                    data: chartData.total
                }],
                chart: {
                    height: 350,
                    type: 'line',
                },
                stroke: {
                    width: [0, 4]
                },
                title: {
                    text: ''
                },
                dataLabels: {
                    enabled: true,
                    enabledOnSeries: [1]
                },
                labels: chartData.labels,
                // xaxis: {
                //   type: 'datetime'
                // },
                yaxis: [{
                    title: {
                        text: 'Projects',
                    },

                }, {
                    opposite: true,
                    title: {
                        text: 'Final Estimate'
                    }
                }]
            };
            if (typeof weeklyEarningReportsEl !== undefined && weeklyEarningReportsEl !== null) {
                //const weeklyEarningReports = new ApexCharts(weeklyEarningReportsEl, options);
                //weeklyEarningReports.render();
            }

            /*won chart */
            var options = {
                chart: {
                    height: 250,
                    type: 'radialBar'
                },
                plotOptions: {
                    radialBar: {
                        hollow: {
                            size: '40%',
                        }
                    },
                },
                labels: ['Volume'],
                series: [totalCount > 0 ? Number(wonCount * 100 / totalCount).toFixed(2) : 0]
            }

            var chart = new ApexCharts(document.querySelector("#pie-won-chart"), options);
            chart.render();

            /*won sumchart */
            var options = {
                chart: {
                    height: 250,
                    type: 'radialBar'
                },
                plotOptions: {
                    radialBar: {
                        hollow: {
                            size: '40%',
                        }
                    },
                },
                labels: ['Value'],
                series: [totalSum > 0 ? Number(wonSum * 100 / totalSum).toFixed(2) : 0]
            }

            var chart = new ApexCharts(document.querySelector("#pie-won-sum-chart"), options);
            chart.render();

            //volume chart
            var options = {
                series: [{
                    name: "Volume",
                    type: 'line',
                    data: chartData.count
                }, {
                    name: "Value",
                    type: 'column',
                    data: chartData.total
                }],
                plotOptions: {
                    pie: {
                        customScale: 0.3
                    }
                },
                chart: {
                    height: 350,
                    type: 'line',
                    zoom: {
                        enabled: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'straight'
                },

                grid: {
                    row: {
                        colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                        opacity: 0.5
                    },
                },
                xaxis: {
                    categories: chartData.labels
                },
                yaxis: [{
          title: {
            text: 'Volume',
          },
        
        }, {
          opposite: true,
          title: {
            text: 'Value'
          }
        }]
            };

            var chart = new ApexCharts(document.querySelector("#volume-chart"), options);
            chart.render();
            //value chart
            var options = {
          series: [{
            data: [{
              x: 'Team A',
              y: [1000, 5000]
            }, {
              x: 'Team B',
              y: [4000, 6000]
            }, {
              x: 'Team C',
              y: [5000, 8000]
            }, {
              x: 'Team D',
              y: [3000, 11000]
            }]
        }, {
            data: [{
              x: 'Team A',
              y: [2, 6]
            }, {
              x: 'Team B',
              y: [1, 3]
            }, {
              x: 'Team C',
              y: [7, 8]
            }, {
              x: 'Team D',
              y: [5, 9]
            }]
        }],
          chart: {
          type: 'rangeBar',
          height: 350
        },
        plotOptions: {
          bar: {
            horizontal: false
          }
        },
        dataLabels: {
          enabled: true
        }
        };

            //var chart = new ApexCharts(document.querySelector("#value-chart"), options);
            // chart.render(); --}}
        </script>

    </x-slot:scripts>
</x-app-layout>
