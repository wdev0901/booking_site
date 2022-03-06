<template>
    <div>
        <div class="modal-layout-header">
            <div class="row">
                <div class="col-10">
                    <h5 class="bluish-text no-margin">{{ trans('lang.setting_service') }}</h5>
                </div>
                <div class="col-2">
                    <a href="#"
                       class="modal-close"
                       @click.prevent="setActive(1,'')">
                        <i class="la la-close"/>
                    </a>
                </div>
            </div>
        </div>
        <pre-loader v-if="hidePreloader!='hide'"/>
        <div class="modal-layout-content" v-show="hidePreloader=='hide'">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label class="d-block">{{ trans('lang.auto_confirm') }}</label>
                    <div class="form-check-inline">
                        <label class="form-check-label">
                            <input type="radio"
                                   class="form-check-input"
                                   name="confirmation"
                                   v-model="item.auto_confirm"
                                   value="1">{{ trans('lang.yes') }}
                        </label>
                    </div>
                    <div class="form-check-inline">
                        <label class="form-check-label">
                            <input type="radio"
                                   class="form-check-input"
                                   name="confirmation"
                                   v-model="item.auto_confirm"
                                   value="0">{{ trans('lang.no') }}
                        </label>
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label class="d-block">{{ trans('lang.activation') }}</label>
                    <div class="form-check-inline">
                        <label class="form-check-label">
                            <input type="radio"
                                   class="form-check-input"
                                   v-model="item.activation"
                                   value="1"
                                   name="activation">{{ trans('lang.yes') }}
                        </label>
                    </div>
                    <div class="form-check-inline">
                        <label class="form-check-label">
                            <input type="radio"
                                   class="form-check-input"
                                   v-model="item.activation"
                                   value="0"
                                   name="activation">{{ trans('lang.no') }}
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="url" :class="{'active':error}">{{ trans('lang.url_alias') }}</label>
                    <input v-model="item.alias"
                           name="url"
                           id="url"
                           type="text"
                           class="form-control validate"
                           :class="{'invalid': errorInvalid.url}"
                           @input="disableError">
                    <span class="helper-text" :data-error="trans('lang.required_input_field')"/>
                </div>
                <div class="form-group col-md-6">

                </div>
            </div>
            <div class="row">
                <div class="input-field col s19 m6 l4">
                    <input v-model="item.allow_booking_max_day_ago" name="max_day_ago" id="max_day_ago" type="text"
                           onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57">
                    <label for="max_day_ago" :data-error="trans('lang.required_input_field')">{{
                        trans('lang.allow_booking_max_day_ago') }}</label>
                </div>
                <div class="col s3 m3 l3 no-padding">
                    <div class="days-ago-parent">
                        <small class="grey-text days-ago-child">{{ trans('lang.days_ago')}}</small>
                    </div>
                </div>
                <div class="col s12">
                    <button class="btn waves-effect waves-light bluish-back mob-btn"
                            @click.prevent="update(serviceId),is_disable()" :disabled="is_disabled" type="submit">{{
                        trans('lang.save') }}
                    </button>
                    <button class="btn cancel-btn waves-effect waves-grey mob-btn modal-close"
                            @click.prevent="setActive(1,'')">{{ trans('lang.cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axiosGetPost from '../../helper/axiosGetPostCommon';

    export default {
        extends: axiosGetPost,
        props: ['serviceId'],
        data() {
            return {
                allow_cancel: '',
                auto_confirm: '',
                activation: '',
                is_disabled: false,
                allow_booking_max_day_ago: 0,
                item: [],
                preloaderType: '',
                hidePreloader: '',
                error: false,
                errorInvalid: {
                    url: false,
                }
            }
        },
        mounted() {
            this.readData();
        },
        methods: {
            setActive: function (e, f) {
                this.$emit('setActive', e, f);
            },
            setPreloader: function (type, action) {
                this.preloaderType = type;
                this.hidePreloader = action;
            },
            setError(value) {
                let instance = this;
                instance.$nextTick(function () {
                    instance.error = true;
                    instance.errorInvalid[value] = true;
                });
                instance.setPreloader('load', 'hide');
            },
            disableError() {
                if (this.item.alias !== '') {
                    this.is_disabled = false;
                }
            },
            readData() {
                let instance = this;
                instance.setPreloader('load', '');
                this.axiosGet('/serviceid/' + this.serviceId,
                    function (response) {
                        instance.item = _.clone(response.data);
                        instance.$nextTick(function () {
                            M.updateTextFields();
                        });
                        instance.setPreloader('load', 'hide');
                    },
                    function (response) {
                    },
                );
            },
            update(id) {
                let instance = this;
                instance.setPreloader('load', '');
                if (instance.item.alias === '') {
                    instance.setError('url');
                } else {
                    instance.axiosPost('/serviceSetting/' + id, {
                            allow_cancel: instance.allow_cancel,
                            auto_confirm: this.item.auto_confirm,
                            activation: this.item.activation,
                            allow_booking_max_day_ago: this.item.allow_booking_max_day_ago,
                            alias: this.item.alias,
                        },
                        function (response) {
                            instance.setPreloader('load', 'hide');
                            M.toast({html: response.data.msg});
                            instance.setActive(1, 'save');
                            instance.is_disabled = false;
                        },
                        function (error) {
                            instance.setPreloader('load', 'hide');
                            M.toast({html: instance.trans('lang.getting_problems'), classes: 'red lighten-1'});
                            instance.is_disabled = false;
                        });
                }


            },
            is_disable() {
                this.is_disabled = true;
            }
        },
    }
</script>