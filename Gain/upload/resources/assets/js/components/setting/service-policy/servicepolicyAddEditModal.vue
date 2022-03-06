<template>
    <div>
        <div class="modal-layout-header">
            <div class="container-fluid p-0">
                <div class="row">
                    <div class="col-10">
                        <h4 class="m-0" v-if="id">{{ trans('lang.change_service_policy') }}</h4>
                        <h4 class="m-0" v-else>{{ trans('lang.service_policy') }}</h4>
                    </div>
                    <div class="col-2 text-right pr-0">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click.prevent="">
                            <span aria-hidden="true"><i class="la la-close"></i></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <pre-loader v-if="!hidePreLoader"></pre-loader>
        <div class="modal-layout-content" v-else>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="title">{{trans('lang.title') }}</label>
                    <input id="title" v-model="title" name="title" type="text"
                           class="form-control"
                           v-validate="'required'"
                           :class="{ 'is-invalid': submitted && errors.has('title') }">
                    <div class="heightError">
                        <small class="text-danger" v-show="errors.has('title')">
                            {{
                            errors.first('title') }}
                        </small>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <label for="description">{{trans('lang.description') }}</label>
                    <textarea id="description" class="form-control"
                              v-model="description" name="description"
                              v-validate="'required'"
                              :class="{ 'is-invalid': submitted && errors.has('description') }"></textarea>
                    <div class="heightError">
                        <small class="text-danger" v-show="errors.has('description')">
                            {{
                            errors.first('description') }}
                        </small>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <label for="select_service">{{ trans('lang.service') }}</label>
                    <select id="select_service" class="form-control" name="service_id" v-model="service_id"
                            v-validate="'required'" :class="{ 'is-invalid': submitted && errors.has('service_id') }"
                            :data-vv-as="trans('lang.service_small')">
                        <option value disabled selected>{{ trans('lang.choose_one') }}</option>
                        <option :value="service.id" v-for="service in servicelists">{{service.title}}</option>
                    </select>
                    <div class="heightError">
                        <small class="text-danger" v-show="errors.has('service_id')">
                            {{
                            errors.first('service_id') }}
                        </small>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label>{{trans('lang.consider_children_for_price') }}</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" v-model="consider_children_for_price"
                               name="consider_children_for_price" id="childen_for_price_yes" v-validate="'required'"
                               :data-vv-as="trans('lang.consider_children_for_price_small')"
                               :class="{ 'is-invalid': submitted && errors.has('consider_children_for_price') }"
                               value="Yes">
                        <label for="childen_for_price_yes" class="radio-button-label">{{trans('lang.yes') }}</label>


                        <div class="form-group col-md-12">
                            <label for="custom-content">{{trans('lang.description') }}</label>
                            <textarea id="custom-content" rows="3" class="form-control"
                                      v-model="custom_content" name="description" v-validate="'required'"></textarea>

                        </div>
                        <div class="heightError">
                            <small class="text-danger" v-show="errors.has('consider_children_for_price')">
                                {{
                                errors.first('consider_children_for_price') }}
                            </small>
                        </div>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="age_range">{{trans('lang.age_range') }}</label>
                        <select id="age_range" class="form-control" name="age_range" v-model="age_range"
                                v-validate="'required'"
                                :class="{ 'is-invalid': submitted && errors.has('age_range') }"
                                :data-vv-as="trans('lang.age_range_small')">

                            <option value disabled selected>{{ trans('lang.choose_one') }}</option>
                            <option v-for="(agerange, index) in sliceItems(0,19)" :value="index+1">{{trans('lang.ziro')
                                }} {{agerange}} {{trans('lang.years')}}
                            </option>

                        </select>
                        <div class="heightError">
                            <small class="text-danger" v-show="errors.has('age_range')">
                                {{
                                errors.first('age_range') }}
                            </small>
                        </div>

                    </div>

                    <div class="form-group col-md-3">
                        <label for="percentage">{{trans('lang.percentage') }}</label>
                        <input id="percentage" v-model="percentage" name="percentage" type="text" class="form-control"
                               v-validate="'required'"
                               :class="{ 'is-invalid': submitted && errors.has('percentage') }">

                        <div class="heightError">
                            <small class="text-danger" v-show="errors.has('percentage')">
                                {{
                                errors.first('percentage') }}
                            </small>
                        </div>
                    </div>

                    <div class="col-12">
                        <button class="btn app-color mobile-btn" type="submit" @click.prevent="save()">
                            {{trans('lang.save') }}
                        </button>
                        <button class="btn cancel-btn mobile-btn" data-dismiss="modal" @click.prevent="">{{
                            trans('lang.cancel') }}
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
        props: ['id', 'modalID'],
        data() {
            return {
                hidePreLoader: true,
                custom_content: '',
                submitted: false,
            }
        },
        created() {
            this.getServices();
            if (this.id) {
                this.getServicePolicy('/servicepolicy-edit/' + this.id);
            }
        },
        mounted() {
            let instance = this;
            $("#custom-content").summernote(
                {
                    callbacks: {
                        onChange: function () {
                            var code = $(this).summernote("code");
                            instance.custom_content = code;
                        }
                    }
                }
            );
        },
        methods: {
            getServices() {
                let instance = this;
                instance.axiosGet('/serviceshowforbooking', function (response) {
                    instance.servicelists = response.data.serviceData;
                })
            },
            sliceItems: function (start, end) {
                return this.argeranges.slice(start, end);
            },
            save() {
                let instance = this;
                (instance.submitted = true),
                    instance.$validator.validateAll().then(result => {
                        if (result) {
                            instance.inputFields = {
                                //description: instance.description,
                                description: this.custom_content,

                            };

                            if (instance.id) {
                                instance.postDataMethod('/servicepolicy-update/' + instance.id, instance.inputFields);
                            } else {
                                instance.postDataMethod('/servicepolicy-store', instance.inputFields);
                            }
                        }
                    });

            },
            postDataThenFunctionality(response) {
                let instance = this;
                $(instance.modalID).modal('hide');
                instance.$hub.$emit('reloadDataTable');
            },
            getServicePolicy(route) {
                let instance = this;
                this.setPreLoader(false);
                instance.axiosGet(route,
                    function (response) {
                        //instance.description = response.data.description;
                        $("#custom-content").summernote("code", instance.custom_content);
                        instance.setPreLoader(true);
                    },
                    function (error) {
                        instance.setPreLoader(true);
                    },
                );
            },
        }
    }
</script>