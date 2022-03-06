<template>
    <div class="main-layout-wrapper">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent m-0">
                <li class="breadcrumb-item">
                    <span>{{trans('lang.dashboard')}}</span>
                </li>
            </ol>
        </nav>
        <div class="row">
            <div class="col-12 col-sm-6 col-md-3 pr-md-0">
                <div class="card dashboard-card-top">
                    <div class="card-content">
                        <div class="dashboard-card-parent">
                            <div class="dashboard-card-child-1">
                                <div class="purple dashboard-top-row-parent">
                                    <i class="la la-clipboard-list la-2x dashboard-top-row-child"></i>
                                </div>
                            </div>
                            <div class="dashboard-card-child-2">
                                <h5>{{totalAllBooking}}</h5>
                                <p>
                                    {{ trans('lang.total_booking_for_next_30_days') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3 pr-md-0">
                <div class="card dashboard-card-top">
                    <div class="card-content">
                        <div class="dashboard-card-parent">
                            <div class="dashboard-card-child-1">
                                <div class="cyan dashboard-top-row-parent">
                                    <i class="la la-shopping-bag la-2x dashboard-top-row-child"></i>
                                </div>
                            </div>
                            <div class="dashboard-card-child-2">
                                <h5>{{totalBooking}}</h5>
                                <p>
                                    {{ trans('lang.confirmed_booking_for_next_30_days') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3 pr-md-0">
                <div class="card dashboard-card-top">
                    <div class="card-content">
                        <div class="dashboard-card-parent">
                            <div class="dashboard-card-child-1">
                                <div class="orange dashboard-top-row-parent">
                                    <i class="la la-star la-2x dashboard-top-row-child"></i>
                                </div>
                            </div>
                            <div class="dashboard-card-child-2">
                                <h5>{{todaysBooking}}</h5>
                                <p>
                                    {{ trans('lang.today_total_booking') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="card dashboard-card-top">
                    <div class="card-content">
                        <div class="dashboard-card-parent">
                            <div class="dashboard-card-child-1">
                                <div class="red dashboard-top-row-parent">
                                    <i class="la la-clipboard la-2x dashboard-top-row-child"></i>
                                </div>
                            </div>
                            <div class="dashboard-card-child-2">
                                <h5>{{todaysBookingPending}}</h5>
                                <p>
                                    {{ trans('lang.today_pending_booking') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row chart-row">
            <div class="col-12 col-sm-6 col-md-9 pr-md-0">
                <div class="main-layout-card">
                    <div class="main-layout-card-header-with-button">
                        <div class="main-layout-card-content-wrapper">
                            <div class="main-layout-card-header-contents">
                                <h5 class="no-margin">{{ trans('lang.booking_overview') }}</h5>
                                <small class="grey-text">{{ trans('lang.last_12_months') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="main-layout-card-content chartjs-loader-wrapper chart-height">
                        <div v-if="hideChartLoader">
                            <div class="chartjs-loader center-align">
                                <pre-loader></pre-loader>
                            </div>
                        </div>
                        <span v-else><line-chart :width="400" :height="405" :linedata="lineChartItem"></line-chart></span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="main-layout-card">
                    <div class="main-layout-card-header-with-button">
                        <div class="main-layout-card-content-wrapper">
                            <div class="main-layout-card-header-contents">
                                <h5 class="no-margin">{{ trans('lang.booking_type') }}</h5>
                                <small class="grey-text">{{ trans('lang.total_bookings_of_different_services') }}
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="main-layout-card-content chartjs-loader-wrapper chart-height">
                        <div v-if="hideChartLoader">
                            <div class="chartjs-loader center-align">
                                <pre-loader></pre-loader>
                            </div>
                        </div>
                        <span v-else>
                            <doughnut-chart :width="200"
                                            :height="405"
                                            :confirmed="confirmedPercentage"
                                            :pending="pendingPercentage">
                            </doughnut-chart>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-6 col-md-4 pr-md-0">
                <div class="card dashboard-card-bottom">
                    <div class="card-content cyan">
                        <div class="text-white">
                            <h5>{{ trans('lang.this_Month') }}</h5>
                            <h6>{{ trans('lang.total_booking') }}</h6>
                            <h3>{{curMonthTotBooking}}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 pr-md-0">
                <div class="card dashboard-card-bottom">
                    <div class="card-content purple">
                        <div class="text-white">
                            <h5>{{ trans('lang.last_month') }}</h5>
                            <h6>{{ trans('lang.total_booking') }}</h6>
                            <h3>{{lastMonthTotBooking}}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="card dashboard-card-bottom">
                    <div class="card-content blue">
                        <div class="text-white">
                            <h5>{{ trans('lang.till_now') }}</h5>
                            <h6>{{ trans('lang.total_booking') }}</h6>
                            <h3>{{allTimeTotBooking}}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import axiosGetPost from '../../helper/axiosGetPostCommon';
    import lineChart from '../charts/lineChart'
    import doughnutChart from '../charts/doughnutChart'

    export default {
        extends: axiosGetPost,
        data() {
            return {
                item: [0],
                showChartLoader: true,
                totalAllBooking: 0,
                totalBooking: 0,
                todaysBooking: 0,
                todaysBookingPending: 0,
                curMonthTotBooking: 0,
                lastMonthTotBooking: 0,
                allTimeTotBooking: 0,
                hideChartLoader: true,
                lineChartItem: [],
                confirmedPercentage: 0,
                pendingPercentage: 0,
            }
        },
        components:
            {
                lineChart,
                doughnutChart
            },
        created() {
            this.getDashboardData('/dashboarddata');
        },
        methods:
        {
            getDashboardData(route) {
                let instance = this;
                this.axiosGet(route,
                    function (response) {

                        //line chart data
                        instance.lineChartItem = response.data.lineChartData;

                        //doughnut chart data
                        let confirm = response.data.doughnutChartData.confirmedCount;
                        let pending = response.data.doughnutChartData.pendingCount;
                        instance.confirmedPercentage = Math.round((((confirm) * 100) / (confirm + pending)));
                        instance.pendingPercentage = Math.round(((pending) * 100) / (confirm + pending));

                        //get other data for dashboard
                        instance.totalAllBooking = response.data.totalAllBooking;
                        instance.totalBooking = response.data.totalBooking;
                        instance.todaysBooking = response.data.todaysBooking;
                        instance.todaysBookingPending = response.data.todaysBookingPending;
                        instance.curMonthTotBooking = response.data.curMonthTotBooking;
                        instance.lastMonthTotBooking = response.data.lastMonthTotBooking;
                        instance.allTimeTotBooking = response.data.allTimeTotBooking;
                        instance.hideChartLoader = false;
                    },
                    function (response) {
                    },
                );

            },
        }
    }
</script>
