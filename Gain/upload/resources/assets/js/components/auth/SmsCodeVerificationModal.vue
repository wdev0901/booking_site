<template>
    <div>
        <div class="modal-layout-header">
            <div class="container-fluid p-0">
                <div class="row">
                    <div class="col-10">
                        <h4 class="m-0">SMS Verification Modal</h4>
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
            <div class="form-group">
                <label for="phone">{{ trans('lang.phone') }}</label>
                <input id="phone"
                       v-model="phone"
                       type="text"
                       name="phone"
                       class="form-control"
                       :disabled="true"
                       v-validate="'required|max:6|min:6'"
                />
            </div>
            <div class="form-group">
                <label for="verification_code">{{ trans('lang.verification_code') }}</label>
                <input id="verification_code"
                       v-model="verification_code"
                       type="text"
                       name="verification_code"
                       class="form-control"
                       :placeholder="trans('lang.enter_verification_code')"/>
            </div>
            <div class="form-group">
                <a href="#"
                   @click="resendVerificationCode"
                   class="bluish-text">
                    <i class="las la-sms"/>
                    {{ trans('lang.resend_code') }}
                </a>
            </div>
            <div class="form-group mb-0">
                <common-submit-button class="btn-block text-center auth-button"
                                      :buttonLoader="buttonLoader"
                                      :isDisabled="isDisabled"
                                      :isActiveText="isActiveText"
                                      buttonText="verify"
                                      v-on:submit="verify"/>
            </div>
        </div>
    </div>
</template>
<script>
    import axiosGetPost from '../../helper/axiosGetPostCommon';

    export default {
        props: ['phone','phone_object','token'],
        extends: axiosGetPost,
        data() {
            return {
                verification_code:'',
                buttonLoader: false,
                isDisabled: false,
                isActiveText: false,
            }
        },
        methods: {

            verify(){
                let instance = this;
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        instance.axiosPost('/sms-confirmation/' + instance.token, {
                                phone: this.phone,
                                verification_code: this.verification_code,
                            },
                            function (response) {
                                instance.login();
                                instance.setPreloader(false);
                            },
                            function (error) {
                                instance.errors = error.data.errors;
                                instance.setPreloader(false);
                            });
                    }
                });
            },
            login() {
                let instance = this;
                instance.redirect('/login');
            },
            homePage() {
                let instance = this;
                instance.redirect('/');
            },
            resendVerificationCode(){
                console.log("called");
                let instance = this;
                instance.axiosPost('/resend-sms-confirmation/', {
                        phone: this.phone,
                        phone_object: this.phone_object,
                    },
                    function (response) {

                    },
                    function (error) {

                    });

            }
        },
    }
</script>

