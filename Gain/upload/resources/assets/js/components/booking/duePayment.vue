<template>
    <div>
        <div class="modal-layout-header">
            <div class="container-fluid p-0">
                <div class="row">
                    <div class="col-10">
                        <h4 class="m-0">{{ trans('lang.due_payment') }}</h4>
                    </div>
                    <div class="col-2 text-right">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click.prevent="">
                            <span aria-hidden="true"><i class="la la-close"></i></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-layout-content">
            <pre-loader v-if="!hidePreLoader"></pre-loader>
            <form v-else>
                <div class="form-row justify-content-center">
                    <div class="form-group col-12 col-md-6">
                        <p>{{ trans('lang.booking_id') }}: #{{ paymentDetails.id }}</p>
                        <p v-if="paymentDetails.status == 'confirmed'">
                            {{ trans('lang.status') }}:
                            <span class="booking-status bg-success ml-1">
                                {{ trans('lang.'+paymentDetails.status+'_')}}
                            </span>
                        </p>
                        <p v-if="paymentDetails.status == 'pending'">
                            {{ trans('lang.status') }}:
                            <span class="booking-status bg-warning ml-1">
                                {{ trans('lang.'+paymentDetails.status)}}
                            </span>
                        </p>
                        <p>
                            {{ trans('lang.bill') }}: {{ paymentDetails.booking_bill}}
                        </p>
                        <p class="mb-0">
                            {{ trans('lang.due') }}: {{ paymentDetails.payment_stat}}
                        </p>
                    </div>
                </div>
                <div class="form-row justify-content-center">
                    <div class="form-group col-12 col-md-6">
                        <label for="payment_methods">
                            {{ trans('lang.payment_methods') }}
                        </label>
                        <select id="payment_methods" class="custom-select">
                            <option value="" disabled selected>
                                {{ trans('lang.choose_one') }}
                            </option>
                            <option v-for="paymentMethod in paymentMethods" :value="paymentMethod.id">
                                {{paymentMethod.title}}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="form-row justify-content-center">
                    <div v-if="decimalSeparator == '.'" class="form-group col-12 col-md-6">
                        <label for="price">{{ trans('lang.payment_amount') }}</label>
                        <input v-model="dueClearAmount"
                               class="form-control"
                               name="price"
                               id="price"
                               type="text"
                               onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57 || event.charCode == 46">
                    </div>
                    <div v-if="decimalSeparator == ','" class="form-group col-12 col-md-6">
                        <label for="priceComma">{{ trans('lang.payment_amount') }}</label>
                        <input v-model="dueClearAmount"
                               class="form-control"
                               name="price"
                               id="priceComma"
                               type="text"
                               onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57 || event.charCode == 44">
                    </div>
                </div>
                <div class="form-row justify-content-center">
                    <div class="col-12 col-md-6">
                        <button class="btn btn-primary btn-block app-color"
                                @click.prevent="clearDueBill()"
                                :disabled="selectedPaymentMethodId == '' || dueClearAmount <= 0">
                            {{ trans('lang.pay') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import axiosGetPost from '../../helper/axiosGetPostCommon';

    export default {
        extends: axiosGetPost,
        props: ['booking_id'],
        data() {
            return {
                dueClearAmount: 1,
                hidePreloader: '',
                paymentDetails: '',
                paymentMethods: '',
                selectedPaymentMethodId: '',

            }
        },
        created() {
            this.readData();
        },
        methods: {
            readData() {
                let instance = this;
                this.setPreLoader(false);
                instance.axiosGet('/payment/payment-details/' + instance.booking_id,
                    function (response) {
                        instance.paymentDetails = response.data.paymentDetails;
                        instance.paymentMethods = response.data.paymentMethods;
                        instance.dueClearAmount = accounting.unformat(instance.paymentDetails.payment_stat, instance.decimalSeparator);
                        setTimeout(function () {
                            $('#payment_methods').on('change', function () {
                                let value = $(this).val();
                                instance.selectedPaymentMethodId = value;
                            });
                        });
                        instance.setPreLoader(true);

                    }, function (response) {
                        instance.setPreLoader(true);
                    });
            },
            clearDueBill() {
                let instance = this;
                this.setPreLoader(false);
                if (this.dueClearAmount > 0 && this.selectedPaymentMethodId) {
                    this.axiosGETorPOST({
                            url: '/payment/payment-details',
                            postData: {
                                booking_id: this.booking_id,
                                paid_amount: this.dueClearAmount,
                                method_id: this.selectedPaymentMethodId,
                            }
                        },
                        function (success, responseData) {
                            instance.showSuccessAlert(responseData.message);
                            $('#due-payment-modal').modal('hide');
                            instance.$hub.$emit('reloadDataTable');
                            instance.$hub.$emit('getNewData');

                        },
                        function (error) {
                            instance.setPreLoader(true);
                        });
                }
            },
        },
    }
</script>