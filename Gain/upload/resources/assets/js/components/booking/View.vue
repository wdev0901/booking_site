<template>
    <div class="main-layout-wrapper">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent m-0">
                <li class="breadcrumb-item">
                    <span>{{ trans('lang.booking_details') }}</span>
                </li>
            </ol>
        </nav>
        <div class="">
            <div class="row">
                <div class="col-12 col-md-8">
                    <div class="main-layout-card">
                        <div class="main-layout-card-header">
                            <div class="main-layout-card-content-wrapper">
                                <div class="main-layout-card-header-contents">
                                    <h5 class="">
                                        {{ trans('lang.booking_details') + ' #'+ bookingDetails.id }}
                                    </h5>
                                </div>
                                <div class="main-layout-card-header-contents float-right">
                                    <a href="#" @click="bookingsclick">
                                        {{ trans('lang.all_bookings') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="main-layout-card-content">
                            <pre-loader v-if="hidePreloader !== 'hide'"></pre-loader>
                            <div v-else>
                                <div class="booking-details-view">
                                    {{ trans('lang.service') }}
                                </div>
                                <div class="booking-details-view">
                                    {{ bookingDetails.title }}
                                </div>

                                <div class="booking-details-view">
                                    {{ trans('lang.status') }}
                                </div>
                                <div class="booking-details-view" v-if="bookingDetails.status === 'confirmed'">
                                    <span class="booking-status bg-success">
                                        {{ trans('lang.confirmed_') }}
                                    </span>
                                </div>
                                <div class="booking-details-view" v-if="bookingDetails.status === 'pending'">
                                    <span class="booking-status bg-warning">
                                        {{ trans('lang.pending') }}
                                    </span>
                                </div>
                                <div class="booking-details-view" v-if="bookingDetails.status === 'canceled'">
                                    <span class="booking-status bg-disable">
                                        {{ trans('lang.canceled') }}
                                    </span>
                                </div>

                                <div class="booking-details-view">
                                    {{ trans('lang.booking_date') }}
                                </div>
                                <div class="booking-details-view">
                                    {{ bookingDetails.booking_date }}
                                </div>

                                <div class="booking-details-view"
                                     v-if="bookingDetails.service_duration_type === 'hourly'">
                                    {{ trans('lang.booking_time') }}
                                </div>
                                <div class="booking-details-view"
                                     v-if="bookingDetails.service_duration_type === 'hourly'">
                                    <div v-for="timeSlot in bookingDetails.booking_time">
                                        {{timeSlot}}
                                    </div>
                                </div>

                                <div class="booking-details-view">
                                    {{ trans('lang.quantity') }}
                                </div>
                                <div class="booking-details-view">
                                    {{ bookingDetails.quantity }}
                                </div>

                                <div class="booking-details-view">
                                    {{ trans('lang.bill') }}
                                </div>
                                <div class="booking-details-view">
                                    {{ bookingDetails.booking_bill }}
                                </div>

                                <div class="booking-details-view">
                                    {{ trans('lang.payment_status') }}
                                </div>
                                <div class="booking-details-view" v-if="bookingDetails.payment_stat <= 0">
                                    <div class="icon-wrapper">
                                        <div class="icon-wrapper-button">
                                            <span class="booking-status bg-success">
                                                {{ trans('lang.paid') }}
                                            </span>
                                        </div>
                                        <div class="icon-wrapper-icon">
                                            <a class=""
                                               href="#"
                                               data-toggle="tooltip"
                                               data-placement="right"
                                               :title="trans('lang.payment_details')"
                                               @click.prevent="openPaymentDetailsModal">
                                                <i class="la la-info-circle la-2x ml-2"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="booking-details-view" v-else>
                                    <span class="booking-status red">{{ trans('lang.due') }}</span>
                                </div>

                                <div class="booking-details-view" v-if="bookingDetails.comment">
                                    {{ trans('lang.comment') }}
                                </div>
                                <div class="booking-details-view" v-if="bookingDetails.comment">
                                    {{ bookingDetails.comment }}
                                </div>

                                <template v-for="fields in custom">
                                    <div class="booking-details-view">{{fields.label}}</div>
                                    <div class="booking-details-view">{{fields.value}}</div>
                                </template>

                                <div class="booking-details-view">
                                    {{ trans('lang.created_at') }}
                                </div>
                                <div class="booking-details-view">
                                    {{ dateFormatBooking(bookingDetails.created_at) }}
                                </div>

                                <div class="booking-details-view-buttons">
                                    <button v-if="bookingDetails.status === 'pending'"
                                            class="btn btn-success"
                                            type="submit"
                                            @click.prevent="confirm(bookingDetails.id)">
                                        {{ trans('lang.confirm') }}
                                    </button>
                                    <button v-if="bookingDetails.status === 'pending' && bookingDetails.payment_stat > 0"
                                            class="btn btn-danger"
                                            data-toggle="modal"
                                            data-target="#confirm-cancel-booking"
                                            @click.prevent="">
                                        {{ trans('lang.cancel') }}
                                    </button>
                                    <button v-if="bookingDetails.status != 'canceled' && bookingDetails.payment_stat > 0"
                                            class="btn btn-info"
                                            @click.prevent="openDuePaymentModal">
                                        {{ trans('lang.pay') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4 pl-md-0">
                    <div class="main-layout-card">
                        <div class="main-layout-card-header">
                            <div class="main-layout-card-content-wrapper">
                                <div class="main-layout-card-header-contents">
                                    <div class="text-center profile-image-container">
                                        <img class="img-fluid profile-image"
                                             :src="publicPath+'/uploads/profile/'+booking.avatar"
                                             alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="main-layout-card-content">
                            <div class="text-center user-name-text">
                                <h5 class="bluish-text">
                                    <a href="#" @click.prevent="userDetails(booking.user_id)">
                                        {{booking.first_name }} {{ booking.last_name ? booking.last_name : ''}}
                                    </a>
                                </h5>
                                <h6>
                                    <i class="la la-envelope"></i>
                                    {{ booking.email }}
                                </h6>
                                <h6 v-if="bookingDetails.phone_number !=null">
                                    <i class="la la-phone"></i>
                                    {{ bookingDetails.phone_number }}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--Payment Modal-->
            <div class="modal fade" id="payment-modal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered big-modal-dialog" role="document">
                    <booking-payment-modal class="modal-content"
                                           v-if="isPaymentDetailsModalActive"
                                           :payment_method_id="bookingDetails.payment_id">
                    </booking-payment-modal>
                </div>
            </div>

            <!--Due Payment Modal-->
            <div class="modal fade" id="due-payment-modal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered big-modal-dialog" role="document">
                    <due-payment class="modal-content"
                                 v-if="isDuePaymentModalActive"
                                 :booking_id="bookingDetails.id">
                    </due-payment>
                </div>
            </div>

            <!--Cancel Booking Modal-->
            <confirmation-modal id="confirm-cancel-booking"
                                :message="'booking_will_be_canceled'"
                                :firstButtonName="'yes'"
                                :secondButtonName="'no'"
                                @confirmationModalButtonAction="confirmationModalButtonAction">
            </confirmation-modal>
        </div>
    </div>
</template>

<script>

    import axiosGetPost from '../../helper/axiosGetPostCommon';

    export default {
        extends: axiosGetPost,
        props: ['booking', 'custom'],
        data() {
            return {
                id: '',
                isActive: 0,
                paymentId: '',
                bookingDetails: '',
                hidePreloader: 'hide',
                isPaymentDetailsModalActive: false,
                isDuePaymentModalActive: false,
            }
        },
        mounted() {
            let instance = this;

            if (this.booking) {
                this.bookingDetails = this.booking;
            }
            this.$hub.$on('getNewData', function () {
                instance.getNewData();
            });

            $('#due-payment-modal').on('hidden.bs.modal', function (e) {
                instance.isDuePaymentModalActive = false;
            });

            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            });
        },
        methods: {
            confirmationModalButtonAction() {
                this.cancel(this.bookingDetails.id);
            },
            userDetails(id) {
                let instance = this;
                instance.axiosGet('/client/' + id,
                    function (response) {
                        users: this.booking.user_id;
                    }, function (response) {
                    });
                this.redirect("/client/" + id);
            },
            localTime(date_time) {
                return moment(date_time + ' Z', 'YYYY-MM-DD HH:mm:ss Z', true).format(this.dateTimeFormat);
            },
            confirm(id) {
                let instance = this;
                this.hidePreloader = '';
                instance.axiosPost('/actionbooking/' + id, {
                        status: 'confirmed'
                    },
                    function (response) {
                        instance.hidePreloader = 'hide';
                        instance.getNewData();
                    },
                    function (error) {
                        instance.errors = error.data.errors;
                        instance.hidePreloader = 'hide';
                    });
            },
            cancel(id) {
                let instance = this;
                this.hidePreloader = '';
                $('#confirm-cancel-booking').modal('hide');
                instance.axiosPost('/actionbooking/' + id, {
                        status: 'canceled'
                    },
                    function (response) {
                        instance.getNewData();
                    },
                    function (error) {
                        instance.errors = error.data.errors
                        instance.hidePreloader = 'hide';
                    });
            },
            getNewData() {
                let instance = this;
                this.hidePreloader = '';
                instance.axiosGet('/booking-details/' + this.bookingDetails.id + '/true',
                    function (response) {
                        instance.bookingDetails = response.data;
                        instance.hidePreloader = 'hide';
                    }, function (response) {
                        instance.hidePreloader = 'hide';
                    })
            },
            bookingsclick() {
                let instance = this;
                instance.redirect('/bookings');
            },
            openPaymentDetailsModal() {
                this.isPaymentDetailsModalActive = true;
                $('#payment-modal').modal('show');
            },
            openDuePaymentModal() {
                this.isDuePaymentModalActive = true;
                $('#due-payment-modal').modal('show');
            },
        }
    }
</script>