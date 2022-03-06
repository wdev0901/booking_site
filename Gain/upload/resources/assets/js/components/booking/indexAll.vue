<template>
    <div class="main-layout-wrapper">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent m-0">
                <li class="breadcrumb-item">
                    <span>{{ trans('lang.bookings') }}</span>
                </li>
            </ol>
        </nav>
        <div class="main-layout-card">
            <div class="main-layout-card-header-with-button">
                <div class="main-layout-card-content-wrapper">
                    <div class="main-layout-card-header-contents">
                        <h5 class="bluish-text m-0">{{ trans('lang.bookings') }}</h5>
                    </div>
                    <div class="main-layout-card-header-contents text-right d-flex justify-content-end">
                        <div class="p-1">
                            <button class="btn btn-primary app-color"
                                    data-toggle="modal"
                                    data-target="#booking-modal"
                                    @click="isActive = 1">
                                {{ trans('lang.add') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <datatable-component class="main-layout-card-content" :options="tableOptions"></datatable-component>
        </div>

        <div class="modal fade" id="booking-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered big-modal-dialog" role="document">
                <adminbooking-form class="modal-content"
                                   @resetAdminFormModal="resetAdminFormModal"
                                   :modalID="modalID"
                                   :id="selectedItemId"
                                   v-if="isActive == 1 && bookper==1">
                </adminbooking-form>
            </div>
        </div>

        <!--Due Payment Modal-->
        <div class="modal fade" id="due-payment-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered big-modal-dialog" role="document">
                <due-payment class="modal-content"
                             v-if="isDuePaymentModalActive"
                             :booking_id="duePaymentBookingId">
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

        <!--Delete Booking Modal-->
        <confirmation-modal id="confirm-delete"
                            :message="'booking_will_be_deleted_permanently'"
                            :firstButtonName="'yes'"
                            :secondButtonName="'no'"
                            @confirmationModalButtonAction="confirmationModalDeleteButtonAction">
        </confirmation-modal>

    </div>
</template>

<script>
    import axiosGetPost from '../../helper/axiosGetPostCommon';

    export default {
        extends: axiosGetPost,
        props: ['bookper', 'clientsetting'],
        data() {
            let instance = this;
            return {
                isActive: 0,
                items: [],
                status: 0,
                duration: 0,
                from: 0,
                to: 0,
                booking: [],
                book: '',
                id: '',
                duePaymentBookingId: '',
                isDuePaymentModalActive: false,
                cancelBookingId: '',
                modalID: '#booking-modal',
                hasData: value => {
                    return !_.isEmpty(value) ? true : false
                },
                tableOptions: {
                    tableName: 'booking',
                    columns: [
                        {
                            title: 'lang.id',
                            key: 'id',
                            type: 'clickable_link',
                            source: 'booking-details',
                            uniquefield: 'id',
                            sortable: true
                        },
                        {title: 'lang.service', key: 'title', type: 'text', sortable: true},
                        {
                            title: 'lang.status', key: 'status', type: 'custom',
                            modifier: function (rowData) {

                                if (rowData == 'confirmed') return {
                                    value: instance.trans('lang.confirmed_'),
                                    class: "booking-status bg-success"
                                };
                                else if (rowData == 'pending') return {
                                    value: instance.trans('lang.pending'),
                                    class: "booking-status bg-warning"
                                };
                                else if (rowData == 'canceled') return {
                                    value: instance.trans('lang.canceled'),
                                    class: "booking-status bg-disable"
                                };
                            }
                        },

                        {title: 'lang.book_date', key: 'booking_date', type: 'text', sortable: true},
                        {title: 'lang.time', key: 'booking_time', type: 'array', sortable: false},
                        {title: 'lang.quantity', key: 'quantity', type: 'text', sortable: true},
                        (instance.clientsetting == 1 ? {
                                title: 'lang.client',
                                key: 'full_name',
                                type: 'clickable_link',
                                source: 'client',
                                uniquefield: 'user_id',
                                sortable: true
                            } :
                            {title: 'lang.client', key: 'full_name', type: 'text', sortable: true}),
                        {title: 'lang.bill', key: 'booking_bill', type: 'text', sortable: true},
                        {
                            title: 'lang.payment_status', key: 'payment_status', type: 'custom', sortable: false,
                            modifier: function (rowData) {
                                if (rowData === 'paid') return {
                                    value: instance.trans('lang.paid'),
                                    class: "booking-status bg-success"
                                };
                                else return {
                                    value: instance.trans('lang.due'),
                                    class: "booking-status bg-danger"
                                };
                            }

                        },
                        {
                            title: 'lang.booking_invoice',
                            key: 'booking_invoice',
                            type: 'component',
                            componentName: 'booking-invoice-component'
                        },
                        {
                            title: 'lang.action',
                            key: 'action',
                            type: 'component',
                            componentName: 'booking-action-component'
                        }
                    ],
                    source: '/bookingshow',
                    sortingOrder: 'DESC',
                    formatting: ['booking_bill'],
                    dateFormatting: ['booking_date'],
                    customField: true,
                    positionAfter: 'payment_status',
                    search: true,
                    right_align: ['quantity', 'booking_bill', 'profit_amount', 'action', 'booking_invoice'],
                    filters: [
                        {title: 'lang.date_range', key: 'date_range', type: 'date_range'},
                        {
                            title: 'lang.status', key: 'status', type: 'dropdown', options: [
                                {text: 'lang.all', value: 0, selected: true},
                                {text: 'lang.confirmed_', value: 1},
                                {text: 'lang.pending', value: 2},
                                {text: 'lang.canceled', value: 3}
                            ]
                        },
                        {
                            title: 'lang.payment_status', key: 'payment_status', type: 'dropdown', options: [
                                {text: 'lang.all', value: 0, selected: true},
                                {text: 'lang.due', value: 1},
                                {text: 'lang.paid', value: 2},
                            ]
                        }
                    ]
                },
            }
        },
        mounted() {
            let instance = this;

            $('#booking-modal').on('hidden.bs.modal', function (e) {
                instance.isActive = 0;
                instance.selectedItemId = '';
            });

            this.bookingDetails();

            $('#due-payment-modal').on('show.bs.modal', function (e) {
                instance.isDuePaymentModalActive = true;
            });
            $('#due-payment-modal').on('hidden.bs.modal', function (e) {
                instance.isDuePaymentModalActive = false;
            });

            instance.$hub.$on('confirmBookingFromDatatable', function (id) {
                instance.confirmBooking(id)
            });

            instance.$hub.$on('cancelBookingFromDatatable', function (id) {
                instance.cancelBookingId = id;
            });

            instance.$hub.$on('duePayment', function (id) {
                instance.duePayment(id);
            });

            instance.$hub.$on('selectedDeletableId', function (id, index) {
                instance.deleteID = id;
                instance.deleteIndex = index;
            });

            instance.$hub.$on('viewEdit', function (id) {
                instance.addEditAction(id);
            });
        },
        methods: {
            bookingDetails() {
                let instance = this;
                instance.$hub.$on('bookingDetails', function (id) {
                    instance.redirect("/booking-details/" + id);
                })
            },
            duePayment(id) {
                this.duePaymentBookingId = id;
            },
            confirmationModalButtonAction() {
                this.cancelBooking(this.cancelBookingId)
            },
            confirmationModalDeleteButtonAction() {
                this.deleteBooking(this.deleteID);
            },
            confirmBooking(id) {
                let instance = this;
                this.deleteActionPreLoader(false);
                instance.axiosPost('/actionbooking/' + id, {
                        status: 'confirmed'
                    },
                    function (response) {
                        instance.deleteActionPreLoader(true);
                        instance.$hub.$emit('reloadDataTable');
                    },
                    function (error) {
                        instance.deleteActionPreLoader(true);
                        instance.errors = error.data.errors;
                    }
                );
            },
            cancelBooking(id) {
                let instance = this;
                $('#confirm-cancel-booking').modal('hide');
                this.deleteActionPreLoader(false);
                instance.axiosPost('/actionbooking/' + id, {
                        status: 'canceled'
                    },
                    function (response) {
                        instance.deleteActionPreLoader(true);
                        instance.$hub.$emit('reloadDataTable');
                    },
                    function (error) {
                        instance.deleteActionPreLoader(true);
                        instance.errors = error.data.errors;
                    });
            },
            deleteBooking(id) {
                let instance = this;
                $('#confirm-delete').modal('hide');
                this.deleteActionPreLoader(false);
                instance.axiosPost('/deletebooking/' + id, {},
                    function (response) {
                        instance.deleteActionPreLoader(true);
                        instance.$hub.$emit('reloadDataTable');
                    },
                    function (error) {
                        instance.deleteActionPreLoader(true);
                    });
            },
            resetAdminFormModal() {
                this.isActive = 0;
            }
        },
    }
</script>