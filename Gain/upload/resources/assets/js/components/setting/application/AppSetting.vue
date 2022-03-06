<template>
    <div class="main-layout-card">
        <div class="main-layout-card-header">
            <div class="main-layout-card-content-wrapper">
                <div class="main-layout-card-header-contents">
                    <h5 class="bluish-text m-0">
                        {{ trans('lang.application_settings') }}
                    </h5>
                </div>
            </div>
        </div>
        <div class="main-layout-card-content">
            <pre-loader v-if="hidePreloader"/>
            <form v-else>
                <div class="mb-4">
                    <h6 class="app-heading-color">
                        {{ trans('lang.general_settings') }}
                    </h6>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="appname">{{ trans('lang.app_name') }}</label>
                            <input type="text" class="form-control" id="appname" v-model="item.app_name">
                        </div>
                        <div class="form-group col-md-6">
                            <label>{{ trans('lang.change_app_logo') }}</label>
                            <div class="custom-file">
                                <input type="file"
                                       class="custom-file-input"
                                       id="app-logo"
                                       accept="image/*"
                                       @change="onImageChange">
                                <label class="custom-file-label text-truncate" for="app-logo">
                                    {{ trans('lang.placeholder_choose_app_logo') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="rows-per-table">{{ trans('lang.rows_per_table') }}</label>
                            <select id="rows-per-table" v-model="item.max_row_per_table" class="custom-select">
                                <option disabled selected>{{ trans('lang.choose_one') }}</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="500">500</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>{{ trans('lang.change_background_image') }}</label>
                            <div class="custom-file">
                                <input type="file"
                                       class="custom-file-input"
                                       id="background-image"
                                       accept="image/*"
                                       @change="backgroundImageChange">
                                <label class="custom-file-label text-truncate" for="background-image">
                                    {{ trans('lang.placeholder_choose_background_image') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <!--Business type chooosen code maybe implemented in future-->
                    <!--<div class="row">
                        <div class="form-group col-md-6">
                            <label for="business_type">{{ trans('lang.business_type_setting') }}</label>
                            <select id="business_type" v-model="item.business_type" class="custom-select">
                                <option disabled selected>{{ trans('lang.choose_business_type') }}</option>
                                <option value="general">{{ trans('lang.general') }}</option>
                                <option value="salon">{{ trans('lang.salon') }}</option>
                            </select>
                        </div>
                    </div>-->
                </div>

                <div class="mb-4">
                    <h6 class="app-heading-color">
                        {{ trans('lang.landing_page_settings') }}
                    </h6>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="landing_page_header">{{ trans('lang.landing_page_header') }}</label>
                            <input type="text"
                                   id="landing_page_header"
                                   class="form-control"
                                   v-model="item.landing_page_header">
                        </div>
                        <div class="form-group  col-md-6">
                            <label for="landing_page_message">
                                {{ trans('lang.landing_page_message') }}
                                <span class="text-secondary font-italic">{{ trans('lang.max_landing_page_message') }}</span>
                            </label>
                            <textarea id="landing_page_message"
                                      class="form-control"
                                      v-model="item.landing_page_message"
                                      rows="1"/>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="copyright_text">
                                {{ trans('lang.copyright_text') }}
                            </label>
                            <input type="text"
                                   v-model="item.copyright_text"
                                   id="copyright_text"
                                   class="form-control">
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="app-heading-color">
                        {{ trans('lang.time_and_date_settings') }}
                    </h6>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="time_zone">{{ trans('lang.time_zone') }}</label>
                            <select id="time_zone" v-model="item.time_zone" class="custom-select">
                                <option value="" disabled>{{ trans('lang.choose_one') }}</option>
                                <option v-for="timezone in timezones" :value="timezone.name">
                                    {{ timezone.name }}
                                </option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="dateformat">{{ trans('lang.date-format') }}</label>
                            <select id="dateformat" v-model="item.date_format" class="custom-select">
                                <option value="" disabled>{{ trans('lang.choose_one') }}</option>
                                <option value="d/m/Y">{{ trans('lang.dd/mm/yyyy') }}</option>
                                <option value="m/d/Y">{{ trans('lang.mm/dd/yyyy') }}</option>
                                <option value="Y/m/d">{{ trans('lang.yyyy/mm/dd') }}</option>
                                <option value="d-m-Y">{{ trans('lang.dd-mm-yyyy') }}</option>
                                <option value="m-d-Y">{{ trans('lang.mm-dd-yyyy') }}</option>
                                <option value="Y-m-d">{{ trans('lang.yyyy-mm-dd') }}</option>
                                <option value="d.m.Y">{{ trans('lang.dd_mm_yyyy') }}</option>
                                <option value="m.d.Y">{{ trans('lang.mm_dd_yyyy') }}</option>
                                <option value="Y.m.d">{{ trans('lang.yyyy_mm_dd') }}</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="d-block"> {{ trans('lang.time_format') }}</label>
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio"
                                           class="form-check-input"
                                           name="time-format"
                                           v-model="item.time_format"
                                           value="24h">{{ trans('lang.24h') }}
                                </label>
                            </div>
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio"
                                           class="form-check-input"
                                           name="time-format"
                                           v-model="item.time_format"
                                           value="12h">{{ trans('lang.12h') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="app-heading-color">
                        {{ trans('lang.language_settings') }}
                    </h6>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="language">{{ trans('lang.preferred_language') }}</label>
                            <select id="language" class="custom-select" v-model="item.language_setting">
                                <option value="" disabled selected>{{ trans('lang.choose_one') }}</option>
                                <option
                                        v-for="language in languages"
                                        :value="language">
                                    {{ language }}
                                </option>
                            </select>
                        </div>

                        <div class="form-group  col-md-6">
                            <label class="col-12 col-form-label"/>
                            <a href="#"
                               class="btn btn-primary app-color mobile-btn mt-1"
                               @click.prevent="languageCacheClear()">
                                {{ trans('lang.clear_language_cache') }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="app-heading-color">
                        {{ trans('lang.currency_settings') }}
                    </h6>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="currency-symbol">{{ trans('lang.currency_symbol') }}</label>
                            <input type="text"
                                   class="form-control"
                                   id="currency-symbol"
                                   v-model="item.currency_symbol"
                                   value="$">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="currency-position">{{ trans('lang.currency_position') }}</label>
                            <select id="currency-position" v-model="item.currency_format" class="custom-select">
                                <option value="" disabled selected>{{ trans('lang.choose_one') }}</option>
                                <option value="left">$0.00</option>
                                <option value="right">0.00$</option>
                                <option value="left-space">$ 0.00</option>
                                <option value="right-space">0.00 $</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="currency_code">{{ trans('lang.currency_code') }}
                                <i class="la la-info-circle setting-info-icon"
                                   data-toggle="tooltip"
                                   data-placement="top"
                                   :title="trans('lang.please_use_valid_currency_code')"
                                   :data-original-title="trans('lang.please_use_valid_currency_code')"
                                   rel="tooltip"/>
                            </label>
                            <input type="text"
                                   id="currency_code"
                                   class="form-control"
                                   v-model="item.currency_code">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="decimal-separator">{{ trans('lang.decimal_separator') }}</label>
                            <select id="decimal-separator"
                                    name="thousand-separator"
                                    class="custom-select"
                                    v-model="item.decimal_separator">
                                <option value="" disabled selected>{{ trans('lang.choose_one') }}</option>
                                <option value=".">100.11</option>
                                <option value=",">100,11</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="thousand-separator">{{ trans('lang.thousand_separator') }}</label>
                            <select id="thousand-separator"
                                    name="thousand-separator"
                                    class="custom-select"
                                    v-model="item.thousand_separator">
                                <option value="" disabled selected>{{ trans('lang.choose_one') }}</option>
                                <option value="space">1 00 000</option>
                                <option value=",">1,00,000</option>
                                <option value=".">1.00.000</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="d-block">{{ trans('lang.number_of_decimal') }}</label>
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio"
                                           class="form-check-input"
                                           v-model="item.number_of_decimal"
                                           value="0">0
                                </label>
                            </div>
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio"
                                           class="form-check-input"
                                           v-model="item.number_of_decimal"
                                           value="2">2
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="app-heading-color">
                        {{ trans('lang.invoice_settings') }}
                    </h6>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="company_name">{{ trans('lang.company_name') }}</label>
                            <input type="text"
                                   class="form-control"
                                   id="company_name"
                                   v-model="item.company_name">
                        </div>
                        <div class="form-group col-md-6">
                            <label>{{ trans('lang.change_invoice_logo') }}</label>
                            <div class="custom-file">
                                <input type="file"
                                       class="custom-file-input"
                                       id="invoice-logo"
                                       accept="image/*"
                                       @change="onInvoiceChange">
                                <label class="custom-file-label text-truncate" for="invoice-logo">
                                    {{ trans('lang.image_only') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="">
                    <a href="#"
                       class="btn app-color mobile-btn"
                       @click.prevent="is_disable(), update()"
                       :disabled="is_disabled">
                        {{ trans('lang.save') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import axiosGetPost from '../../../helper/axiosGetPostCommon';

    export default {
        extends: axiosGetPost,
        props: ['timezones'],
        data() {
            return {
                item: {
                    time_format: '',
                    date_format: '',
                    time_zone: '',
                    currency_symbol: '',
                    currency_format: '',
                    thousand_separator: '',
                    language_setting: '',
                    choose_currency: '',
                    decimal_separator: '',
                    number_of_decimal: '',
                    max_row_per_table: '',
                    app_name: '',
                    app_logo: '',
                    invoice_logo: '',
                    company_name: '',
                    copyright_text: '',
                    landing_page_message: '',
                    landing_page_header: '',
                },
                app_logo: '',
                invoice_logo: '',
                backgroundImage: '',
                backgroundImageName: '',
                is_disabled: false,
                tzlist: [],
                langdir: [],
                hidePreloader: '',
                preloaderType: false,
                languages: [],
            }
        },
        created() {
            this.getBasicSettingData();
        },
        methods: {
            getBasicSettingData() {
                let instance = this;
                instance.hidePreloader = true;

                this.axiosGet('/basicsettingdata',
                    function (response) {
                        instance.item = response.data.basicData;
                        instance.languages = response.data.language;
                        instance.hidePreloader = false;

                        setTimeout(function () {
                            $('[data-toggle="tooltip"]').tooltip();
                        });
                    },
                    function (response) {
                        instance.hidePreloader = false;
                    },
                );
            },
            onImageChange(event) {

                let fileName = event.target.files[0].name;
                $('#app-logo').next('.custom-file-label').html(fileName);

                var input = event.target;
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = (e) => {
                        this.item.app_logo = e.target.result;
                    }
                    this.app_logo = input.files[0].name;
                    reader.readAsDataURL(input.files[0]);
                } else {
                    this.item.app_logo = '';
                }
            },
            onInvoiceChange(event) {

                let fileName = event.target.files[0].name;
                $('#invoice-logo').next('.custom-file-label').html(fileName);

                var input = event.target;
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = (e) => {
                        this.item.invoice_logo = e.target.result;
                    }
                    this.invoice_logo = input.files[0].name;
                    reader.readAsDataURL(input.files[0]);
                } else {
                    this.item.invoice_logo = '';
                }
            },
            backgroundImageChange(event) {

                let fileName = event.target.files[0].name;
                $('#background-image').next('.custom-file-label').html(fileName);

                var input = event.target;
                if (input.files && input.files[0]) {

                    var reader = new FileReader();
                    reader.onload = (e) => {
                        this.backgroundImage = e.target.result;
                    }
                    this.backgroundImageName = input.files[0].name;
                    reader.readAsDataURL(input.files[0]);
                } else {
                    this.backgroundImage = '';
                }
            },
            setPreloader: function (type, action) {
                //this.$emit('setPreloader', e,f);
                this.preloaderType = type;
                this.hidePreloader = action;

            },
            languageCacheClear() {
                let instance = this;
                instance.hidePreloader = true;
                this.axiosGet('/clear-cache',
                    function (response) {
                        instance.hidePreloader = false;
                        instance.is_disabled = false;
                    },
                    function (error) {
                        instance.hidePreloader = false;
                    },
                );

            },
            sepChange() {
                let instance = this;
                if (instance.item.decimal_separator == ",") {
                    instance.item.thousand_separator = ".";
                }
                if (instance.item.decimal_separator == ".") {
                    instance.item.thousand_separator = ",";
                }
            },
            update() {
                let instance = this;
                instance.hidePreloader = true;
                instance.axiosPost('/basic-setting', {
                        choose_currency: this.item.choose_currency,
                        currency_code: this.item.currency_code,
                        time_format: this.item.time_format,
                        date_format: this.item.date_format,
                        currency_symbol: this.item.currency_symbol,
                        currency_format: this.item.currency_format,
                        thousand_separator: this.item.thousand_separator,
                        decimal_separator: this.item.decimal_separator,
                        number_of_decimal: this.item.number_of_decimal,
                        language_setting: this.item.language_setting,
                        max_row_per_table: this.item.max_row_per_table,
                        app_name: this.item.app_name,
                        app_logo: this.item.app_logo,
                        invoice_logo: this.item.invoice_logo,
                        company_name: this.item.company_name,
                        copyright_text: this.item.copyright_text,
                        background_image: this.backgroundImage,
                        landing_page_message: this.item.landing_page_message,
                        landing_page_header: this.item.landing_page_header,
                        time_zone: this.item.time_zone,
                        business_type: this.item.business_type,
                    },
                    function (response) {
                        window.location.reload();
                        instance.hidePreloader = true;
                        instance.is_disabled = false;
                        instance.app_logo = '';
                        instance.backgroundImageName = '';
                    },
                    function (error) {
                        instance.errors = error.data.errors;
                        instance.hidePreloader = true;
                        instance.is_disabled = false;
                    }
                );
            },
            is_disable() {
                this.is_disabled = true;
            }
        }
    }
</script>