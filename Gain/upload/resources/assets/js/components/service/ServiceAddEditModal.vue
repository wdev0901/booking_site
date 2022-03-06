<template>
    <div>
        <div class="modal-layout-header">
            <div class="container-fluid p-0">
                <div class="row">
                    <div class="col-10">
                        <h4 class="m-0" v-if="id">{{ trans('lang.update_service') }}</h4>
                        <h4 class="m-0" v-else>{{ trans('lang.add_service') }}</h4>
                    </div>
                    <div class="col-2 text-right">
                        <button type="button"
                                class="close"
                                data-dismiss="modal"
                                aria-label="Close"
                                @click.prevent>
                            <span aria-hidden="true">
                                <i class="la la-close"/>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <pre-loader v-if="!hidePreLoader"/>
        <div v-else class="modal-layout-content service-nav-tab">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active"
                       data-toggle="tab"
                       href="#details">
                        {{ trans('lang.details') }}
                    </a>
                </li>
                <li v-if="businessType == 'salon'" class="nav-item">
                    <a class="nav-link"
                       :class="{'disabled' : isNavDisable}"
                       data-toggle="tab"
                       href="#gallery"
                       @click="checkValidation(true)">
                        {{ trans('lang.gallery') }}
                    </a>
                </li>
                <li v-if="businessType == 'salon'" class="nav-item d-none">
                    <a class="nav-link"
                       :class="{'disabled' : isNavDisable}"
                       data-toggle="tab"
                       href="#contact"
                       @click="checkValidation(true)">
                        {{ trans('lang.assistant') }}
                    </a>
                </li>
            </ul>

            <form enctype="multipart/form-data">
                <div class="tab-content service-tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="details">
                        <div class="row mt-3">
                            <div class="form-group col-12">
                                <label for="title">{{ trans('lang.title') }}</label>
                                <input v-model="title"
                                       v-validate="'required'"
                                       name="title"
                                       id="title"
                                       type="text"
                                       class="form-control"/>
                                <div v-show="isSubmitted && errors.has('title')" class="heightError">
                                    <small class="text-danger">
                                        {{ errors.first('title')}}
                                    </small>
                                </div>
                            </div>
                            <div class="form-group col-12">
                                <label for="description">{{ trans('lang.description') }}</label>
                                <textarea id="description"
                                          v-model="description"
                                          class="form-control service-description"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12 col-md-6" v-if="businessType == 'general'">
                                <label class="d-block">{{ trans('lang.service_duration_type') }}</label>
                                <div class="form-check-inline">
                                    <label class="form-check-label">
                                        <input type="radio"
                                               class="form-check-input"
                                               :checked="service_duration_type=='hourly'"
                                               @click="setService('hourly')">{{ trans('lang.hourly') }}
                                    </label>
                                </div>
                                <div class="form-check-inline">
                                    <label class="form-check-label">
                                        <input type="radio"
                                               class="form-check-input"
                                               :checked="service_duration_type == 'daily'"
                                               @click="setService('daily')">{{ trans('lang.daily') }}
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6">
                                <label class="d-block">{{ trans('lang.multiple_bookings') }}</label>
                                <div class="form-check-inline">
                                    <label class="form-check-label">
                                        <input type="radio"
                                               class="form-check-input"
                                               v-model="multiple_bookings"
                                               :disabled="service_duration_type == 'daily'"
                                               value="1">{{ trans('lang.yes') }}
                                    </label>
                                </div>
                                <div class="form-check-inline">
                                    <label class="form-check-label">
                                        <input type="radio"
                                               class="form-check-input"
                                               :disabled="service_duration_type == 'daily'"
                                               v-model="multiple_bookings"
                                               value="0">{{ trans('lang.no') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6" :class="{'col-sm-12': (businessType != 'general')}">
                                <label for="service_starting_date">{{ trans('lang.service_starting_date') }}</label>
                                <datepicker id="service_starting_date"
                                            v-model="service_starting_date"
                                            :disabledDates="state.disabledDates"
                                            :format="datePickerDateFormatter(service_starting_date)"
                                            :bootstrap-styling="true">
                                </datepicker>
                                <div v-show="isSubmitted && isServiceStartingDateNotSet"
                                     class="heightError">
                                    <small class="text-danger">
                                        {{ trans('lang.service_starting_date_field_is_required') }}
                                    </small>
                                </div>
                            </div>
                            <div class="form-group col-md-6" :class="{'col-sm-12': (businessType != 'general')}">
                                <label for="service_ending_date">{{ trans('lang.service_ending_date') }}</label>
                                <datepicker id="service_ending_date"
                                            v-model="service_ending_date"
                                            :disabledDates="state.disabledDates"
                                            :format="datePickerDateFormatter(service_ending_date)"
                                            :bootstrap-styling="true">
                                </datepicker>
                                <div v-show="isSubmitted && !serviceStartingEndingDateProperlySet"
                                     class="heightError">
                                    <small class="text-danger">
                                        {{ trans('lang.ending_date_should_not_be_before_than_starting_date') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="available_seat">{{ trans('lang.available_seat') }}</label>
                                <input v-model="available_seat"
                                       name="availableSpace"
                                       id="available_seat"
                                       type="text"
                                       v-validate="'required'"
                                       class="form-control"
                                       onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57"/>
                                <div v-show="isSubmitted && errors.has('availableSpace')" class="heightError">
                                    <small class="text-danger">
                                        {{trans('lang.available_space_space_field_is_required')}}
                                    </small>
                                </div>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="price">{{trans('lang.price_per_space') }}</label>
                                <input v-model="price"
                                       name="price"
                                       id="price"
                                       type="text"
                                       v-validate="'required'"
                                       onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57 || event.charCode == 46"
                                       class="form-control"/>
                                <div v-show="isSubmitted && errors.has('price')" class="heightError">
                                    <small class="text-danger" v-show="errors.has('price')">
                                        {{ errors.first('price')}}
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div v-if="service_duration_type == 'hourly'" class="row">
                            <div class="form-group col-lg-6">
                                <label for="service_starts">{{ trans('lang.service_starts') }}</label>
                                <div>
                                    <vue-timepicker id="service_starts"
                                                    class="form-control"
                                                    placeholder="Service Start"
                                                    hide-clear-button
                                                    v-validate="'required'"
                                                    name="serviceStart"
                                                    v-model="service_starts"
                                                    :format="timePickerFormat">
                                    </vue-timepicker>
                                </div>
                                <div v-show="isSubmitted && service_starts == timePickerDefaultValueFormat"
                                     class="heightError">
                                    <small class="text-danger">
                                        {{ trans('lang.service_start_field_is_required') }}
                                    </small>
                                </div>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="service_ends">{{ trans('lang.service_ends') }}</label>
                                <div>
                                    <vue-timepicker id="service_ends"
                                                    class="form-control"
                                                    hide-clear-button
                                                    placeholder="Service End"
                                                    v-validate="'required'"
                                                    name="serviceEnd"
                                                    v-model="service_ends"
                                                    :format="timePickerFormat">
                                    </vue-timepicker>
                                </div>
                                <div v-show="isSubmitted && service_ends == timePickerDefaultValueFormat"
                                     class="heightError">
                                    <small class="text-danger">
                                        {{ trans('lang.service_end_field_is_required') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="service_duration">{{ trans('lang.service_duration') }}</label>
                                <div>
                                    <vue-timepicker id="service_duration"
                                                    class="form-control"
                                                    placeholder="Service Duration"
                                                    hide-clear-button
                                                    v-validate="'required'"
                                                    name="serviceDuration"
                                                    v-model="service_duration"
                                                    :format="'HH:mm'">
                                    </vue-timepicker>
                                </div>
                                <div v-show="isSubmitted && service_duration == '00:00'" class="heightError">
                                    <small class="text-danger">
                                        {{ trans('lang.service_duration_field_is_required') }}
                                    </small>
                                </div>
                                <div v-show="isSubmitted && service_duration != '00:00' && !isStartEndDurationValidate"
                                     class="heightError">
                                    <small class="text-danger">
                                        {{ trans('lang.daily_services_available_only') }}
                                    </small>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="buffer_time">
                                    {{trans('lang.service_buffering_time')}}
                                </label>
                                <select id="buffer_time" class="custom-select" v-model="buffer_time">
                                    <option value="" selected>{{ trans('lang.choose_one') }}</option>
                                    <option value="00:05:00">5 {{trans('lang.minutes')}}</option>
                                    <option value="00:10:00">10 {{trans('lang.minutes')}}</option>
                                    <option value="00:15:00">15 {{trans('lang.minutes')}}</option>
                                    <option value="00:20:00">20 {{trans('lang.minutes')}}</option>
                                    <option value="00:25:00">25 {{trans('lang.minutes')}}</option>
                                    <option value="00:30:00">30 {{trans('lang.minutes')}}</option>
                                    <option value="00:35:00">35 {{trans('lang.minutes')}}</option>
                                    <option value="00:40:00">40 {{trans('lang.minutes')}}</option>
                                    <option value="00:45:00">45 {{trans('lang.minutes')}}</option>
                                    <option value="00:50:00">50 {{trans('lang.minutes')}}</option>
                                    <option value="00:55:00">55 {{trans('lang.minutes')}}</option>
                                    <option value="01:00:00">1 {{trans('lang.hour')}}</option>
                                    <option value="01:30:00">1.5 {{trans('lang.hours')}}</option>
                                    <option value="02:00:00">2 {{trans('lang.hours')}}</option>
                                </select>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="break_time">
                                    {{trans('lang.break_time')}}
                                </label>
                                <multi-select :data="multiSelectProps" v-model="selectedBreakTimes"/>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="referred_service">
                                    {{trans('lang.share_available_space_with')}}
                                </label>
                                <multi-select :data="multipleSelectService" v-model="selectedReferredService"/>
                            </div>
                        </div>
                        <custom-input-field v-if="isActiveFields"
                                            :custom_form_fields="customFields"
                                            :date_id="customFieldDateId"
                                            :newCustomFieldData="newCustomFieldData"
                                            @fieldOptions="fieldOptions">
                        </custom-input-field>
                    </div>
                    <div class="tab-pane fade" id="gallery">
                        <div class="img-drag-wrapper mx-auto mt-3">
                            <label class="custom-file-label" for="file">
                                <i class="las la-cloud-upload-alt la-4x"/>
                                <br>
                                {{ trans('lang.drag_and_drop_an_image') }}
                                <br>
                                <span>{{ trans('lang.browse_to_choose_a_file') }}</span>
                                <br>
                                <small class="font-italic">({{ (trans('lang.recommended_service_image')) }})</small>
                            </label>
                            <input id="file"
                                   type="file"
                                   class="custom-file-input"
                                   @change="onFileChange"/>
                            <div id="preview">
                                <img v-if="url" :src="url" alt=""/>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade employee-tab" id="contact">
                        <div class="form-row">
                            <label for="employee">{{ trans('lang.assistant') }}</label>
                            <div v-for="(item, index) in items"
                                 class="input-group col-md-12 col-sm-12 custom-field-input-group">
                                <select class="form-control" id="employee" v-model="item.user_id">
                                    <option value disabled selected>{{ trans('lang.choose_one') }}</option>
                                    <option v-for="employee in allemployees"
                                            :value="employee.id">
                                        {{employee.first_name+' '+employee.last_name}}
                                    </option>
                                </select>
                                <br/>
                                <br/>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary"
                                            type="button"
                                            v-if="items.length === index + 1"
                                            @click="addEmployee()">
                                        <i class="las la-plus-circle"/>
                                    </button>
                                    <button class="btn btn-outline-secondary"
                                            type="button"
                                            v-else
                                            @click="removeEmployee(index)">
                                        <i class="las la-trash"/>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-row mt-2">
                    <div class="col-12">
                        <button class="btn app-color mobile-btn"
                                type="submit"
                                @click.prevent="save()">
                            {{trans('lang.save') }}
                        </button>
                        <button class="btn cancel-btn mobile-btn"
                                data-dismiss="modal"
                                @click.prevent>
                            {{trans('lang.cancel') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import axiosGetPost from "../../helper/axiosGetPostCommon";

    export default {
        extends: axiosGetPost,
        props: ["id", "modalID", "precision"],
        data() {
            return {
                title: "",
                price: "",
                payment_method: "",
                allow_cancel: "",
                auto_confirm: "",
                allow_coupons: "",
                service_duration: "00:00",
                buffer_time: "",
                service_starts: "",
                service_ends: "",
                multiple_bookings: 0,
                available_seat: "",
                description: "",
                min_booking: 0,
                max_booking: 0,
                week_start: "",
                activation: "",
                service_duration_type: "hourly",
                service_starting_date: "",
                service_ending_date: "",
                isServiceStartingDateNotSet: false,
                serviceStartingEndingDateProperlySet: true,
                isSubmitted: false,
                is_disabled: false,
                serviceDurationPlaceholder: "",
                preloaderType: "",
                hidePreloader: false,
                serviceEndError: this.trans("lang.required_input_field"),
                serviceDurationError: this.trans("lang.required_input_field"),
                serviceDateError: "",
                validDateRange: true,
                customFields: [],
                customFieldsOptions: [],
                newCustomFieldData: {},
                customFieldDateId: [],
                isActiveFields: false,
                newServiceDate: [],
                error: false,
                items: [{user_id: ""}],
                users: [],
                allemployees: {},
                is_true: true,
                url: null,
                file: "",
                isNavDisable: false,
                timePickerDefaultValueFormat: "",
                state: {},
                isStartEndDurationValidate: true,
                selectedBreakTimes: [],
                selectedReferredService: [],
                multipleSelectService: {
                    list: [],
                    listValueField: 'title',
                    disabled: false
                },
                multiSelectProps: {
                    list: [],
                    listValueField: 'title',
                    disabled: false
                },
            };
        },
        created() {
            this.getUserlist();
            this.getCustomField("/service-custom-field");
        },
        mounted() {
            this.setTimePickerDefaultValue();
            // Get previous day
            let date = new Date();
            date.setDate(date.getDate() - 1);
            this.state = {
                disabledDates: {
                    to: date,
                }
            };
        },
        watch: {
            title: function () {
                this.checkValidation();
            },
            available_seat: function () {
                this.checkValidation();
            },
            price: function () {
                this.checkValidation();
            },
            service_starts: function (value) {
                if (value != this.timePickerDefaultValueFormat) this.checkValidation();
            },
            service_ends: function (value) {
                if (value != this.timePickerDefaultValueFormat) this.checkValidation();
            },
            service_duration: function () {
                this.checkValidation();
            },
            buffer_time: function () {
                this.checkValidation();
            },
            service_starting_date: function () {
                if (this.service_starting_date != '' && this.service_ending_date != '') {
                    this.isServiceStartingDateNotSet = false;
                    this.serviceStartEndDateValidation();
                }
            },
            service_ending_date: function () {
                if (this.service_starting_date != '' && this.service_ending_date != '') {
                    this.isServiceStartingDateNotSet = false;
                    this.serviceStartEndDateValidation();
                } else this.isServiceStartingDateNotSet = true;
            },
        },
        methods: {
            onFileChange(e) {
                this.file = e.target.files[0];
                this.url = URL.createObjectURL(this.file);
            },
            setTimePickerDefaultValue() {
                let instance = this;
                if (this.timeFormat == "12h") {
                    instance.timePickerDefaultValueFormat = "hh:00 AM";
                } else {
                    instance.timePickerDefaultValueFormat = "HH:00";
                }
                instance.service_starts = instance.timePickerDefaultValueFormat;
                instance.service_ends = instance.timePickerDefaultValueFormat;
            },
            addEmployee() {
                this.items.push({user_id: ""});
            },
            removeEmployee(index) {
                this.items.splice(index, 1);
            },
            getUserlist() {
                let instance = this;
                instance.axiosGet("/service-userlist", function (response) {
                    instance.allemployees = response.data;
                });
            },
            getServiceData(route) {
                let instance = this;
                this.setPreLoader(false);
                this.axiosGet(
                    route,
                    function (response) {
                        if(response.data.break_time){
                            let break_time = _.clone(response.data.break_time);
                            instance.selectedBreakTimes = break_time.split(',');
                        }
                        if (response.data.referred_service_id) {
                            let reference_ids = _.clone(response.data.referred_service_id);
                            instance.selectedReferredService = reference_ids.split(',');
                        }
                        instance.newCustomFieldData = response.data.customFields;
                        if (response.data.customFields != null) {
                            instance.customFieldsOptions =
                                response.data.customFields;
                            instance.customFieldDateId = response.data.dateId;
                        }
                        instance.title = _.clone(response.data.title);
                        instance.price = _.clone(response.data.price);
                        instance.service_duration = _.clone(
                            response.data.service_duration
                        );
                        instance.buffer_time = _.clone(response.data.buffer_time);
                        instance.multiple_bookings = _.clone(
                            response.data.multiple_bookings
                        );
                        if (response.data.description) {
                            instance.description = _.clone(response.data.description);
                        }
                        instance.service_starts = _.clone(
                            response.data.service_starts
                        );
                        instance.service_ends = _.clone(response.data.service_ends);
                        instance.available_seat = _.clone(
                            response.data.available_seat
                        );
                        instance.service_duration_type = _.clone(
                            response.data.service_duration_type
                        );
                        instance.service_starting_date = _.clone(
                            response.data.service_starting_date
                        );
                        instance.service_ending_date = _.clone(
                            response.data.service_ending_date
                        );
                        instance.setPreLoader(true);
                    },
                    function (errror) {
                        instance.setPreLoader(true);
                    }
                );
            },
            getCustomField() {
                let instance = this;
                instance.setPreLoader(false);
                instance.axiosPost(
                    "/get-service-custom-field",
                    {
                        table_name: "service"
                    },
                    function (response) {
                        instance.customFields = response.data;
                        instance.isActiveFields = true;
                        if (instance.id) {
                            instance.getServiceData("/serviceid/" + instance.id);
                        } else {
                            instance.setPreLoader(true);
                        }
                        instance.getBreakTimes();
                        instance.getAllServices();
                    },
                    function (error) {
                        instance.setPreLoader(true);
                    }
                );
            },
            setService(type) {
                this.service_duration_type = type;
                if (this.service_duration_type == "daily") {
                    this.multiple_bookings = 0;
                }
            },
            setActive: function (e, f) {
                this.$emit("setActive", e, f);
            },
            fieldOptions(options, newDateId) {
                let instance = this;
                instance.customFieldsOptions = options;
                instance.newServiceDate = newDateId;
            },
            setPreloader: function (type, action) {
                this.preloaderType = type;
                this.hidePreloader = action;
            },
            save(e) {
                let instance = this;
                instance.isSubmitted = true;
                instance.$validator.validateAll().then(result => {
                    if (
                        result && instance.service_starts != instance.timePickerDefaultValueFormat
                        && instance.service_ends != instance.timePickerDefaultValueFormat
                        && instance.service_duration != "00:00"
                        && instance.isStartEndDurationValidate
                        && !instance.isServiceStartingDateNotSet
                        && instance.serviceStartingEndingDateProperlySet
                    ) {
                        let formData = new FormData();
                        formData.append("title", instance.title);
                        formData.append("price", accounting.unformat(instance.price, instance.decimalSeparator));
                        formData.append(
                            "service_duration",
                            instance.service_duration
                        );
                        formData.append("buffer_time", instance.buffer_time);
                        formData.append(
                            "multiple_bookings",
                            instance.multiple_bookings
                        );
                        formData.append("description", instance.description);
                        formData.append("min_booking", instance.min_booking);
                        formData.append("max_booking", instance.max_booking);
                        formData.append("break_time", instance.selectedBreakTimes.toString());
                        formData.append("referred_service_id", instance.selectedReferredService.toString());
                        formData.append(
                            "service_starts",
                            instance.service_starts
                        );
                        formData.append("service_ends", instance.service_ends);
                        formData.append(
                            "available_seat",
                            instance.available_seat
                        );
                        formData.append(
                            "service_duration_type",
                            instance.service_duration_type
                        );
                        if (instance.service_starting_date != '') {
                            if (instance.service_starting_date == null) {
                                instance.service_starting_date = '';
                                formData.append("service_starting_date", instance.service_starting_date);
                            } else {
                                let endDate = instance.convertStrToDate(instance.service_starting_date);
                                formData.append("service_starting_date", endDate);
                            }
                        } else formData.append("service_starting_date", instance.service_starting_date);

                        if (instance.service_ending_date != '') {
                            if (instance.service_ending_date == null) {
                                instance.service_ending_date = '';
                                formData.append("service_ending_date", instance.service_ending_date);
                            } else {
                                let endDate = instance.convertStrToDate(instance.service_ending_date);
                                formData.append("service_ending_date", endDate);
                            }
                        } else formData.append("service_ending_date", instance.service_ending_date);

                        formData.append(
                            "customFields",
                            JSON.stringify(instance.customFieldsOptions)
                        );
                        instance.items.forEach(user => {
                            if (user.user_id != "") {
                                formData.append("user_id[]", user.user_id);
                            }
                        });

                        if (instance.file != '') {
                            formData.append("uploads", instance.file);
                        }

                        if (instance.id) {
                            instance.postDataMethod(
                                "/serviceid/" + instance.id,
                                formData
                            );
                        } else {
                            instance.postDataMethod(
                                "/servicenew/create/store",
                                formData
                            );
                        }
                    }
                });
            },

            postDataThenFunctionality(response) {
                let instance = this;
                $(instance.modalID).modal("hide");
                instance.$hub.$emit("reloadDataTable");
            },
            postDataCatchFunctionality(error) {
                let instance = this;
            },
            checkValidation(submit = false) {
                let instance = this;
                if (submit) {
                    instance.isSubmitted = submit;
                }
                if (this.service_starts != instance.timePickerDefaultValueFormat
                    && this.service_ends != instance.timePickerDefaultValueFormat
                    && this.service_duration != '00:00'
                ) {
                    this.serviceTimeValidation();
                }
                instance.$validator.validateAll().then(result => {
                    if (
                        (result && instance.service_starts != instance.timePickerDefaultValueFormat
                            && instance.service_ends != instance.timePickerDefaultValueFormat
                            && instance.service_duration != "00:00"
                            && instance.isStartEndDurationValidate
                            && !instance.isServiceStartingDateNotSet
                            && instance.serviceStartingEndingDateProperlySet
                        ) || !instance.isSubmitted
                    ) {
                        instance.isNavDisable = false;
                    } else {
                        instance.isNavDisable = true;
                    }
                });
            },

            // Starting time and ending time duration caculation
            serviceTimeValidation() {
                let timeStart = this.Get24HourDate(this.service_starts),
                    timeEnd = this.Get24HourDate(this.service_ends),
                    duration = this.Get24HourDate(this.service_duration),
                    startEndDiff = this.getDiff(timeStart, timeEnd),
                    durationHours = duration.getHours(),
                    durationMinutes = duration.getMinutes(),
                    startEndDiffInMin = (startEndDiff.hours * 60) + startEndDiff.mins,
                    durationInMin = (durationHours * 60) + durationMinutes;

                if (startEndDiffInMin >= durationInMin) {
                    this.isStartEndDurationValidate = true;
                } else {
                    this.isStartEndDurationValidate = false;
                }
            },
            // Starting time and ending time duration caculation
            Get24HourDate(str) {
                let dt = new Date(),
                    parts = str.split(':'),
                    hour = parseInt(parts[0]),
                    parts2 = parts[1].split(' '),
                    mis = parts2[0],
                    ampm = parts2[1];
                if (ampm != undefined) {
                    if (ampm.toLowerCase() == 'pm') {
                        if (hour < 12)
                            hour += 12;
                    }
                    if (ampm.toLowerCase() == 'am') {
                        if (hour == 12) hour = 0;
                    }
                }

                dt.setHours(hour, mis, 0);
                return dt;
            },
            // Starting time and ending time duration caculation
            getDiff(timestart, timeEnd) {
                let timeStart = timestart.getTime();
                timeEnd = timeEnd.getTime();
                let hourDiff = timeEnd - timeStart;

                let secDiff = hourDiff / 1000,
                    minDiff = hourDiff / 60 / 1000,
                    hDiff = hourDiff / 3600 / 1000,
                    hours = Math.floor(hDiff),
                    mins = minDiff - 60 * hours;

                return {
                    hours: hours,
                    mins: mins
                }
            },

            //Service starting date and ending date validation
            serviceStartEndDateValidation() {
                let startingDate = this.getDateFormat(this.service_starting_date),
                    endingDate = this.getDateFormat(this.service_ending_date);

                if (Date.parse(startingDate) > Date.parse(endingDate)) {
                    this.serviceStartingEndingDateProperlySet = false;
                } else this.serviceStartingEndingDateProperlySet = true;
            },
            //Service starting date and ending date validation
            getDateFormat(str) {
                let date = new Date(str),
                    mnth = ("0" + (date.getMonth() + 1)).slice(-2),
                    day = ("0" + date.getDate()).slice(-2);
                return [date.getFullYear(), mnth, day].join("-");
            },

            /* *
             *  method fo break time
             * */
            getBreakTimes() {
                let instance = this;
                instance.setPreLoader(false);
                instance.axiosGet("/break-times", function (response) {
                    instance.multiSelectProps.list = response.data;
                    instance.setPreLoader(true);
                });
            },

            getAllServices() {
                let instance = this;
                instance.setPreLoader(false);
                if (instance.id) {
                    instance.axiosGet("/get-services/" + instance.id, function (response) {
                        instance.multipleSelectService.list = response.data;
                        instance.setPreLoader(true);
                    });
                } else {
                    instance.axiosGet("/getAllServiceFormData", function (response) {
                        instance.multipleSelectService.list = response.data.services;
                        instance.setPreLoader(true);
                    });
                }
            },
            breakTimeChange($event) {
                let value = $event.target.value;

                if (!this.selectedBreakTimes.includes(value)) {
                    this.selectedBreakTimes.push(value);
                } else {
                    let index = this.selectedBreakTimes.indexOf(value);
                    this.selectedBreakTimes.splice(index, 1);
                }
            }
        }
    };
</script>