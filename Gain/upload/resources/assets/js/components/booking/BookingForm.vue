<template>
    <div class="booking-form-wrapper">
        <div class="previous-button modal-close" v-if="!bookingLoading && confirmBook == 1">
            <a href="" data-dismiss="modal"
               @click.prevent="changeConfirm(''), setRenderBookingForm(false), closeModal()">
                <i class="la la-angle-left"/>
                {{ trans('lang.back_')}}
            </a>
        </div>
        <div class="previous-button" v-if="!bookingLoading && confirmBook == 2">
            <p @click.prevent="changeConfirm(1), setErrorInvalid(), renderTextFields()"
               class="text-primary cursor-pointer">
                <i class="la la-angle-left"/>
                {{ trans('lang.back_') }}
            </p>
        </div>
        <div class="previous-button" v-if="!bookingLoading && confirmBook == 3">
            <a href="#"
               @click.prevent="changeConfirm(2), setErrorInvalid(),renderTextFields(), paymentMethodClicked=''"
               class="">
                <i class="la la-angle-left"/>
                {{ trans('lang.back_')}}
            </a>
        </div>
        <div class="previous-button" v-if="enablePaypal && confirmBook == 4">
            <a href="#"
               @click.prevent="changeConfirm(3),setErrorInvalid(), renderTextFields(), paymentMethodClicked=''"
               class="">
                <i class="la la-angle-left"/>
                {{ trans('lang.back_')}}
            </a>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 col-sm-7 col-md-5 col-lg-5">

                <div class="row justify-content-center" v-if="bookingLoading">
                    <div class="col-12" v-if="bookingProcessing">
                        <div class="text-center">
                            <pre-loader></pre-loader>
                            <h5>
                                {{ trans('lang.please_wait_while') }}
                            </h5>
                        </div>
                    </div>
                    <div class="col-12" v-if="!bookingProcessing && !bookingProcessingFailed">
                        <div class="text-center">
                            <div class="mb-4">
                                <h5 class="smile_icon">
                                    <i class="la la-smile la-2x"></i>
                                </h5>
                                <h5>
                                    {{ trans('lang.booking_submitted_successfully') }}
                                </h5>
                            </div>
                            <button class="btn app-color"
                                    @click.prevent="closeModal"
                                    data-dismiss="modal">
                                {{ trans('lang.thank_you') }}
                            </button>
                        </div>
                    </div>
                    <div class="col-12" v-if="bookingProcessingFailed">
                        <div class="text-center">
                            <i class="la la-frown la-2x text-danger"></i>
                            <h5 class="text-danger">
                                {{ trans('lang.booking_not_submitted') }}
                            </h5>
                            <h6>{{ trans('lang.please_try_again') }}</h6>
                            <button class="modal-action modal-close btn app-color"
                                    @click.prevent="bookingProcessing=true, setRenderBookingForm(false), renderBookingForm(), bookingProcessingFailed=false">
                                {{ trans('lang.ok') }}
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row" v-if="!bookingLoading && !circleLoader">
                    <div class="col-12">
                        <h5>{{ serviceTitle }}</h5>
                        <div class="row mb-3" v-if="confirmBook">
                            <div class="col-12">
                                <p>{{ show_date}}</p>
                                <div class="comment-wrapper">
                                    <span v-if="service.service_duration_type === 'hourly'">
                                        {{trans('lang.book_time')}}:
                                         <span v-if="slot.length==0"> {{ trans('lang.not_selected_yet') }}</span>
                                         <span v-else v-for="(slots, index) in slot">
                                             <span v-if="index>0">, </span>{{ slots }}
                                         </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-5" v-if="confirmBook">
                            <div class="col-12">
                                <p>
                                    {{ trans('lang.price') }}: {{servicePrice}}
                                </p>
                                <p>
                                    {{ trans('lang.quantity') }}: {{ seat }}
                                </p>
                                <p>
                                    {{ trans('lang.bill') }}:
                                    <span v-if='bill_in()'>{{ show_bill }}</span>
                                    <span v-else> {{bill}} </span>
                                </p>
                            </div>
                        </div>
                        <div class="row" v-if="confirmBook == 3">
                            <div class="col-12">
                                <p>
                                    {{ trans('lang.your_details')}}
                                </p>
                                <p>
                                    {{trans('lang.name')}}: {{first_name + ' ' + last_name }}
                                </p>
                                <p>
                                    {{trans('lang.login_email')}}: {{ email }}
                                </p>
                                <p v-if="phone != ''">
                                    {{trans('lang.phone')}}: {{ phone }}
                                </p>
                                <p class="comment-wrapper" v-if="comment != ''">
                                    {{trans('lang.comment')}}: {{ comment }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center" v-if="!bookingLoading">
                    <div class="col-12">
                        <pre-loader v-if="circleLoader"></pre-loader>
                        <div class="form-row" v-show="!circleLoader && confirmBook == 1">
                            <div class="form-group col-12">
                                <div class="time-slot-space-wrapper text-center">
                                    <a href="#" class="mx-auto time-slot-space" @click.prevent="decreaseSeat()">
                                        <i class="la la-minus-circle"></i>
                                    </a>
                                    <div class="mx-auto input-field time-slot-space"
                                         v-if="selectedServiceDurationType == 'hourly'">
                                        <label for="space" class="">
                                            {{ trans('lang.your_desired_quantity') }}
                                        </label>
                                        <input v-model="seat"
                                               type="text"
                                               id="space"
                                               class="form-control text-center seat-input"
                                               :class="{'seat-increase-decrease': increaseDecreaseAnimate}"
                                               @animationend="increaseDecreaseAnimate = false"
                                               onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57">
                                    </div>
                                    <div class="mx-auto input-field time-slot-space" v-else>
                                        <label for="space" class="">
                                            {{ trans('lang.your_desired_quantity') }}
                                        </label>
                                        <input v-model="seat"
                                               type="text"
                                               id="space"
                                               class="form-control text-center seat-input"
                                               :class="{'seat-increase-decrease': increaseDecreaseAnimate}"
                                               @animationend="increaseDecreaseAnimate = false"
                                               onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57"
                                               @input="checkAvailableSeatForDaily">
                                    </div>
                                    <a href="#" class="mx-auto time-slot-space" @click.prevent="increaseSeat()">
                                        <i class="la la-plus-circle"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="form-group col-12">
                                <div v-if="service.service_duration_type === 'hourly'" class="text-center">
                                    <span v-if="checkMultiBooking == 0">
                                        {{ trans('lang.multiple_booking_no') }} {{ trans('lang.every_time_slot_provide_you')}} <b>{{ this.slotTime(service.service_duration)}}</b> {{ trans('lang.hours_service') }}
                                    </span>
                                    <span v-else>
                                        {{ trans('lang.multiple_booking_yes') }} {{ trans('lang.every_time_slot_provide_you')}} <b>{{ this.slotTime(service.service_duration)}}</b> {{ trans('lang.hours_service') }}
                                    </span>
                                </div>
                            </div>
                            <div class="form-group col-12">
                                <div class="time-slot-wrapper" v-if="service.service_duration_type === 'hourly'">
                                    <div class="time-slot-container" v-for="(time,index) in this.serviceTiming">
                                        <a href="#" class="time-slot waves-effect waves-light"
                                           @click.prevent="setSelectedTimeSlot(time)"
                                           :class="{'selectedTime': checkSelectedTimeSlot(time), 'disabledTimeSlot': disabledTimeSlot[index]}">
                                            <span v-if="service.service_duration_type === 'hourly'">{{ trans('lang.book_time') }}</span>
                                            <span v-else>{{ trans('lang.book_date') }}</span> : {{ time }}
                                            <span v-if="disabledTimeSlot[index] && available[index]>=0"> - {{ trans('lang.available') }} : {{available[index]}}</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-12">
                                <div class="time-slot-button">
                                    <button class="btn app-color"
                                            @click.prevent="changeConfirm(2),renderTextFields(), renderTextarea()"
                                            :disabled="checkIfTimeIsSelected() && service.service_duration_type === 'hourly'">
                                        {{ trans('lang.next_') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center" v-if="!bookingLoading && confirmBook==2">
                    <div class="col-12" v-if="!user">
                        <div class="form-row">
                            <div class="form-group col-12">
                                <h6>{{ trans("lang.please_provide_your_details") }}</h6>
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


                        </div>
                    </div>
                    <div class="form-group col-12">
                        <label for="comment">{{ trans('lang.comment') }}</label>
                        <textarea id="comment" v-model="comment" class="form-control"/>
                    </div>
                    <div class="form-group col-12">
                        <custom-input-field v-if="isActiveFields"
                                            :custom_form_fields="customFields"
                                            :date_id="customFieldDateId"
                                            :newCustomFieldData="newCustomFieldData"
                                            size="large"
                                            @fieldOptions="fieldOptions">
                        </custom-input-field>
                    </div>
                    <div class="col-12">
                        <div class="user-info-button mt-0">
                            <button class="btn btn-block app-color"
                                    @click.prevent="checkUserInfo()"
                                    :disabled="checkIfTimeIsSelected() && service.service_duration_type === 'hourly'">
                                {{ trans('lang.next_') }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row" v-if="!bookingLoading && confirmBook == 3 && !enablePaypal">
                    <div class="col-12">
                        <div v-if="!checkPaymentMethod(paymentMethods)">
                            {{ trans('lang.please_choose_a_payment_method') }}:
                        </div>
                        <div class="payment-methods-button-container" v-for="paymentMethod in paymentMethods">
                            <a href="#"
                               class="payment-methods-btn center-align"
                               v-if="paymentMethod.type=='stripe' && paymentMethod.available_to_client==1 && paymentMethod.enable==1 && paymentMethod.option"
                               :class="{'selectedPaymentMethod': paymentMethodClicked==paymentMethod.title}"
                               @click.prevent="paymentMethodClicked = paymentMethod.title,isPaymentMethodDefault=true,selectedMethodId=paymentMethod.id,paymentMethodType=paymentMethod.type,pay()">
                                {{paymentMethod.title}}
                            </a>
                            <a href="#"
                               class="payment-methods-btn center-align"
                               v-if="paymentMethod.type=='paypal' && paymentMethod.available_to_client==1 && paymentMethod.enable==1 && paymentMethod.option"
                               :class="{'selectedPaymentMethod': paymentMethodClicked==paymentMethod.title}"
                               @click.prevent="paymentMethodClicked = paymentMethod.title,isPaymentMethodDefault=true,selectedMethodId=paymentMethod.id,paymentMethodType=paymentMethod.type,pay()">
                                {{paymentMethod.title}}
                            </a>
                            <a href="#"
                               class="payment-methods-btn center-align"
                               v-else-if="paymentMethod.type!='stripe' && paymentMethod.type!='paypal' && paymentMethod.available_to_client==1 && paymentMethod.enable==1"
                               :class="{'selectedPaymentMethod': paymentMethodClicked==paymentMethod.title}"
                               @click.prevent="paymentMethodClicked = paymentMethod.title,isPaymentMethodDefault=false,selectedMethodId=paymentMethod.id,paymentMethodType=paymentMethod.type">
                                {{paymentMethod.title}}
                            </a>
                        </div>

                        <div class="row" v-if="!checkPaymentMethod(paymentMethods)">
                            <div class="col-12">
                                <div class="user-info-button">
                                    <button v-if="isPaymentMethodDefault"
                                            class="btn btn-primary app-color mobile-btn"
                                            id="default"
                                            type="submit" :disabled="!paymentMethodClicked"
                                            @click.prevent="">
                                        {{ trans('lang.book') }}
                                    </button>
                                    <button v-else
                                            class="btn btn-primary app-color mobile-btn"
                                            type="submit"
                                            :disabled="!paymentMethodClicked"
                                            @click.prevent="book(0,selectedMethodId)">
                                        {{ trans('lang.book') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row" v-else>
                            <div class="col-12">
                                <div class="user-info-button">
                                    <button class="btn btn-primary app-color mobile-btn"
                                            type="submit"
                                            @click.prevent="book(0,selectedMethodId)">
                                        {{ trans('lang.book') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" v-if="enablePaypal">
                    <div class="col-12">
                        <div v-if="!checkPaymentMethod(paymentMethods)">
                            {{ trans('lang.please_continue_to_process_payment') }}:
                        </div>
                        <div class="payment-methods-button-container">
                            <div id="paypal-button-container"></div>
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
        props: [
            'dateForBooking',
            'service_price',
            'service_selected',
            'checkMultiBooking',
            'serviceTitle',
            'servicePrice',
            'user',
            'service',
            'paymentMethods',
            'id',
            'modalID'
        ],
        data() {
            return {
                customFields: [],
                customFieldsOptions: {},
                newCustomFieldData: {},
                customFieldDateId: [],
                isActiveFields: false,
                newServiceDate: [],
                selectedMethodId: 0,
                stripeBill: 0,
                enablePaypal: false,
                paymentMethodType: '',
                first_name: '',
                last_name: '',
                email: '',
                phone: '',
                comment: '',
                service_list: '',
                services: '',
                isSubmitted: false,
                serviceTiming: [],
                slot: [],
                seat: 1,
                booking_date: '',
                avSeat: true,
                offdays: [],
                holydays: [],
                available: [],
                circleLoader: true,
                bookingLoading: false,
                bookingProcessing: true,
                bookingProcessingFailed: false,
                confirmBook: '',
                selectedTimeSlot: [],
                disabledTimeSlot: [],
                detailsDate: {
                    year: this.dateForBooking.split('-')[0],
                    month: this.dateForBooking.split('-')[1] - 1,
                    Month: moment().month(this.dateForBooking.split('-')[1] - 1).format("MMMM"),
                    day: this.dateForBooking.split('-')[2],
                },
                error: false,
                errorInvalid: {
                    slot: false,
                    seat: false,
                    phone: false
                },
                errorMessage: {
                    slot: this.trans('lang.choose_available_time_slot'),
                    seat: this.trans('lang.booking_seat_can_not_be_zero'),
                    phone: this.trans('lang.required_input_field'),
                },
                is_disabled: false,
                paymentMethodClicked: false,
                isPaymentMethodDefault: false,
                handler: '',
                increaseDecreaseAnimate: '',
                paypalError: true,
                selectedServiceDurationType: '',
                phone_number: '',
            }
        },
        mounted() {
            this.readSupportingData();
            let instance = this;
            if (this.user) {
                this.first_name = this.user.first_name
                this.last_name = this.user.last_name
                this.email = this.user.email
                this.phone = this.user.phone
            }
            this.getTiming();
            instance.paymentMethods.forEach(function (paymentMethod) {
                if (paymentMethod.type == 'stripe') {
                    if (paymentMethod.available_to_client == 1 && paymentMethod.enable == 1) {
                        instance.paymentForm();
                    }
                }
            })
        },
        computed: {
            show_date: function () {
                return this.dateFormatBooking(this.dateForBooking);
            },

            servicePriceCurrencyFormat() {
                return this.numberFormatSalonGeneralBooking(this.servicePrice);
            },

            show_bill: function () {
                return this.numberFormatSalonGeneralBooking(this.bill_in());
            },

            bill() {
                return this.numberFormatSalonGeneralBooking(0);
            }
        },
        methods:
            {
                closeModal() {
                    this.$emit("resetBookingForm");
                },
                paypal() {
                    let instance = this;
                    setTimeout(function () {
                        paypal.Buttons({
                            createOrder: function (data, actions) {
                                instance.paypalError = true;
                                return actions.order.create({
                                    purchase_units: [{
                                        amount: {
                                            value: instance.service_price * instance.seat,
                                            quantity: instance.seat,
                                        }
                                    }]
                                });
                            },
                            onApprove: function (data, actions) {
                                return actions.order.capture().then(function (details) {
                                    instance.setBookingLoader(false);
                                    instance.bookingLoading = true;
                                    instance.enablePaypal = false;
                                    instance.axiosPost('/paypal-transaction-complete', {orderID: data.orderID},
                                        function (response) {
                                            if (response.data == 'success') {
                                                instance.book(instance.service_price * instance.seat, instance.selectedMethodId, data.orderID);
                                            } else {
                                                instance.bookingProcessingFailed = true;
                                                instance.bookingProcessing = false;
                                                instance.changeConfirm(1);
                                                instance.is_disabled = false;
                                            }
                                        }, function (error) {
                                            instance.bookingProcessingFailed = true;
                                            instance.bookingProcessing = false;
                                            instance.changeConfirm(1);
                                            instance.is_disabled = false;

                                        });

                                });
                            },
                            onError: function (err) {
                                if (instance.paypalError) {
                                    instance.paypalError = false;
                                }
                            },
                            style: {
                                color: 'blue',
                                size: 'responsive',
                                fundingicons: false,
                                tagline: false,
                            },

                        }).render('#paypal-button-container');
                    });
                },
                readSupportingData() {
                    let instance = this;
                    instance.axiosGet('/client-custom-field',
                        function (response) {
                            instance.customFields = response.data;
                            instance.isActiveFields = true;
                        },
                        function (error) {

                        }
                    );
                },
                fieldOptions(options, newDateId) {
                    this.customFieldsOptions = options;
                    this.newServiceDate = newDateId;
                },
                checkAvailableSeatForDaily(event) {
                    if (parseInt(event.target.value) >= this.available[0]) {
                        this.seat = this.available[0];
                    } else {
                        this.seat = event.target.value;
                    }
                },
                checkPaymentMethod(paymentMethods) {
                    if (_.differenceWith(paymentMethods, [{
                        'available_to_client': 1,
                        'enable': 1
                    }], _.isEqual).length == 0) {
                        return true;
                    } else {
                        return false;
                    }
                },
                paymentForm() {
                    let instance = this;
                    let publishable_key = '';
                    instance.paymentMethods.forEach(function (element) {
                        if (element.type == 'stripe') {
                            publishable_key = element.option;
                        }
                    });
                    if (publishable_key) {
                        this.handler = StripeCheckout.configure({
                            key: publishable_key,
                            locale: 'auto',
                            image: instance.publicPath + '/images/stripe-logo.png',
                            allowRememberMe: false,
                            token: function (token) {
                                // You can access the token ID with `token.id`.
                                // Get the token ID to your server-side code for use.
                                instance.bookingLoading = true;

                                instance.axiosPost('/paymentstripe', {
                                        email: instance.email,
                                        token: token.id,
                                        bill: instance.stripeBill,
                                        method_id: instance.selectedMethodId,
                                    },
                                    function (response) {
                                        instance.book(instance.stripeBill, instance.selectedMethodId);
                                    },
                                    function (error) {
                                        instance.bookingLoading = false;
                                    });
                            },
                        });
                    }
                },
                pay() {
                    let instance = this;
                    this.$nextTick(function () {
                        document.getElementById('default').addEventListener('click', function (e) {

                            if (instance.paymentMethodType == 'paypal') {
                                instance.confirmBook = 4;
                                instance.enablePaypal = true;
                                instance.paypal();
                            } else {
                                instance.handler.open({
                                    name: instance.appName,
                                    description: 'Bill: ' + instance.currencyCode + ' ' + instance.bill_in(),
                                    email: instance.email,
                                    closed: function () {
                                    }
                                });
                            }

                            e.preventDefault();
                        });
                    })
                },
                changeConfirm(value) {
                    this.confirmBook = value;
                    this.enablePaypal = false;
                },
                renderDayName: function (day, month, year) {
                    return moment(day + "/" + moment().month(month).format("MMMM") + "/" + year, "DD/MMMM/YYYY").format('dddd');
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
                increaseSeat() {
                    this.seat++;
                    if (this.service.service_duration_type != 'hourly' && this.seat >= this.available[0]) {
                        this.seat = this.available[0]
                    }
                    this.disableTimeSlot();
                    this.increaseDecreaseAnimate = true;
                },
                disableTimeSlot() {
                    var instance = this;
                    for (var i = 0; i < this.serviceTiming.length; i++) {
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
                    this.circleLoader = false;
                    this.confirmBook = 1;
                },
                slotTime(time) {
                    var instance = this;
                    var timeDuration = time.split(":");
                    if (timeDuration[0] != "00" && timeDuration[1] != 0) {
                        if (timeDuration[0] == "01" && timeDuration[1] == "01") {
                            return timeDuration[0] + " " + instance.trans('lang.hour') + " " + timeDuration[1] + " " + instance.trans('lang.minute');
                        }
                        if (timeDuration[0] == "01" && timeDuration[1] != "01") {
                            return timeDuration[0] + " " + instance.trans('lang.hour') + " " + timeDuration[1] + " " + instance.trans('lang.minutes');
                        }
                        if (timeDuration[0] != "01" && timeDuration[1] == "01") {
                            return timeDuration[0] + " " + instance.trans('lang.hours') + " " + timeDuration[1] + " " + instance.trans('lang.minute');
                        }

                    }
                    if (timeDuration[0] != "00" && timeDuration[1] == 0) {
                        if (timeDuration[0] == "01") {
                            return timeDuration[0] + " " + instance.trans('lang.hour');
                        } else {
                            return timeDuration[0] + " " + instance.trans('lang.hours');
                        }
                    }
                    if (timeDuration[0] == "00" && timeDuration[1] != 0) {
                        if (timeDuration[1] == "01") {
                            return timeDuration[1] + " " + instance.trans('lang.minute');
                        } else {
                            return timeDuration[1] + " " + instance.trans('lang.minutes');
                        }
                    }
                },
                setSelectedTimeSlot(time) //setting time slot into slot variable
                {
                    var flag = 0;
                    if (this.checkMultiBooking == 1) //for multiple booking
                    {
                        for (var i = 0; i < this.selectedTimeSlot.length; i++) {
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
                        this.renderTextFields();
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
                    this.$emit('setActive', 1);
                },
                setBookingLoader: function (e) {
                    this.$emit('setBookingLoader', e);
                },
                setRenderBookingForm: function (e) {
                    this.$emit('setRenderBookingForm', e);
                    this.enablePaypal = false;
                },

                setErrorInvalid() {
                    this.error = false;
                    this.errorInvalid.first_name = false;
                    this.errorInvalid.last_name = false;
                    this.errorInvalid.email = false;
                    this.errorInvalid.slot = false;
                    this.errorInvalid.seat = false;
                    this.errorInvalid.phone = false;
                },
                changeSlotError() {
                    this.errorInvalid.slot = false;
                },
                bill_in() {

                    if (this.checkMultiBooking == 1 && this.slot != '') {
                        return this.stripeBill = this.slot.length * this.seat * this.service_price;

                    }
                    if (this.checkMultiBooking == 0 && this.slot != '') {
                        return this.stripeBill = this.seat * this.service_price;
                    }
                },
                setError(value) {
                    let instance = this;
                    instance.$nextTick(function () {
                        instance.error = true;
                        instance.errorInvalid[value] = true;
                    });
                    instance.circleLoader = false;
                },
                checkUserInfo() {
                    let instance = this;

                    // SLot validation
                    if (instance.slot.length == 0) instance.setError('slot');
                    // Seat validation
                    if (!instance.avSeat) {
                        instance.errorMessage.seat = instance.trans('lang.limited_seat');
                        instance.setError('seat');
                    } else {
                        if (!instance.seat || instance.seat < 1) instance.setError('seat');
                    }

                    this.$validator.validateAll().then(result => {
                        if (result) {
                            if (instance.slot.length !== 0 && instance.avSeat && instance.seat > 0) {
                                this.changeConfirm(3);
                            }
                        }
                    });
                },
                book(bill, method, transaction_id = null) {
                    let instance = this;

                    let index;

                    let minSeat = 999999;

                    let i;
                    let emailFormat = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                    if (instance.first_name && instance.last_name && instance.email.match(emailFormat) && instance.slot.length !== 0 && instance.avSeat && instance.seat > 0) {
                        for (i = 0; i < this.slot.length; i++) {
                            instance.index = this.serviceTiming.indexOf(this.slot[i]);
                            if (this.available[instance.index] < minSeat) {
                                minSeat = this.available[instance.index];
                            }
                        }

                        if (this.seat > minSeat) {
                            this.avSeat = false
                        } else {
                            instance.setBookingLoader(false);
                            instance.bookingLoading = true;
                            instance.newServiceDate.forEach(function (element) {
                                instance.customFieldsOptions[element] = moment(instance.customFieldsOptions[element], instance.dateFormat.toUpperCase()).format('YYYY-MM-DD');
                            });

                            instance.inputFields = {
                                id: instance.service_selected,
                                first_name: instance.first_name,
                                last_name: instance.last_name,
                                email: instance.email,
                                phone_number: instance.phone,
                                comment: instance.comment,
                                seat: instance.seat,
                                slot: instance.slot,
                                booking_date: instance.dateForBooking,
                                service_list: instance.service_selected,
                                bill: bill,
                                method_id: method,
                                transaction_id: transaction_id,
                                customFields: instance.customFieldsOptions,
                                duration_type: instance.service.service_duration_type,
                            };

                            instance.axiosGETorPOST(
                                {
                                    url: '/bookservice',
                                    postData: instance.inputFields
                                },
                                (success, responseData) => {
                                    instance.setPreLoader(true);
                                    instance.postDataThenFunctionality(responseData);
                                },
                                (error) => {
                                    instance.setPreLoader(true);
                                }
                            );

                        }
                    } else {
                        instance.is_disabled = false;
                    }
                },


                getTiming() {
                    var instance = this;
                    instance.axiosGet('/servicetiming/' + this.service_selected + '/' + this.dateForBooking,
                        function (response) {
                            instance.serviceTiming = response.data.stack;
                            instance.available = response.data.seat;
                            instance.circleLoader = true;
                            instance.$nextTick(function () {
                                if (instance.service.service_duration_type === 'daily') {
                                    instance.slot = instance.serviceTiming;
                                    instance.circleLoader = false;
                                    instance.confirmBook = 1;
                                } else {
                                    $('#space').on('input', function () {
                                        let value = $(this).val();
                                        instance.seat = value;
                                        instance.disableTimeSlot();
                                    });
                                    instance.disableTimeSlot();
                                }
                                instance.renderTextFields();
                            });
                        },
                        function (response) {
                            instance.circleLoader = false;
                        })

                },
                renderTextFields() {
                    this.renderTextarea();
                },
                renderTextarea() {
                    this.$nextTick(function () {
                        $('#comment').trigger('autoresize');
                    })
                },
                renderBookingForm() {
                    var instance = this;
                    this.$nextTick(function () {
                        instance.bookingLoading = false;
                    });
                },
                is_disable() {
                    this.is_disabled = true;
                },
                postDataThenFunctionality(response) {
                    let instance = this;
                    instance.bookingProcessing = false;
                    instance.slot = [];
                    instance.seat = 0;
                    instance.serviceTiming = [];
                    instance.available = [];
                    instance.getTiming(instance.service_list);
                    instance.changeConfirm(1);
                    instance.is_disabled = false;
                },
                postDataCatchFunctionality(error) {
                    let instance = this;
                    instance.bookingProcessingFailed = true;
                    instance.bookingProcessing = false;
                    instance.changeConfirm(1);
                    instance.is_disabled = false;

                },
            }
    }
</script>
