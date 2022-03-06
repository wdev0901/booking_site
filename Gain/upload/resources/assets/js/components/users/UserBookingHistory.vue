<template>
    <div class="main-layout-wrapper">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent m-0">
                <li class="breadcrumb-item">
                    <span>{{trans('lang.booking_history')}}</span>
                </li>
            </ol>
        </nav>
        <div class="container-fluid p-0">
            <div class="row mr-0">
                <div class="col-12 col-md-4 col-lg-4 col-xl-3 pr-0">
                    <div class="main-layout-card mb-3">
                        <div class="main-layout-card-header">
                            <div class="main-layout-card-content-wrapper">
                                <div class="main-layout-card-header-contents">
                                    <div class="text-center profile-image-container">
                                        <img class="responsive-img profile-image materialboxed"
                                             :src="publicPath+'/uploads/profile/'+user.avatar"
                                             alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="main-layout-card-content">
                            <div class="text-center user-name-text">
                                <h5 class="">{{user.first_name+' '+user.last_name }}</h5>
                                <h6>
                                    <i class="la la-envelope"/>
                                    {{ user.email }}
                                </h6>
                                <h6 v-if="user.phone !=null">
                                    <i class="la la-phone"/>
                                    {{ user.phone }}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-8 col-lg-8 col-xl-9 pr-0">
                    <div class="main-layout-card">
                        <div class="main-layout-card-header">
                            <div class="main-layout-card-content-wrapper">
                                <div class="main-layout-card-header-contents">
                                    <h5 class="">{{ trans('lang.booking_history') }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="main-layout-card-content">
                            <datatable-component :options="tableOptions"/>
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
                            source: '/booking-details',
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
                                    class: "booking-status bg-danger"
                                };
                                else if (rowData == 'canceled') return {
                                    value: instance.trans('lang.canceled'),
                                    class: "booking-status bg-warning"
                                };
                            }
                        },
                        {title: 'lang.book_date', key: 'booking_date', type: 'text', sortable: true},
                        {title: 'lang.time', key: 'booking_time', type: 'array', sortable: false},
                        {title: 'lang.quantity', key: 'quantity', type: 'text', sortable: true},
                        {title: 'lang.bill', key: 'booking_bill', type: 'text', sortable: true},
                    ],
                    source: '/user-bookings/' + this.user.id,
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