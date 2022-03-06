<template>
    <div>
        <div class="modal-layout-header">
            <div class="container-fluid p-0">
                <div class="row">
                    <div class="col-10">
                        <h4 class="m-0" v-if="id">{{ trans('lang.edit_payment_method') }}</h4>
                        <h4 class="m-0" v-else>{{ trans('lang.add_payment_method') }}</h4>
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
            <pre-loader v-if="!hidePreLoader" class="small-loader-container"></pre-loader>
            <form v-else>
                <div class="form-row">
                    <span v-if="alertMessage.length>0" class="alertBranch">
                        <div class="alert alert-warning alertBranch" role="alert">
                            {{alertMessage}}
                        </div>
                    </span>
                    <div class="form-group col-md-12">
                        <label for="type-name">{{ trans('lang.name') }}</label>
                        <input class="form-control" v-validate="'required'" id="type-name" name="title"
                               data-vv-as="name" type="text" v-model="title">
                        <div class="heightError">
                            <small class="text-danger" v-show="errors.has('title')">{{
                                errors.first('title') }}
                            </small>
                        </div>
                    </div>
                    <div class="form-group col-12">
                        <label class="d-block" for="type-name">{{ trans('lang.status') }}</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="enable" id="enable" value="1"
                                   v-model="status">
                            <label class="form-check-label" for="enable">{{ trans('lang.enable') }}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="disable" id="disable" value="0"
                                   v-model="status">
                            <label class="form-check-label" for="disable">{{ trans('lang.disable') }}</label>
                        </div>
                    </div>
                    <div class="form-group col-12">
                        <label class="d-block" for="type-name">{{ trans('lang.available_to_client') }}</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="yes" id="yes" value="1"
                                   v-model="available_to_client">
                            <label class="form-check-label" for="yes">{{ trans('lang.yes') }}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="no" id="no" value="0"
                                   v-model="available_to_client">
                            <label class="form-check-label" for="no">{{ trans('lang.no') }}</label>
                        </div>
                    </div>
                </div>
                <div class="form-row" v-if="type === 'paypal' || type === 'stripe'">
                    <div class="form-group col-12" v-if="type === 'stripe'">
                        <label for="type-name">{{ trans('lang.publishablekey') }}</label>
                        <input class="form-control" v-validate="'required'" id="publickey" name="publickey"
                               data-vv-as="publishable key" type="text" v-model="publickey">
                        <div class="heightError">
                            <small class="text-danger" v-show="errors.has('publickey')">{{
                                errors.first('publickey') }}
                            </small>
                        </div>
                    </div>
                    <div class="form-group col-12" v-if="type=='paypal'">
                        <label for="type-name">{{ trans('lang.client_id') }}</label>
                        <input class="form-control" v-validate="'required'" id="client_id" name="client_id"
                               data-vv-as="client id" type="text" v-model="client_id">
                        <div class="heightError">
                            <small class="text-danger" v-show="errors.has('client_id')">{{
                                errors.first('client_id') }}
                            </small>
                        </div>
                    </div>
                    <div class="form-group col-12">
                        <label for="type-name">{{ trans('lang.secret_key') }}</label>
                        <input class="form-control" v-validate="'required'" id="secret_key" name="secret_key"
                               data-vv-as="secret key" type="password" v-model="secret_key">
                        <div class="heightError">
                            <small class="text-danger" v-show="errors.has('secret_key')">{{
                                errors.first('secret_key') }}
                            </small>
                        </div>
                    </div>
                    <div class="form-group col-12" v-if="type=='paypal'">
                        <label class="d-block" for="type-name">{{ trans('lang.mode') }}</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="live" id="live" value="1"
                                   v-model="mode_paypal">
                            <label class="form-check-label" for="live">{{ trans('lang.live') }}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="sandbox" id="sandbox" value="0"
                                   v-model="mode_paypal">
                            <label class="form-check-label" for="sandbox">{{ trans('lang.sandbox') }}</label>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-12 mb-0">
                        <button class="btn app-color mobile-btn" type="submit" @click.prevent="save()">{{
                            trans('lang.save')
                            }}
                        </button>
                        <button class="btn cancel-btn mobile-btn" data-dismiss="modal" @click.prevent="">{{
                            trans('lang.cancel') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import axiosGetPost from '../../../helper/axiosGetPostCommon';

    export default {

        props: ['id', 'modalID'],
        extends: axiosGetPost,
        data() {
            return {
                title: '',
                status: 1,
                type: '',
                available_to_client: 0,
                client_id: '',
                secret_key: '',
                publickey: '',
                alertMessage: '',
                round: '',
                mode_paypal: 1,
            }
        },
        created() {
            if (this.id) this.getPaymentData('/payments/' + this.id);
        },
        methods: {
            save() {
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        this.inputFields = {
                            title: this.title,
                            enable: this.status,
                            client_id: this.client_id,
                            secret_key: this.secret_key,
                            available_to_client: this.available_to_client,
                            publickey: this.publickey,
                            mode_paypal: this.mode_paypal,

                        };
                        if (this.id) {
                            this.postDataMethod('/payments/' + this.id, this.inputFields);
                        }
                        else {
                            this.postDataMethod('/payment/store', this.inputFields);
                        }
                    }
                });
            },
            getPaymentData(route) {
                let instance = this;
                this.setPreLoader(false);
                this.axiosGet(route,
                    function (response) {

                        instance.title = response.data.title;
                        instance.status = response.data.enable;
                        instance.available_to_client = response.data.available_to_client;
                        instance.type = response.data.type;
                        instance.secret_key = response.data.option[0];

                        if (instance.type === 'stripe') {
                            instance.publickey = response.data.option[1];
                        } else if (instance.type === 'paypal') {
                            instance.client_id = response.data.option[1];
                            if (response.data.option[2] !== undefined) {
                                instance.mode_paypal = response.data.option[2];
                            }
                        }

                        instance.setPreLoader(true);
                    },
                    function (response) {
                        instance.setPreLoader(true);
                    },
                );
            },
            postDataThenFunctionality(response) {
                $(this.modalID).modal('hide');
                this.$hub.$emit('reloadDataTable');
            },
            postDataCatchFunctionality(error) {
                this.showErrorAlert(error.data.message);
            },
        },
    }
</script>