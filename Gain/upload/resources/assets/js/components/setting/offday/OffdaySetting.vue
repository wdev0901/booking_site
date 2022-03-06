<template>
    <div class="main-layout-card">
        <div class="main-layout-card-header">
            <div class="main-layout-card-content-wrapper">
                <div class="main-layout-card-header-contents">
                    <h5 class="bluish-text no-margin">{{ trans('lang.off_day_settings') }}</h5>
                </div>
            </div>
        </div>
        <pre-loader v-if="!hidePreLoader"></pre-loader>
        <div class="main-layout-card-content" v-else>
            <div class="pl-3">
                <div class="row mb-3">
                    <h6>{{ trans('lang.choose_off_day')}}</h6>
                </div>
                <div class="row custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" id="1" value=1 v-model="offday_setting">
                    <label class="custom-control-label" for="1">{{ trans('lang.sunday') }}</label>
                </div>

                <div class="row custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" id="2" value=2 v-model="offday_setting">
                    <label class="custom-control-label" for="2">{{ trans('lang.monday') }}</label>
                </div>

                <div class="row custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" id="3" value=3 v-model="offday_setting">
                    <label class="custom-control-label" for="3">{{ trans('lang.tuesday') }}</label>
                </div>
                <div class="row custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" id="4" value=4 v-model="offday_setting">
                    <label class="custom-control-label" for="4">{{ trans('lang.wednesday') }}</label>
                </div>
                <div class="row custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" id="5" value=5 v-model="offday_setting">
                    <label class="custom-control-label" for="5">{{ trans('lang.thursday') }}</label>
                </div>
                <div class="row custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" id="6" value=6 v-model="offday_setting">
                    <label class="custom-control-label" for="6">{{ trans('lang.friday') }}</label>
                </div>
                <div class="row custom-control custom-checkbox mb-3">
                    <input class="custom-control-input" type="checkbox" id="7" value=7 v-model="offday_setting">
                    <label class="custom-control-label" for="7">{{ trans('lang.saturday') }}</label>
                </div>
                <div class="row">
                    <button class="btn btn-primary app-color mobile-btn" @click.prevent="save()" type="submit">{{
                        trans('lang.save') }}
                    </button>
                </div>
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
                offday_setting: [],
                hidePreLoader: true,
            }
        },
        created() {
            this.readData();
        },
        methods: {
            setPreloader: function (type, action) {
                this.preloaderType = type;
                this.hidePreloader = action;
            },
            readData() {
                let instance = this;
                instance.hidePreLoader = false;
                instance.axiosGet('/offdaysdata',
                    function (response) {
                        instance.hidePreLoader = true;
                        instance.offday_setting = response.data.offday_setting;
                    },
                    function (error) {
                        instance.hidePreLoader = true;
                    });
            },
            save() {
                let instance = this;
                instance.inputFields = {
                    offday_setting: instance.offday_setting,
                };
                instance.hidePreLoader = false;
                instance.axiosPost('/offdaysetting', instance.inputFields,
                    function (response) {
                        instance.hidePreLoader = true;
                    },
                    function (error) {
                        instance.errors = error.data.errors;
                        instance.hidePreLoader = true;
                    });
            },
        }
    }
</script>
