<template>
    <div>
        <div class="modal-layout-header">
            <div class="row">
                <div class="col-10">
                    <h4 class="m-0">{{ trans('lang.setting_service') }}</h4>
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
        <pre-loader v-if="!hidePreLoader"/>
        <div class="modal-layout-content" v-else>
            <form>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label class="d-block">{{ trans('lang.auto_confirm') }}</label>
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="radio"
                                       class="form-check-input"
                                       v-model="auto_confirm"
                                       value="1">{{ trans('lang.yes') }}
                            </label>
                        </div>
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="radio"
                                       class="form-check-input"
                                       v-model="auto_confirm"
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
                                       v-model="activation"
                                       value="1">{{ trans('lang.yes') }}
                            </label>
                        </div>
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="radio"
                                       class="form-check-input"
                                       v-model="activation"
                                       value="0">{{ trans('lang.no') }}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="alias">{{ trans('lang.url_alias') }}</label>
                        <input v-model="alias"
                               v-validate="'required'"
                               name="alias"
                               id="alias"
                               type="text"
                               class="form-control"/>
                        <div v-show="errors.has('alias')" class="heightError">
                            <small class="text-danger" v-show="errors.has('alias')">
                                {{ errors.first('alias') }}
                            </small>
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="allow_booking_max_day_ago">
                            {{ trans('lang.allow_booking_max_day_ago') }}
                        </label>
                        <div class="input-group">
                            <input v-model="allow_booking_max_day_ago"
                                   id="allow_booking_max_day_ago"
                                   type="text"
                                   class="form-control"/>
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">
                                    {{ trans('lang.days_ago') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="business_type=='salon'">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="d-block">{{trans('lang.consider_children_for_price') }}</label>
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio"
                                           class="form-check-input"
                                           name="consider_children_for_price"
                                           v-model="consider_children_for_price"
                                           v-validate="'required'"
                                           :data-vv-as="trans('lang.consider_children_for_price_small')"
                                           :class="{ 'is-invalid': submitted && errors.has('consider_children_for_price') }"
                                           value="Yes">{{ trans('lang.yes') }}
                                </label>
                            </div>
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="radio"
                                           class="form-check-input"
                                           v-model="consider_children_for_price"
                                           name="consider_children_for_price"
                                           v-validate="'required'"
                                           :data-vv-as="trans('lang.consider_children_for_price_small')"
                                           :class="{ 'is-invalid': submitted && errors.has('consider_children_for_price') }"
                                           value="No">{{ trans('lang.no') }}
                                </label>
                            </div>
                            <div class="heightError">
                                <small class="text-danger" v-show="errors.has('consider_children_for_price')">
                                    {{ errors.first('consider_children_for_price') }}
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="form-row" v-if="consider_children_for_price == 'Yes'">
                        <div class="form-group col-md-6">
                            <label for="age_range">{{trans('lang.age_range') }}</label>
                            <select id="age_range"
                                    class="form-control"
                                    name="age_range"
                                    v-model="age_range"
                                    v-validate="'required'"
                                    :data-vv-as="trans('lang.age_range_small')">
                                <option value disabled selected>
                                    {{ trans('lang.choose_one') }}</option>
                                <option v-for="(agerange, index) in sliceItems(0,19)" :value="index+1">
                                    {{trans('lang.ziro') }} {{agerange}} {{trans('lang.years')}}
                                </option>
                            </select>
                            <div class="heightError">
                                <small class="text-danger" v-show="errors.has('age_range')">
                                    {{ errors.first('age_range') }}
                                </small>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="percentage">{{trans('lang.percentage') }}</label>
                            <input id="percentage"
                                    v-model="percentage"
                                    name="percentage"
                                    type="text"
                                    class="form-control"
                                    v-validate="'required'"/>
                            <div class="heightError">
                                <small class="text-danger" v-show="errors.has('percentage')">
                                    {{ errors.first('percentage') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-12">
                        <button class="btn app-color mobile-btn" type="submit" @click.prevent="save()">
                            {{trans('lang.save') }}
                        </button>
                        <button class="btn cancel-btn mobile-btn"
                                data-dismiss="modal"
                                @click.prevent>
                            {{ trans('lang.cancel') }}
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
        name: "CustomSettingModal",
        extends: axiosGetPost,
        props: ["id", "modalID"],
        data() {
            return {
                auto_confirm: "",
                activation: "",
                alias: "",
                allow_booking_max_day_ago: "",
                hidePreloader: false,
                consider_children_for_price: "No",
                age_range: "",
                argeranges: [
                    1,
                    2,
                    3,
                    4,
                    5,
                    6,
                    7,
                    8,
                    9,
                    10,
                    11,
                    12,
                    13,
                    14,
                    15,
                    16,
                    17,
                    18
                ],
                percentage: "",
                submitted: false
            };
        },
        created() {
            if (this.id) {
                this.getServiceSettingData("/serviceid/" + this.id);
            }
        },
        methods: {
            sliceItems: function (start, end) {
                return this.argeranges.slice(start, end);
            },
            getServiceSettingData(route) {
                let instance = this;
                instance.setPreLoader(false);
                instance.axiosGet(
                    route,
                    function (response) {
                        instance.setPreLoader(true);
                        instance.auto_confirm = response.data.auto_confirm;
                        instance.activation = response.data.activation;
                        instance.alias = response.data.alias;
                        instance.allow_booking_max_day_ago =
                            response.data.allow_booking_max_day_ago;
                        instance.percentage = response.data.percentage;
                        instance.business_type = response.data.business_type;

                        if (response.data.age_range) {
                            instance.age_range = response.data.age_range;
                        }
                        if (!_.isEmpty(response.data.consider_children_for_price)) {
                            instance.consider_children_for_price =
                                response.data.consider_children_for_price;
                        }
                    },
                    function (errror) {
                        instance.setPreLoader(true);
                    }
                );
            },
            save() {
                let instance = this;
                this.submitted = true;
                instance.$validator.validateAll().then(result => {
                    if (result) {
                        instance.inputFields = {
                            auto_confirm: instance.auto_confirm,
                            activation: instance.activation,
                            alias: instance.alias,
                            allow_booking_max_day_ago:
                            instance.allow_booking_max_day_ago,
                            consider_children_for_price:
                            instance.consider_children_for_price,
                            age_range: instance.age_range,
                            percentage: instance.percentage
                        };

                        if (instance.id) {
                            instance.postDataMethod(
                                `/serviceSetting/${instance.id}`,
                                instance.inputFields
                            );
                            instance.submitted = false;
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
            }
        }
    };
</script>