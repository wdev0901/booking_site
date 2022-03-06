<template>
    <div>
        <div class="main-layout-wrapper">
            <div class="container-fluid p-0">
                <div class="row">
                    <div class="col-12 col-md-4 col-lg-4 col-xl-3 pr-md-0">
                        <div class="main-layout-card">
                            <div class="main-layout-card-header-with-button">
                                <div class="text-center">
                                    <img class="img-fluid profile-image avatar"
                                         :src="publicPath+'/uploads/profile/'+user.avatar" alt="Customer Image">
                                </div>
                            </div>
                            <div class="main-layout-card-content pt-4.5">
                                <div class="text-center user-name-text">
                                    <div class="text-center">
                                        <div class="contact-person-info">
                                            <h4 class="cursor-text">
                                                <i class="fa fa-check"></i> {{ user.first_name }} {{ user.last_name }}
                                            </h4>
                                            <h6 class="cursor-text">
                                                <i class="la la-envelope"></i> {{ user.email }}
                                            </h6>
                                            <h6 class="cursor-text">
                                                <i class="la la-phone"></i> {{ user.phone }}
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-8 col-lg-8 col-xl-9">
                        <div class="main-layout-card">
                            <div class="main-layout-card-header">
                                <div class="main-layout-card-content-wrapper">
                                    <div class="main-layout-card-header-contents">
                                        <h4 class="m-0">{{ trans('lang.booking_history') }}</h4>
                                    </div>
                                </div>
                            </div>
                            <datatable-component class="main-layout-card-content"
                                                 :options="tableOptions"></datatable-component>
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
        props: ['user', 'totalbooking'],
        data() {
            let instance = this;
            return {
                hasData: value => {
                    return !_.isEmpty(value) ? true : false
                },
                hidePreloader: '',
                preloaderType: 'load',
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
                                    class: "booking-status bg-secondary"
                                };
                            }
                        },
                        {title: 'lang.book_date', key: 'booking_date', type: 'text', sortable: true},
                        {title: 'lang.time', key: 'booking_time', type: 'array', sortable: false},
                        {title: 'lang.quantity', key: 'quantity', type: 'text', sortable: true},
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
                    ],
                    source: '/client-bookings/' + this.user.id,
                    sortingOrder: 'DESC',
                    search: false,
                    formatting: ['booking_bill'],
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
                    ]
                }
            }
        },
        methods: {
            bookingDetails() {
                let instance = this;
                instance.$hub.$on('bookingDetails', function (id) {
                    this.axiosGet('/booking-details/' + id,
                        function (response) {
                        },
                        function (response) {
                        },
                    );
                    instance.redirect("/booking-details/" + id);
                })
            },
        }
    }
</script>