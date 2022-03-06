<template>
    <div class="main-layout-wrapper">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent m-0">
                <li class="breadcrumb-item">
                    <span>{{ trans('lang.reports') }}</span>
                </li>
            </ol>
        </nav>
        <div class="main-layout-card">
            <div class="main-layout-card">
                <div class="main-layout-card-header">
                    <div class="main-layout-card-content-wrapper">
                        <div class="main-layout-card-header-contents">
                            <h5 class="bluish-text no-margin">{{ trans('lang.reports') }}</h5>
                        </div>
                    </div>
                </div>
                <div class="main-layout-card-content">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-row">
                                <div class="form-group col-md-6 dateRangeFilter">
                                    <datepicker
                                            @change="getDatefilterValues"
                                            :input-class="{ 'is-invalid': errors.has('start date')}"
                                            v-validate="'required'"
                                            name="start date"
                                            id="start_date"
                                            v-model="start_date"
                                            :bootstrap-styling="true"
                                            :format="datePickerDateFormatter(start_date)">
                                    </datepicker>
                                </div>
                                <div class="form-group col-md-6">
                                    <select v-model="service_list" id="select" class="custom-select"
                                            @change="checkService($event)">
                                        <option value="" disabled selected>{{ trans('lang.choose_one') }}</option>
                                        <option v-for="service in services" :value="service.id"> {{service.title}}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <pre-loader v-if="!hidePreloader"></pre-loader>
                            <div id="renderChart" v-else>
                                <report-bar-chart ref="barchart"
                                                  :width="400"
                                                  :height="582"
                                                  v-if="serviceTiming.length > 0"
                                                  :data="chartData">
                                </report-bar-chart>
                                <div v-if="serviceTiming.length <= 0">
                                    <h6 class="text-center"> {{ trans('lang.didnt_find') }}</h6>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import axiosGetPost from '../../helper/axiosGetPostCommon';

    export default {
        extends: axiosGetPost,
        data() {
            return {
                start_date: '',
                service_list: '',
                services: [],
                serviceTiming: [],
                slot: [],
                seat: 1,
                booking_date: '',
                avSeat: true,
                offdays: [],
                holydays: [],
                confirm: [],
                pending: [],
                checkMultiBooking: undefined,
                saveSlot: [],
                availableSeat: [],
                preloaderType: 'load',
                hidePreloader: false,
                error: false,
                chartData: {},
                count: '',
                showCircleLoader: true,
                formDate: 0,
                toDate: 0,
                state: {},
            }
        },
        created() {
            this.getServices();
        },
        mounted() {
            this.getTodaysDate();
            this.state = {
                disabledDates: {
                    to: new Date(1001, 0, 5),
                    from: new Date(),
                }
            };

        },
        watch: {
            start_date() {
                this.getDatefilterValues();
            }
        },
        methods: {
            checkService($event) {
                let instance = this;
                let id = $event.target.value;
                instance.service_list = id;
                instance.slot = []
                if (instance.booking_date) {
                    instance.getTiming(id)
                }
                let i;
                for (i = 0; i < instance.services.length; i++) {
                    if (instance.services[i].id == id) {
                        instance.checkMultiBooking = instance.services[i].multiple_bookings
                        break;
                    }
                }
            },
            getTodaysDate() {
                let today = new Date();
                this.start_date = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
                this.booking_date = this.start_date;
            },
            getServices() {
                let instance = this;
                this.axiosGet('/serviceshowforbooking',
                    function (response) {
                        instance.services = response.data.serviceData;
                        instance.offdays = response.data.offdays;
                        instance.holydays = response.data.holydays;
                        instance.holydays.forEach((hDay, index) => {
                            if (hDay.start_date == hDay.end_date) {
                                hDay.start_date = hDay.start_date.split("-");
                                hDay.start_date[0] = parseInt(hDay.start_date[0]);
                                hDay.start_date[1] = parseInt(hDay.start_date[1]) - 1;
                                hDay.start_date[2] = parseInt(hDay.start_date[2]);
                                instance.offdays.push(hDay.start_date)
                            }
                            else {
                                hDay.start_date = hDay.start_date.split("-");
                                hDay.start_date[0] = parseInt(hDay.start_date[0]);
                                hDay.start_date[1] = parseInt(hDay.start_date[1]) - 1;
                                hDay.start_date[2] = parseInt(hDay.start_date[2]);
                                hDay.end_date = hDay.end_date.split("-");
                                hDay.end_date[0] = parseInt(hDay.end_date[0]);
                                hDay.end_date[1] = parseInt(hDay.end_date[1]) - 1;
                                hDay.end_date[2] = parseInt(hDay.end_date[2]);
                                instance.offdays.push({from: hDay.start_date, to: hDay.end_date})
                            }
                        })
                        // select first service from service list
                        if (instance.services.length > 0) {
                            instance.services.forEach((e) => {
                                if (!instance.service_list) instance.service_list = e.id;
                            })
                            if (instance.booking_date) {
                                instance.getTiming(instance.service_list);
                            }
                        }
                        instance.hidePreloader = true;
                    },
                    function (response) {
                    },
                );
            },
            // time slot with confirm pending seat
            getTiming(id) {
                let instance = this;
                instance.hidePreloader = false;
                let convertedDate = moment(instance.start_date).format('YYYY-MM-DD');
                this.axiosGet('/booking-report/' + id + '/' + convertedDate,
                    function (response) {
                        instance.serviceTiming = response.data.stack;
                        instance.confirm = response.data.confirmSeat;
                        instance.pending = response.data.pendingSeat;
                        instance.availableSeat = response.data.availableSeat;
                        instance.count = response.data.count;
                        if (instance.count > 0) {
                            instance.chartData = {
                                labels: instance.serviceTiming,
                                datasets: [
                                    {
                                        label: instance.trans('lang.confirmed_booking'),
                                        backgroundColor: '#4CAF50',
                                        data: instance.confirm
                                    },
                                    {
                                        label: instance.trans('lang.pending_booking'),
                                        backgroundColor: '#ff9800',
                                        data: instance.pending
                                    }
                                ]
                            };
                        } else {
                            instance.serviceTiming.length = 0;
                        }
                        instance.hidePreloader = true;
                    },
                    function (response) {
                        instance.hidePreloader = true;
                    });
            },
            getDatefilterValues: function () {
                this.booking_date = this.start_date;
                this.toDate = this.start_date;
                /* this condition has been used for the request time matching (booking_date and service_list) otherwise routing error will be happen */
                if (this.service_list) this.getTiming(this.service_list);
            },
        }
    }
</script>