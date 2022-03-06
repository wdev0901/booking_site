<template>
    <div class="main-layout-card">
        <div class="main-layout-card-header">
            <div class="main-layout-card-content-wrapper">
                <div class="main-layout-card-header-contents">
                    <h5 class="bluish-text no-margin">{{ trans('lang.client_settings') }}</h5>
                </div>
            </div>
        </div>
        <pre-loader v-if="!hidePreLoader"></pre-loader>
        <div class="main-layout-card-content ml-3" v-else>
            <div class="row custom-control custom-checkbox mb-3">
                <input class="custom-control-input" type="checkbox" id="client-setting-1" v-model="can_signup"
                       @change="checkSelected(can_signup,false,false)"/>
                <label class="custom-control-label" for="client-setting-1">{{ trans('lang.client_can_signup') }}</label>
            </div>
            <div v-if="can_signup" class="row custom-control custom-checkbox mb-3">
                <input class="custom-control-input" type="checkbox" id="client-setting-3" v-model="can_login"
                       @change="checkSelected(can_signup,can_login,false)"/>
                <label class="custom-control-label" for="client-setting-3">{{ trans('lang.client_can_login') }}</label>
            </div>
            <div v-if="can_signup && can_login" class="row custom-control custom-checkbox mb-3">
                <input class="custom-control-input" type="checkbox" id="client-setting-4"
                       v-model="submit_booking_after_login"
                       @change="checkSelected(can_signup,can_login,submit_booking_after_login)"/>
                <label class="custom-control-label" for="client-setting-4">{{
                    trans('lang.client_can_submit_booking_when_login') }}</label>
            </div>
            <div class="row">
                <button class="btn btn-primary app-color mobile-btn" type="submit" @click.prevent="save()">{{
                    trans('lang.save') }}
                </button>
            </div>
        </div>
    </div>
</template>
<script>

    import axiosGetPost from '../../../helper/axiosGetPostCommon';

    export default {
        extends: axiosGetPost,
        data() {
            return {
                can_signup: 0,
                can_login: 0,
                submit_booking_after_login: 0,
                settingValue: {},
                hidePreLoader: true,
            }
        },
        mounted() {
            this.getData();
        },
        methods: {
            checkSelected(can_signup, can_login, submit_booking_after_login) {
                this.can_signup = can_signup;
                this.can_login = can_login;
                this.submit_booking_after_login = submit_booking_after_login;
            },
            setPreloader: function (type, action) {
                this.preloaderType = type;
                this.hidePreloader = action;

            },
            getData() {
                let instance = this;
                instance.hidePreLoader = false;
                this.axiosGet('/clientsetting',
                    function (response) {
                        instance.settingValue = response.data,
                            instance.can_signup = instance.settingValue.can_signup,
                            instance.can_login = instance.settingValue.can_login,
                            instance.submit_booking_after_login = instance.settingValue.submit_booking_after_login
                        instance.hidePreLoader = true;
                    },
                    function (response) {
                        instance.hidePreLoader = true;
                    },
                );
            },
            save() {
                let instance = this;
                instance.inputFields = {
                    can_signup: instance.can_signup,
                    can_login: instance.can_login,
                    submit_booking_after_login: instance.submit_booking_after_login,
                };
                instance.hidePreLoader = false;
                instance.axiosPost('/clientsetting', instance.inputFields,
                    function (response) {
                        instance.hidePreLoader = true;
                    },
                    function (error) {
                        instance.errors = error.data.errors;
                        instance.hidePreLoader = true;
                    });
            }
        }
    }
</script>