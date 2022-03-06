<template>
    <div>
        <div class="modal-layout-header">
            <div class="container-fluid p-0">
                <div class="row">
                    <div class="col-10">
                        <h4 class="m-0" v-if="id">{{ trans('lang.edit') }}</h4>
                        <h4 class="m-0" v-else>{{ trans('lang.add_custom_field') }}</h4>
                    </div>
                    <div class="col-2 text-right">
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
                <div class="form-group col-md-6">
                    <label for="input-label">{{trans('lang.label_name') }}</label>
                    <input id="input-label"
                           name="label"
                           v-model="label"
                           type="text"
                           class="form-control"
                           v-validate="'required'"
                           :class="{ 'is-invalid': submitted && errors.has('label') }">
                    <div class="heightError">
                        <small class="text-danger" v-show="errors.has('label')">
                            {{ errors.first('label') }}
                        </small>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label for="field-type">{{trans('lang.field_type') }}</label>
                    <select v-model="fieldType" name="fieldType" id="field-type" class="custom-select"
                            :disabled="isDisable" v-validate="'required'" :data-vv-as="trans('lang.field_type_small')"
                            :class="{ 'is-invalid': submitted && errors.has('fieldType') }">
                        <option value="" disabled selected>{{ trans('lang.choose_one') }}</option>
                        <option value="text">{{ trans('lang.text') }}</option>
                        <option value="textarea">{{ trans('lang.textarea') }}</option>
                        <option value="email">{{ trans('lang.input_email') }}</option>
                        <option value="date">{{ trans('lang.input_date') }}</option>
                        <option value="checkbox">{{ trans('lang.checkbox') }}</option>
                        <option value="number">{{ trans('lang.number') }}</option>
                        <option value="radio">{{ trans('lang.radio') }}</option>
                        <option value="select">{{ trans('lang.select') }}</option>
                    </select>
                    <div class="heightError">
                        <small class="text-danger" v-show="errors.has('fieldType')">
                            {{
                            errors.first('fieldType') }}
                        </small>
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label for="field-type">{{trans('lang.select_service') }}</label>
                    <select v-model="serviceId" name="serviceId" id="service" class="custom-select"
                            :disabled="isDisable">
                        <option value="" selected>{{ trans('lang.choose_one') }}</option>
                        <option v-for="service in services" :value="service.id">{{ service.title }}</option>
                    </select>
                    <small class="text">
                        {{ trans('lang.select_service_custom_field')}}
                    </small>
                </div>
                <div class="form-group col-md-12"
                     v-if="fieldType === 'checkbox' || fieldType === 'radio' || fieldType ==='select'">
                    <div v-show="options.length>0">
                        <label>{{ trans('lang.options') }}</label>
                        <br>
                        <div class="custom-chip custom-chip-top-margin" v-for="option in options">
                            {{option}} <i class="la la-times" @click="deleteChip(option)"></i>
                        </div>
                    </div>
                    <div class="input-group custom-field-input-group">
                        <input type="text"
                               class="form-control"
                               id="input-options"
                               name="inputOptions"
                               v-model="inputOptions"
                               @keyup.enter="addChips">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" @click="addChips">
                                <i class="las la-plus-circle"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" id="1" v-model="enableField"
                               name="enableField" checked value="1">
                        <label class="custom-control-label" for="1">{{ trans('lang.enable_field') }}</label>
                    </div>

                    <div v-if="!serviceId" class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" id="2" v-model="showTable" name="showTable"
                               checked value="1">
                        <label class="custom-control-label" for="2">{{ trans('lang.show_in_table') }}</label>
                    </div>

                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" id="3" v-model="visibleToClient"
                               name="visible_in_client" checked
                               value="1">
                        <label class="custom-control-label" for="3">{{ trans('lang.visible_to_client') }}</label>
                    </div>
                </div>

                <div class="col-12">
                    <button class="btn app-color mobile-btn" type="submit" @click.prevent="save()">{{trans('lang.save')
                        }}
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
        props: ['id', 'modalID'],
        extends: axiosGetPost,
        data() {
            return {
                hidePreLoader: true,
                label: '',
                fieldType: '',
                enableField: true,
                showTable: true,
                visibleToClient: true,
                options: [],
                newOptions: [],
                inputOptions: '',
                table_name: 'booking',
                isDisable: false,
                submitted: false,
                serviceId: '',
                services: [],

            }
        },
        created() {
            if (this.id) {
                this.editCustomfiledBooking('/customfiledbooking-edit/' + this.id);
            }
            this.getAllService('/getAllServiceFormData');
        },
        methods: {
            addChips() {
                if (this.inputOptions !== '') {
                    this.options.push(this.inputOptions);
                }
                this.inputOptions = '';
            },
            deleteChip(chip) {
                this.options = this.options.filter(function (element) {
                    return element !== chip;
                });
            },
            save() {
                let instance = this;
                if (instance.serviceId) instance.showTable = false;
                (instance.submitted = true),
                    instance.$validator.validateAll().then(result => {
                        if (result) {
                            instance.inputFields = {
                                label: instance.label,
                                fieldType: instance.fieldType,
                                enableField: instance.enableField,
                                showTable: instance.showTable,
                                visibleToClient: instance.visibleToClient,
                                tableName: instance.table_name,
                                options: instance.options,
                                serviceId: instance.serviceId,
                            };

                            if (instance.id) {
                                instance.postDataMethod('/customfiled-update/' + instance.id, instance.inputFields);
                            } else {
                                instance.postDataMethod('/save-custom-field', instance.inputFields);
                            }
                        }
                    });
            },
            postDataThenFunctionality(response) {
                let instance = this;
                $(instance.modalID).modal('hide');
                instance.$hub.$emit('reloadDataTable');
            },
            postDataCatchFunctionality(error) {
                let instance = this;
            },
            editCustomfiledBooking(route) {
                let instance = this;
                this.setPreLoader(false);
                instance.axiosGet(route,
                    function (response) {
                        instance.label = response.data.label;
                        instance.fieldType = response.data.field_type;
                        instance.enableField = response.data.is_enable;
                        instance.showTable = response.data.show_in_table;
                        instance.visibleToClient = response.data.visible_in_client;
                        instance.options = response.data.options;
                        instance.newOptions = response.data.options;
                        if (response.data.service_id) instance.serviceId = response.data.service_id;
                        instance.isDisable = true;
                        instance.setPreLoader(true);
                    },
                    function (error) {
                        instance.setPreLoader(true);
                    },
                );
            },
            getAllService(route) {
                let instance = this;
                instance.axiosGet(route,
                    function (response) {
                        instance.services = response.data.services;
                    },
                    function (error) {
                        instance.setPreLoader(true);
                    },
                );
            },
        }
    }
</script>