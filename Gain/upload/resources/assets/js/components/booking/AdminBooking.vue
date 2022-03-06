<template>
    <div>
        <div class="modal-layout-header">
            <div class="container-fluid p-0">
                <div class="row">
                    <div class="col-10">
                        <h4 class="m-0">{{trans(`lang.${id ? 'edit_booking' : 'add_booking'}`) }}</h4>
                    </div>
                    <div class="col-2 text-right">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click.prevent="">
                            <span aria-hidden="true"><i class="la la-close"></i></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <pre-loader v-if="circleLoader"></pre-loader>
        <div v-else class="modal-layout-content booking-modal-min-height">
            <div>
                <div class="form-row justify-content-center" v-if="!confirmBook">
                    <div class="form-group col-12 col-md-7">
                        <label for="select_service">{{ trans('lang.service') }}</label>
                        <select id="select_service"
                                v-model="service_list"
                                class="custom-select"
                                v-validate="'required'"
                                data-vv-as="branch"
                                :disabled="isDisabledWhenEdit"
                                name="select-service">
                            <option value disabled selected>{{ trans('lang.choose_one') }}</option>
                            <option v-for="service in services" :value="service.id">
                                {{service.title}}
                            </option>
                        </select>
                        <div class="heightError">
                            <small class="text-danger" v-show="errors.has('select-service')">
                                {{ errors.first('select-service') }}
                            </small>
                        </div>
                    </div>
                    <div class="form-group col-12 col-md-7">
                        <label for="service_date">{{ trans('lang.booking_date') }}</label>
                        <datepicker id="service_date"
                                    :disabled="!(service_list.length > 0)"
                                    v-model="booking_date"
                                    :disabledDates="state.disabledDates"
                                    @input="dateChange()"
                                    :format="datePickerDateFormatter(booking_date)"
                                    :bootstrap-styling="true">
                        </datepicker>
                    </div>
                </div>
                <div class="form-row justify-content-center" v-if="booking_date && !confirmBook">
                    <div class="form-group col-12 col-md-7">
                        <div class="text-center" v-if="serviceDetails.business_type != 'salon'">
                            <label class="mb-0">{{ trans('lang.your_desired_quantity') }}</label>
                            <div class="input-group time-slot-input mx-auto">
                                <div class="input-group-prepend" v-show="!isDisabledWhenEdit">
                                    <span class="input-group-text py-0" @click.prevent="decreaseSeat()">
                                        <i class="la la-minus-circle"/>
                                    </span>
                                </div>
                                <input v-if="selectedServiceDurationType == 'hourly'"
                                       v-model="seat"
                                       type="text"
                                       :disabled="isDisabledWhenEdit"
                                       class="form-control text-center py-0"
                                       :class="{'seat-increase-decrease': increaseDecreaseAnimate}"
                                       @animationend="increaseDecreaseAnimate = false"
                                       onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57">
                                <input v-else
                                       v-model="seat"
                                       type="text"
                                       :disabled="isDisabledWhenEdit"
                                       class="form-control text-center py-0"
                                       :class="{'seat-increase-decrease': increaseDecreaseAnimate}"
                                       @animationend="increaseDecreaseAnimate = false"
                                       onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57"
                                       @input="checkAvailableSeatForDaily">
                                <div class="input-group-append" v-show="!isDisabledWhenEdit">
                                    <span class="input-group-text py-0" @click.prevent="increaseSeat()">
                                        <i class="la la-plus-circle"/>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Salon Quantity -->
                        <div class="text-center" v-else>
                            <div class="adult">
                                <label class="mb-0">{{ trans('lang.no_of_adults') }}</label>
                                <div class="input-group time-slot-input mx-auto">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text py-0"
                                              @click.prevent="plusMinusSalon('-', 'adult')">
                                            <i class="la la-minus-circle"/>
                                        </span>
                                    </div>
                                    <input v-model="noOfAdult"
                                           type="text"
                                           class="form-control text-center py-0"
                                           :class="{'seat-increase-decrease': increaseDecreaseAnimate}"
                                           @animationend="increaseDecreaseAnimate = false"
                                           onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57">
                                    <div class="input-group-append">
                                        <span class="input-group-text py-0"
                                              @click.prevent="plusMinusSalon('+', 'adult')">
                                            <i class="la la-plus-circle"/>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div v-show="serviceDetails.consider_children_for_price == 'Yes'" class="children mt-3">
                                <label class="mb-0">{{ trans('lang.no_of_Children') }}</label>
                                <div class="input-group time-slot-input mx-auto">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text py-0"
                                              @click.prevent="plusMinusSalon('-', 'children')">
                                            <i class="la la-minus-circle"/>
                                        </span>
                                    </div>
                                    <input v-model="noOfChild"
                                           type="text"
                                           class="form-control text-center py-0"
                                           :class="{'seat-increase-decrease': increaseDecreaseAnimate}"
                                           @animationend="increaseDecreaseAnimate = false"
                                           onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57">
                                    <div class="input-group-append">
                                        <span class="input-group-text py-0"
                                              @click.prevent="plusMinusSalon('+', 'children')">
                                            <i class="la la-plus-circle"/>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="time-slot-wrapper" v-if="selectedServiceDurationType == 'hourly'">
                            <div class="time-slot-container" v-for="(time,index) in serviceTiming">
                                <a href="#" class="time-slot waves-effect waves-light"
                                   @click.prevent="setSelectedTimeSlot(time)"
                                   :class="{'selectedTime': checkSelectedTimeSlot(time), 'disabledTimeSlot': disabledTimeSlot[index]}">
                                    <span v-if="selectedServiceDurationType === 'hourly'">
                                        {{ trans('lang.book_time') }}
                                    </span>
                                    <span v-else>{{ trans('lang.book_date') }}</span> : {{ time }}
                                    <span v-if="disabledTimeSlot[index] && available[index]>=0">
                                        - {{ trans('lang.available') }} : {{available[index]}}
                                    </span>
                                </a>
                            </div>
                        </div>
                        <div class="time-slot-button">
                            <button v-if="id"
                                    class="btn app-color"
                                    @click.prevent="update()"
                                    :disabled="(checkIfTimeIsSelected() && selectedServiceDurationType == 'hourly') || buttonLoader">

                                    <span class="w-100" :class="{buttonText:buttonLoader}">
                                        <roller-loader class="d-inline mx-2" v-if="buttonLoader"/>
                                        {{ trans('lang.update') }}
                                    </span>
                            </button>
                            <button v-else 
                                    class="btn app-color"
                                    @click.prevent="changeConfirm(true)"
                                    :disabled="checkIfTimeIsSelected() && selectedServiceDurationType == 'hourly'">
                                {{ trans('lang.next_') }}
                            </button>
                        </div>
                    </div>
                    <div class="form-group col-12 col-md-7 mt-3" v-if="serviceTiming.length <= 0">
                        <pre-loader></pre-loader>
                    </div>
                </div>
                <span v-if="circleLoader">
                        <pre-loader></pre-loader>
                </span>
                <span v-else>
                    <div class="form-row" v-if="confirmBook">

                        <div class="col-12">
                            <h6>{{ trans('lang.customer_details') }}</h6>
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="first_name">{{ trans('lang.first_name') }}</label>
                            <input id="first_name"
                                   v-model="first_name"
                                   v-validate="'required'"
                                   data-vv-as="first name"
                                   name="first-name"
                                   type="text"
                                   class="form-control">
                            <div class="heightError">
                                <small class="text-danger" v-show="errors.has('first-name')">
                                    {{ errors.first('first-name') }}
                                </small>
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="last_name">{{ trans('lang.last_name') }}</label>
                            <input id="last_name"
                                   v-model="last_name"
                                   v-validate="'required'"
                                   data-vv-as="last name"
                                   name="last-name"
                                   type="text"
                                   class="form-control">
                            <div class="heightError">
                                <small class="text-danger" v-show="errors.has('last-name')">
                                    {{ errors.first('last-name') }}
                                </small>
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="email">{{ trans('lang.login_email') }}</label>
                            <input id="email"
                                   v-model="email"
                                   v-validate="'required'"
                                   data-vv-as="email"
                                   name="email"
                                   type="email"
                                   class="form-control">
                            <div class="heightError">
                                <small class="text-danger" v-show="errors.has('email')">
                                    {{ errors.first('email') }}
                                </small>
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="phone_number">{{ trans('lang.phone_number') }}</label>
                            <input id="phone_number"
                                   v-model="phone_number"
                                   type="text"
                                   class="form-control">
                        </div>
                        <div class="form-group col-12">
                            <label for="comment">{{ trans('lang.comment') }}</label>
                            <textarea id="comment" v-model="comment" class="form-control"></textarea>
                        </div>
                        <div class="col-12">
                            <custom-input-field v-if="isActiveFields"
                                                :custom_form_fields="customFields"
                                                :date_id="customFieldDateId"
                                                :newCustomFieldData="newCustomFieldData"
                                                size="large"
                                                @fieldOptions="fieldOptions">
                            </custom-input-field>
                        </div>
                        <div class="col-12">
                            <button class="btn app-color mobile-btn"
                                    type="submit"
                                    @click.prevent=" is_disable(), book()">
                                {{ trans(`lang.${id? 'update': 'book'}`) }}
                            </button>
                            <button class="btn cancel-btn mobile-btn"
                                    @click.prevent="setActive()">
                                {{ trans('lang.cancel') }}
                            </button>
                            <button class="btn cancel-btn mobile-btn"
                                    @click.prevent="changeConfirm(false)">
                                {{ trans('lang.back_') }}
                            </button>
                        </div>
                    </div>
                </span>

            </div>
        </div>
    </div>
</template>

<script>
    import axiosGetPost from '../../helper/axiosGetPostCommon';

    export default {
        props: {
            modalID : String,
            id: {
                default: null
            }
        },
        extends: axiosGetPost,
        data() {
            return {
                customFields: [],
                customFieldsOptions: {},
                newCustomFieldData: {},
                customFieldDateId: [],
                isActiveFields: false,
                first_name: '',
                last_name: '',
                email: '',
                phone_number: '',
                comment: '',
                service_list: '',
                services: '',
                selectedServiceDurationType: '',
                isSubmitted: false,
                serviceTiming: [],
                slot: [],
                seat: 1,
                booking_date: '',
                avSeat: true,
                offdays: [],
                holydays: [],
                available: [],
                checkMultiBooking: undefined,
                saveSlot: [],
                duration_type: 0,
                salonDurationType: 0,
                max_day: '',
                from: '',
                to: '',
                max_day_before: '',
                is_disabled: false,
                confirmBook: false,
                selectedTimeSlot: [],
                disabledTimeSlot: [],
                circleLoader: true,
                bookingProcessing: false,
                increaseDecreaseAnimate: '',
                newServiceDate: [],
                showPreLoader: true,
                state: {},
                serviceDetails: {},
                noOfAdult: 1,
                noOfChild: 0,
                buttonLoader: false,
            }
        },
        watch: {
            service_list() {
                let instance = this;
                instance.readSupportingData();
            }
        },
        computed:{
            isDisabledWhenEdit(){
                return this.id ? true : false;
            }
        },
        created() {
            this.getServices();

            if(this.id) this.getSingleBookService();
        },
        mounted() {
            // this.readSupportingData();
            let instance = this;
            $('input[type="checkbox"]').on('change', function () {
                $('input[name="' + this.name + '"]').not(this).prop('checked', false);
            });

            $('#select_service').on('change', function () {
                let value = $(this).val();
                instance.serviceTiming = [];
                instance.disableTimeSlot();
                instance.services.forEach(function (i) {
                    if (i.id == value) {
                        instance.duration_type = i.service_duration_type;
                        instance.max_day_before = i.allow_booking_max_day_ago;
                        if (i.service_starting_date) {
                            let today = moment().format('YYYY-MM-DD');
                            let sDate = moment(i.service_starting_date).format('YYYY-MM-DD');

                            if (today > sDate) {
                                instance.from = 'Today';
                            } else {
                                instance.from = i.service_starting_date.split('-');
                                instance.from[1] = instance.from[1] - 1;
                            }

                        } else {
                            instance.from = 'Today';
                        }
                        if (!i.service_ending_date) {
                            instance.max_day = (moment().add(i.allow_booking_max_day_ago - 1, 'days').format("YYYY/MM/DD")).split('/');//making date to array
                            instance.max_day[1] = instance.max_day[1] - 1;
                        } else {
                            instance.max_day = i.service_ending_date.split('-');
                            instance.max_day[1] = instance.max_day[1] - 1;
                        }

                        let disableCondition = '', day;
                        for (day of instance.offdays) {
                            if (disableCondition === '') {
                                disableCondition += 'date.getDay()==' + (day - 1);
                            } else {
                                disableCondition += ' || date.getDay()==' + (day - 1);
                            }
                        }
                    }
                });
            });

            $('#service_date').on('change', function () {
                instance.disableTimeSlot();
            });

            // Get previous day
            let date = new Date();
            date.setDate(date.getDate() - 1);
            instance.state = {
                disabledDates: {
                    to: date,
                }
            };
        },
        methods: {
            dateChange() {
                this.serviceTiming = [];
                this.getTiming(this.service_list);
                this.disableTimeSlot();
            },
            fieldOptions(options, newDateId) {
                this.customFieldsOptions = options;
                this.newServiceDate = newDateId;
            },
            readSupportingData() {
                let instance = this;
                instance.axiosPost('/get-service-custom-field', {
                        'table_name': "booking"
                    },
                    function (response) {
                        instance.customFields = response.data.filter(function (element) {
                            if (element.service_id == null || element.service_id == instance.service_list)  return element;
                        });
                        instance.isActiveFields = true;
                    },
                    function (error) {

                    }
                );
            },
            checkAvailableSeatForDaily(event) {
                if (parseInt(event.target.value) >= this.available[0]) {
                    this.seat = this.available[0];
                } else {
                    this.seat = event.target.value;
                }
            },
            decreaseSeat() {
                if (this.seat <= 1) {
                    this.seat = 1;
                } else {
                    this.seat--;
                    this.increaseDecreaseAnimate = true;
                }
                this.disableTimeSlot();
            },
            plusMinusSalon(value, type) {
                if (type == "adult") {
                    if (value == "+") {
                        this.noOfAdult++;
                        this.seat = this.noOfAdult + this.noOfChild;
                        this.disableTimeSlot();
                    } else {
                        --this.noOfAdult;
                        if (this.noOfAdult <= 1) {
                            this.noOfAdult = 1;
                        } else {
                            this.seat = this.noOfAdult + this.noOfChild;
                            this.disableTimeSlot();
                        }
                    }
                } else {
                    if (value == '+') {
                        this.noOfChild++;
                        this.seat = this.noOfAdult + this.noOfChild;
                        this.disableTimeSlot();
                    } else {
                        --this.noOfChild;
                        if (this.noOfChild <= 0) {
                            this.noOfChild = 0;
                        } else {
                            this.seat = this.noOfAdult + this.noOfChild;
                            this.disableTimeSlot();
                        }
                    }
                }
            },
            increaseSeat() {
                this.seat++;
                if (this.selectedServiceDurationType != 'hourly' && this.seat >= this.available[0]) {
                    this.seat = this.available[0]
                }
                this.disableTimeSlot();
                this.increaseDecreaseAnimate = true;
            },
            changeConfirm(value) {
                this.confirmBook = value;
            },
            disableTimeSlot() {
                let instance = this;
                for (let i = 0; i < this.serviceTiming.length; i++) {
                    if (this.seat > this.available[i]) {
                        this.disabledTimeSlot[i] = true;
                        if (_.includes(this.selectedTimeSlot, this.serviceTiming[i])) {
                            this.selectedTimeSlot.splice(_.findIndex(this.selectedTimeSlot, function (time) {
                                return time == instance.serviceTiming[i];
                            }), 1);
                        }
                    } else {
                        this.disabledTimeSlot[i] = false;
                    }
                }
            },
            setSelectedTimeSlot(time) {
                let flag = 0;
                if (this.checkMultiBooking == 1) //for multiple booking
                {
                    for (let i = 0; i < this.selectedTimeSlot.length; i++) {
                        if (this.selectedTimeSlot[i] == time) {
                            this.selectedTimeSlot.splice(i, 1); // splicing time if same time is inserted again
                            flag = 1;
                        }
                    }
                    if (flag == 0) {
                        this.selectedTimeSlot.push(time); // new selected time is pushed to array
                    }
                } else {                                  // for single booking
                    if (this.selectedTimeSlot.length < 1) {
                        this.selectedTimeSlot.push(time); // new selected time is pushed to array
                    } else {
                        if (this.selectedTimeSlot[0] == time) //if same time is inserted again
                        {
                            this.selectedTimeSlot = []; //deselecting time by making the variable empty
                        } else {
                            this.selectedTimeSlot = [];
                            this.selectedTimeSlot.push(time);  // new selected time is pushed to array
                        }
                    }
                }
                this.slot = this.selectedTimeSlot;
            },
            checkSelectedTimeSlot(time) {
                if (_.includes(this.selectedTimeSlot, time)) {
                    return true;
                } else {
                    return false;
                }
            },
            checkIfTimeIsSelected() {
                if (this.selectedTimeSlot.length <= 0) {
                    return true;
                } else if (this.seat == 0) {
                    return true;
                } else {
                    return false;
                }
            },
            setActive: function () {
                this.$emit('setActive', 0);
            },
            getServices() {
                let instance = this;
                instance.axiosGet('/serviceshowforbooking', function (response) {
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
                            } else {
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
                        });
                        instance.circleLoader = false;
                        instance.disableTimeSlot();
                        setTimeout(function () {
                            $('#select_service').on('change', function () {
                                let value = $(this).val();
                                instance.service_list = value;
                                instance.serviceDetails = instance.services.find(item => item.id == instance.service_list);
                                instance.salonDurationType = instance.serviceDetails.service_duration_type;
                                instance.slot = [];
                                instance.serviceTiming = [];
                                instance.disableTimeSlot();
                                if (instance.booking_date) {
                                    instance.getTiming(value)
                                }
                                let i;
                                for (i = 0; i < instance.services.length; i++) {
                                    if (instance.services[i].id == value) {
                                        instance.checkMultiBooking = instance.services[i].multiple_bookings
                                        instance.selectedServiceDurationType = instance.services[i].service_duration_type
                                        break;
                                    }
                                }
                            });
                        });
                        instance.circleLoader = false;
                    },
                    function (response) {

                    })
            },
            book() {
                let instance = this, index, minsSeat = 999999, i;

                if (instance.service_list && instance.booking_date && instance.avSeat && instance.seat > 0) {

                    if (instance.duration_type == 'hourly' || instance.salonDurationType == 'hourly') {
                        for (i = 0; i < this.slot.length; i++) {
                            instance.index = this.serviceTiming.indexOf(this.slot[i]);
                            if (this.available[instance.index] < minsSeat) {
                                minsSeat = this.available[instance.index];
                            }
                        }
                    } else {
                        minsSeat = this.available[0];
                    }

                    if (this.seat > minsSeat) {
                        this.avSeat = false;
                    } else {
                        this.$validator.validateAll().then((result) => {
                            if (result) {
                                instance.circleLoader = true;
                                instance.bookingProcessing = true;

                                instance.inputFields = {
                                    id: instance.service_list,
                                    first_name: instance.first_name,
                                    last_name: instance.last_name,
                                    email: instance.email,
                                    phone_number: instance.phone_number,
                                    comment: instance.comment,
                                    seat: instance.seat,
                                    slot: instance.slot,
                                    booking_date: moment(instance.booking_date, instance.dateFormat.toUpperCase()).format('YYYY-MM-DD'),
                                    duration_type: instance.duration_type,
                                    customFields: JSON.stringify(instance.customFieldsOptions)
                                };
                                if (instance.businessType == 'salon') {
                                    instance.inputFields.adult = instance.noOfAdult;
                                    instance.inputFields.children = instance.noOfChild;
                                    instance.inputFields.duration_type = instance.salonDurationType;
                                }

                                instance.postDataMethod('/bookservice', instance.inputFields);
                            }
                        });
                    }
                } else {
                    instance.is_disabled = false;
                }
            },
            getTiming(id) {
                let instance = this;
                if (instance.booking_date != '') {

                    instance.axiosGet('/servicetiming/' + id + '/' + moment(this.booking_date, 'MMMDDYYYY').format('YYYY-MM-DD'),
                        function (response) {
                            instance.serviceTiming = response.data.stack;
                            instance.available = response.data.seat;
                            instance.disableTimeSlot();

                            instance.$nextTick(function () {
                                if (instance.selectedServiceDurationType == 'daily') {
                                    instance.slot = instance.serviceTiming;
                                } else {
                                    $('#space').on('input', function () {
                                        let value = $(this).val();
                                        instance.seat = value;
                                        instance.disableTimeSlot();
                                    });
                                    instance.disableTimeSlot();
                                }
                                instance.seat = 1;
                            });
                        },
                        function (response) {

                        },
                    );
                }
            },
            is_disable() {
                this.is_disabled = true;
            },
            postDataThenFunctionality(response) {
                this.circleLoader = false;
                $(this.modalID).modal('hide');
                this.$emit('resetAdminFormModal');
                this.$hub.$emit('reloadDataTable');
                this.confirmBook = false;

            },
            postDataCatchFunctionality(error) {
                this.circleLoader = false;
            },

            getSingleBookService(){
                let instance = this;
                this.axiosGet(`/single-book-service/${this.id}`,function (response) {
                    let bookingData = response.data.bookingData,
                        service = response.data.service,
                        user = response.data.user;

                    instance.service_list = bookingData.service_id.toString();
                    instance.comment = bookingData.comment;
                    if(bookingData.booking_date){
                        instance.booking_date = bookingData.booking_date;
                        instance.dateChange();
                    }
                    instance.selectedServiceDurationType = service.service_duration_type; 
                    instance.selectedTimeSlot = bookingData.booking_time;
                    instance.slot = bookingData.booking_time;
                    instance.checkMultiBooking = service.multiple_bookings;

                    if(user){
                        instance.first_name = user.first_name;
                        instance.last_name = user.last_name;
                        instance.phone_number = user.phone;
                        instance.email = user.email;
                    }
                },
                function (response) {

                })
            },
            update(){
                let instance = this,
                    url = `/update-booking/${this.id}`,
                    postData = {
                        booking_date : this.booking_date,
                        booking_time : this.slot
                    };
                this.setPreLoader(false);
                this.buttonLoader = true;
                this.axiosGETorPOST({
                    url: url,
                    postData: postData
                }, (success, res) => {

                    instance.setPreLoader(true);
                    instance.buttonLoader = false;
                    $(instance.modalID).modal('hide');
                    instance.$hub.$emit('reloadDataTable');
                    instance.showSuccessAlert(res.message);
                }, (error, res) => {

                    instance.buttonLoader = false;
                    instance.setPreLoader(true);
                });
            }
        }
    }
</script>