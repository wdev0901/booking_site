<template>
    <div>
        <div class="row modal-layout-header">
            <div class="col-10">
                <h5 class="bluish-text no-margin" v-if="id">{{ trans('lang.edit_custom_field') }}</h5>
                <h5 class="bluish-text no-margin" v-else>{{ trans('lang.add_custom_field') }}</h5>
            </div>
            <div class="col-2 text-center">
                <a href="#" class="modal-close"><i
                        class="material-icons dp48 grey-text icon-vertically-middle">clear</i></a>
            </div>
        </div>
        <div class="modal-loader-parent" v-if="hidePreloader!='hide'">
            <div class="modal-loader-child">
                <preloaders :loderType="preloaderType"></preloaders>
            </div>
        </div>
        <div class="modal-layout-content" v-show="hidePreloader=='hide'">
            <div class="row margin-fix">
                <div class="row margin-fix">
                    <div class="input-field col s6">
                        <input :class="{'invalid': errorInvalid.label}" type="text" id="input-label" v-model="label">
                        <label for="input-label" :class="{'active': error}">{{ trans('lang.label_name') }}</label>
                        <span class="helper-text" :data-error="trans('lang.required_label_field')"></span>
                    </div>
                    <div class="input-field col s6">
                        <select v-model="fieldType" id="field-type" :disabled="isDisable">
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
                        <label for="field-type">{{ trans('lang.field_type') }}</label>
                        <span v-if="errorInvalid.fieldType" class="red-text helper-text">{{ trans('lang.required_input_type') }}</span>
                    </div>

                    <div class="col s12"
                         v-if="fieldType === 'checkbox' || fieldType === 'radio' || fieldType ==='select'">
                        <div v-show="options.length>0">
                            <label>{{ trans('lang.options') }}</label>
                            <br>
                            <div class="custom-chip custom-chip-top-margin" v-for="option in options">
                                {{option}} <i class="close material-icons" @click="deleteChip(option)">close</i>
                            </div>
                        </div>
                        <div class="input-field col s11">
                            <input type="text" id="input-options" v-model="inputOptions" @keyup.enter="addChips">
                            <label for="input-options">{{ trans('lang.add_options') }}</label>
                        </div>
                        <div class="col s1">
                            <div class="custom-action-icon">
                                <a class="btn-floating waves-effect waves-light green" @click="addChips">
                                    <i class="material-icons white-text">add</i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col s12 custom-field-check-box">
                        <label for="custom-field-check">
                            <input type="checkbox" id="custom-field-check" v-model="enableField" value="1"
                                   name="field-enable"/>
                            <span>{{ trans('lang.enable_field') }}</span> <br>
                        </label>
                    </div>
                    <div class="col s12 custom-field-check-box">
                        <label for="custom-field-check1">
                            <input type="checkbox" id="custom-field-check1" v-model="showTable" value="1"
                                   name="field-enable"/>
                            <span>{{ trans('lang.show_in_table') }}</span> <br>
                        </label>
                    </div>
                    <div class="col s12 custom-field-check-box" v-if="table_name == 'booking'">
                        <label for="custom-field-check2">
                            <input type="checkbox" id="custom-field-check2" v-model="visibleToClient" value="1"
                                   name="field-enable"/>
                            <span>{{ trans('lang.visible_to_client') }}</span> <br>
                        </label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <button class="btn waves-effect waves-light bluish-back mob-btn"
                            type="submit"
                            @click="setErrorInvalid(), save()">
                        {{ trans('lang.save') }}
                    </button>
                    <button class="btn cancel-btn waves-effect waves-grey mob-btn modal-close">
                        {{ trans('lang.cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import axiosGetPost from '../../helper/axiosGetPostCommon';

    export default {
        props: ['id', 'table_name'],
        extends: axiosGetPost,
        data() {
            return {
                fieldType: '',
                label: '',
                inputEmailLabel: '',
                inputOptions: '',
                options: [],
                newOptions: [],
                enableField: true,
                showTable: true,
                visibleToClient: true,
                hidePreloader: '',
                preloaderType: 'load',
                isDisable: false,

                error: false,
                errorInvalid: {
                    label: false,
                    fieldType: false,
                },
            }
        },
        mounted() {
            if (this.id) {
                this.getCustomField();
            } else {
                let instance = this;
                instance.setPreloader('load', 'hide');
            }

            $("#field-type").formSelect();
        },
        methods: {
            setErrorInvalid() {
                this.error = false;
                this.errorInvalid.label = false;
                this.errorInvalid.fieldType = false;
            },
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
                if (this.label == undefined || this.label == '') {
                    this.$nextTick(function () {
                        instance.error = true;
                        instance.errorInvalid.label = true;
                    });
                }
                if (this.fieldType == undefined || this.fieldType == '') {
                    this.$nextTick(function () {
                        instance.error = true;
                        instance.errorInvalid.fieldType = true;
                    });
                }
                else {
                    if (this.id) {
                        if (this.label && this.fieldType) {
                            instance.setPreloader('load', '');
                            instance.axiosPost('/save-custom-field/' + instance.id, {
                                    label: instance.label,
                                    options: instance.options,
                                    enableField: instance.enableField,
                                    showTable: instance.showTable,
                                    visibleToClient: instance.visibleToClient
                                },
                                function (response) {
                                    instance.setPreloader('load', 'hide');
                                    M.toast({html: response.data.msg});
                                    instance.setHide();
                                    instance.$hub.$emit('reloadDataTable');
                                },
                                function (error) {
                                    M.toast({html: instance.trans('lang.getting_problems'), classes: 'red lighten-1'});
                                    instance.setPreloader('load', 'hide');
                                }
                            )
                        }
                    } else {
                        if (this.label && this.fieldType) {
                            instance.error = true;
                            instance.setPreloader('load', '');
                            instance.axiosPost('/save-custom-field', {
                                    fieldType: instance.fieldType,
                                    label: instance.label,
                                    tableName: instance.table_name,
                                    options: instance.options,
                                    enableField: instance.enableField,
                                    showTable: instance.showTable,
                                    visibleToClient: instance.visibleToClient
                                },
                                function (response) {
                                    instance.setPreloader('load', 'hide');
                                    M.toast({html: response.data.msg});
                                    instance.setHide();
                                    instance.$hub.$emit('reloadDataTable');
                                },
                                function (error) {
                                    M.toast({html: instance.trans('lang.getting_problems'), classes: 'red lighten-1'});
                                    instance.setPreloader('load', 'hide');
                                }
                            );
                        }

                    }
                }
            },

            setHide() {
                $('#custom-input-modal').modal('close');
            },
            setPreloader(type, action) {
                this.preloaderType = type;
                this.hidePreloader = action;

            },
            getCustomField() {
                let instance = this;
                instance.axiosGet('/get-custom-field/' + instance.id,
                    function (response) {
                        instance.setPreloader('load', 'hide');
                        instance.fieldType = response.data.field_type;
                        instance.label = response.data.label;
                        instance.options = response.data.options;
                        instance.newOptions = response.data.options;
                        if (response.data.is_enable == 0) instance.enableField = false;
                        if (response.data.show_in_table == 0) instance.showTable = false;
                        if (response.data.visible_in_client == 0) instance.visibleToClient = false;
                        instance.isDisable = true;
                    },
                    function (error) {

                    }
                );
            },
        }
    }
</script>