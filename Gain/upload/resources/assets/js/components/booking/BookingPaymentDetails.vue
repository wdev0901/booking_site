<template>
    <div>
        <div class="modal-layout-header">
            <div class="container-fluid p-0">
                <div class="row">
                    <div class="col-10">
                        <h4 class="m-0">{{ trans('lang.payment_details') }}</h4>
                    </div>
                    <div class="col-2 text-right">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                @click.prevent="">
                            <span aria-hidden="true"><i class="la la-close"></i></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-layout-content">
            <pre-loader v-if="hidePreloader != 'hide'"/>
            <div v-else>
                <div class="row mb-3">
                    <div class="col-6">{{ trans('lang.booking_id') }}</div>
                    <div class="col-6">#{{ payment.booking_id }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-6"> {{ trans('lang.amount') }}</div>
                    <div class="col-6">{{ numberFormat(payment.paid_amount) }}</div>
                </div>
                <div class="row mb-3" v-if="payment.payment_method">
                    <div class="col-6"> {{ trans('lang.payment_method') }}</div>
                    <div class="col-6">{{ payment.payment_method }}</div>
                </div>
                <div class="row mb-3" v-if="payment.first_name">
                    <div class="col-6"> {{ trans('lang.received_by') }}</div>
                    <div class="col-6">{{ payment.first_name +' '+ payment.last_name }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-6"> {{ trans('lang.created_at') }}</div>
                    <div class="col-6">{{ payment.created }}</div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import axiosGetPost from '../../helper/axiosGetPostCommon';

    export default {
        extends: axiosGetPost,
        props: ['payment_method_id'],
        data() {
            return {
                payment: {},
                hidePreloader: '',
            }
        },
        created() {
            this.getPaymentDetails();
        },
        methods: {
            getPaymentDetails() {
                let instance = this;
                instance.axiosGet('/booking-payment/' + instance.payment_method_id,
                    function (response) {
                        instance.payment = response.data;
                        instance.hidePreloader = 'hide';
                    }, function (response) {

                    }
                );
            }
        }
    }
</script>