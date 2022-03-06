<template>
    <div>
        <div class="back-img"
             :style="{'background-image': 'url(' + publicPath+'/images/' + 'background/background-image.jpeg' + ')'}">
            <div class="container-fluid p-0">
                <div class="row">
                    <div class="col-12 col-md-6 offset-md-6 col-lg-4 offset-lg-8 col-xl-4 offset-xl-8">
                        <div class="sign-in-sign-up-content">
                            <form class="sign-in-sign-up-form">
                                <div class="text-center mb-4 application-logo">
                                    <img :src="publicPath+'/uploads/logo/'+appLogo" alt="" class="img-fluid logo">
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <h6 class="text-center mb-0">
                                            {{ trans('lang.hi_there') }}
                                        </h6>
                                        <label class="text-center d-block">{{ trans('lang.sign_up_for_your_new_account')
                                            }}</label>
                                    </div>
                                </div>
                                
                                <div class="alertBranch" v-if="alertMessage.length>0">
                                    <div class="alert alert-warning alertBranch" role="alert">
                                        {{alertMessage}}
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <label for="first_name">{{ trans('lang.first_name') }}</label>
                                        <input id="first_name"
                                               v-validate="'required'"
                                               type="text"
                                               v-model="first_name"
                                               :data-vv-as="trans('lang.first_name_small')"
                                               name="first_name"
                                               :placeholder="trans('lang.enter_first_name')"
                                               class="form-control">
                                        <div class="heightError">
                                            <small class="text-danger" v-show="errors.has('first_name')">
                                                {{ errors.first('first_name') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <label for="last_name">{{ trans('lang.last_name') }}</label>
                                        <input id="last_name"
                                               v-validate="'required'"
                                               type="text"
                                               v-model="last_name"
                                               :data-vv-as="trans('lang.last_name_small')"
                                               name="last_name"
                                               :placeholder="trans('lang.enter_last_name')"
                                               class="form-control">
                                        <div class="heightError">
                                            <small class="text-danger" v-show="errors.has('last_name')">
                                                {{ errors.first('last_name') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="isSmsSettingSet" class="form-row">
                                    <div class="form-group col-12">
                                        <label for="email">{{ trans('lang.login_email') }}</label>
                                        <input id="email"
                                               type="email"
                                               v-model="email"
                                               name="email"
                                               :placeholder="trans('lang.enter_email')"
                                               class="form-control">
                                    </div>
                                </div>
                                <div v-else class="form-row">
                                    <div class="form-group col-12">
                                        <label for="email">{{ trans('lang.login_email') }}</label>
                                        <input id="email"
                                               type="email"
                                               v-model="email"
                                               v-validate="'required'"
                                               name="email"
                                               :placeholder="trans('lang.enter_email')"
                                               class="form-control">
                                        <div class="heightError">
                                            <small class="text-danger" v-show="errors.has('email')">
                                                {{ errors.first('email') }}
                                            </small>
                                        </div>
                                    </div>

                                </div>

                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <label for="email">{{ trans('lang.phone') }}</label>
                                        <vue-tel-input id="phone"
                                                       type="text"
                                                       v-model="phone"
                                                       mode="international"
                                                       name="phone"
                                                       @input="getCountry"
                                                       :placeholder="trans('lang.enter_phone')"
                                                       class="form-control"></vue-tel-input>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <label for="password">{{ trans('lang.login_password') }}</label>
                                        <input id="password"
                                               ref="password"
                                               v-validate="'required'"
                                               v-model="password"
                                               name="password"
                                               type="password"
                                               :placeholder="trans('lang.enter_password')"
                                               class="form-control">
                                        <div class="heightError">
                                            <small class="text-danger" v-show="errors.has('password')">
                                                {{ errors.first('password') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <label for="conf-password">{{ trans('lang.confirm_password') }}</label>
                                        <input id="conf-password"
                                               v-model="password_confirmation"
                                               v-validate="'required|confirmed:password'"
                                               :data-vv-as="trans('lang.confirm_password_small')"
                                               name="password_confirmation"
                                               type="password"
                                               :placeholder="trans('lang.confirm_password')"
                                               class="form-control">
                                        <div class="heightError">
                                            <small class="text-danger" v-show="errors.has('password_confirmation')">
                                                {{ errors.first('password_confirmation') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row loginButton">
                                    <div class="form-group col-12">
                                        <common-submit-button class="btn-block text-center auth-button"
                                                              :buttonLoader="buttonLoader"
                                                              :isDisabled="isDisabled"
                                                              :isActiveText="isActiveText"
                                                              buttonText="sign_up"
                                                              @submit="registersPost"/>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-6 ">
                                        <a @click="homePage"
                                           href="#"
                                           class="bluish-text">
                                            <i class="las la-home"/>
                                            {{ trans('lang.back_to_home')}}
                                        </a>
                                    </div>
                                    <div class="form-group col-6 text-right">
                                        <a href="#"
                                           @click="login"
                                           class="bluish-text">
                                            <i class="las la-lock"/>
                                            {{ trans('lang.login_here') }}
                                        </a>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-12">
                                        <p class="text-center mt-5">
                                            {{ copyright_text }}
                                        </p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="sms-verification-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <sms-code-verification-modal :phone="phone" :phone_object="phoneObject" :token="token" class="modal-content"/>
            </div>
        </div>
    </div>
</template>

<script>
    import axiosGetPost from '../../helper/axiosGetPostCommon';
    import {required, minLength, sameAs} from "vuelidate/lib/validators";
    import {VueTelInput} from 'vue-tel-input'
    import SmsCodeVerificationModal from "./SmsCodeVerificationModal";

    export default {
        extends: axiosGetPost,
        props: ['emailadd', 'token', 'copyright_text'],
        components: {SmsCodeVerificationModal, VueTelInput},
        data() {
            return {
                first_name: '',
                last_name: '',
                email: '',
                phone: '',
                password: '',
                password_confirmation: '',
                showPreloader: '',
                alertMessage: '',
                hidePreLoader: false,
                buttonLoader: false,
                isActiveText: false,
                isDisabled: false,
                submitted: false,
                isSmsModalActive: false,
                phoneObject:{},
                isSmsSettingSet:'',
            }
        },
        computed: {
            phoneInput() {
                if (this.phone === '') {
                    return true;
                }
                return false;
            },
            emailInput() {
                if (this.email === '')
                    return true; // cellphone is required
                return false;
            }
        },
        created(){
            this.getSmsSettingStatus('/get-sms-setting-status');
        }
        ,
        mounted() {

            let instance = this;

            if (this.emailadd) {
                this.email = this.emailadd;
            } else {
                $('#email').removeAttr('readonly');
            }

            $('#sms-verification-modal').on('show.bs.modal', function (e) {
                instance.isSmsModalActive = true;
            });
            $('#sms-verification-modal').on('hidden.bs.modal', function (e) {
                instance.isSmsModalActive = false;
            });
        },
        methods: {
            getCountry(number, isValid, country){
                this.phoneObject = isValid.number.significant;
            },
            checkFocus(id) {
                return $(id).is(":focus")
            },
            setPreloader(value) {
                this.hidePreLoader = value;
            },
            registersPost() {
                let instance = this;

                this.submitted = true;

                this.$validator.validateAll().then((result) => {
                    if (result) {
                        this.inputFields = {
                            first_name: this.first_name,
                            last_name: this.last_name,
                            password: this.password,
                            password_confirmation: this.password_confirmation,
                        };
                        instance.buttonLoader = true;
                        instance.isDisabled = true;
                        instance.isActiveText = true;

                        if (instance.emailadd && instance.token) {
                            instance.setPreloader(true);
                            instance.axiosPost('/register/' + instance.token, {
                                    first_name: this.first_name,
                                    last_name: this.last_name,
                                    email: this.email,
                                    phone: this.phone,
                                    phone_object: this.phoneObject,
                                    password: this.password,
                                    password_confirmation: this.password_confirmation,
                                },
                                function (response) {
                                    if (response) {
                                        instance.alertMessage = response.data.message;
                                    }
                                    instance.login();
                                    instance.setPreloader(false);
                                },
                                function (error) {
                                    instance.errors = error.data.errors;
                                    instance.setPreloader(false);
                                });

                        } else {
                            instance.setPreloader(true);
                            instance.loginAxiosPost('/registerclient', {
                                    first_name: this.first_name,
                                    last_name: this.last_name,
                                    email: this.email,
                                    phone: this.phone,
                                    phone_object: this.phoneObject,
                                    password: this.password,
                                    password_confirmation: this.password_confirmation,
                                },
                                function (response) {
                                    if (response.data.message) {
                                        instance.alertMessage = response.data.message;
                                    }

                                    instance.setPreloader(false);
                                    instance.buttonLoader = false;
                                    instance.isDisabled = false;

                                    if (instance.email == "" && instance.phone){
                                        //phone registration
                                        instance.isSmsModalActive = true;
                                        $('#sms-verification-modal').modal('show');
                                    } else {
                                        //email registration
                                        instance.login();
                                    }
                                },
                                function (error) {
                                      instance.alertMessage = error.data.message;
                                      instance.setPreloader(false);
                                });
                        }
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
            getSmsSettingStatus(route){
                let instance = this;
                instance.axiosGet(route,
                    function (response) {
                        instance.isSmsSettingSet = response.data.isSmsSettingSet;
                    },
                    function (errror) {

                    }
                );
            }
        },
    }
</script>