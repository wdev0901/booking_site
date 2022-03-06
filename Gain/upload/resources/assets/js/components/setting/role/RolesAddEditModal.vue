<template>
    <div>
        <div class="modal-layout-header">
            <div class="container-fluid p-0">
                <div class="row">
                    <div class="col-10">
                        <h4 class="m-0" v-if="id">{{ trans('lang.edit_role') }}</h4>
                        <h4 class="m-0" v-else>{{ trans('lang.add_role') }}</h4>
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
            <form class="form-row">
                <div class="form-group col-md-12">
                    <label for="title">{{ trans('lang.role_title') }}</label>
                    <input v-validate="'required'" name="title" id="title" class="form-control" type="text"
                           v-model="title">
                    <small class="text-danger" v-show="errors.has('title')">{{ errors.first('title') }}</small>
                </div>
                <div class="form-group col-md-12 md-1">

                    <h6 class="m-0">{{trans('lang.settings')}}</h6>

                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="can_edit_application_setting"
                               v-model="check" value="can_edit_application_setting"
                               name="can_edit_application_setting"/>
                        <label class="custom-control-label" for="can_edit_application_setting">{{trans('lang.can_manage_application_setting')
                            }}</label> <br>
                    </div>
                    
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="can_edit_email_setting" v-model="check"
                               value="can_edit_email_setting" name="can_edit_email_setting"/>
                        <label class="custom-control-label" for="can_edit_email_setting">{{trans('lang.can_manage_email_setting')
                            }}</label> <br>
                    </div>

                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="can_edit_sms_setting" v-model="check"
                               value="can_edit_email_setting" name="can_edit_email_setting"/>
                        <label class="custom-control-label" for="can_edit_email_setting">{{trans('lang.can_manage_email_setting')
                            }}</label> <br>
                    </div>

                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="can_manage_sms_setting"
                               v-model="check" value="can_edit_sms_setting" name="can_manage_sms_setting"/>
                        <label class="custom-control-label" for="can_manage_sms_setting">{{trans('lang.can_manage_sms_setting')
                            }}</label> <br>
                    </div>

                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="can_edit_holi_day_setting"
                               v-model="check" value="can_edit_holi_day_setting" name="can_edit_holi_day_setting"/>
                        <label class="custom-control-label" for="can_edit_holi_day_setting">{{trans('lang.can_manage_holiday_setting')}}</label> <br>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="can_edit_break_time_setting"
                               v-model="check" value="can_edit_break_time_setting" name="can_edit_break_time_setting"/>
                        <label class="custom-control-label" for="can_edit_break_time_setting">{{trans('lang.can_manage_break_time_setting')}}</label> <br>
                    </div>

                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="can_edit_email_template" v-model="check"
                               value="can_edit_email_template" name="can_edit_email_template"/>
                        <label class="custom-control-label" for="can_edit_email_template">{{trans('lang.can_manage_email_template')
                            }}</label> <br>
                    </div>

                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="can_manage_custom_input" v-model="check"
                               value="can_manage_custom_input" name="can_manage_custom_input"/>
                        <label class="custom-control-label" for="can_manage_custom_input">{{trans('lang.can_manage_custom_input')
                            }}</label> <br>
                    </div>

                </div>
                <div class="form-group col-md-12 md-1">
                    <h6 class="m-0">{{trans('lang.service')}}</h6>

                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="can_add_service" v-model="check"
                               value="can_add_service" name="can_add_service"/>
                        <label class="custom-control-label" for="can_add_service">{{trans('lang.can_manage_service')
                            }}</label> <br>
                    </div>

                </div>
                <div class="form-group col-md-12 md-1">
                    <h6 class="m-0">{{trans('lang.booking')}}</h6>

                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="can_add_booking" v-model="check"
                               value="can_add_booking" name="can_add_booking"/>
                        <label class="custom-control-label" for="can_add_booking">{{trans('lang.can_add_booking_for_users')
                            }}</label> <br>
                    </div>

                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="can_manage_booking" v-model="check"
                               value="can_manage_booking" name="can_manage_booking"/>
                        <label class="custom-control-label" for="can_manage_booking">{{trans('lang.can_manage_booking')
                            }}</label> <br>
                    </div>

                </div>
                <div class="form-group col-md-12 md-1">
                    <h6 class="m-0">{{trans('lang.clients')}}</h6>

                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="can_manage_client_setting"
                               v-model="check" value="can_manage_client_setting" name="can_manage_client_setting"/>
                        <label class="custom-control-label" for="can_manage_client_setting">{{trans('lang.can_manage_client_settings')
                            }}</label> <br>
                    </div>
                </div>
                <div class="form-group col-md-12 md-1">
                    <h6 class="m-0">{{trans('lang.reports')}}</h6>

                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="can_see_reports" v-model="check"
                               value="can_see_reports" name="can_see_reports"/>
                        <label class="custom-control-label" for="can_see_reports">{{trans('lang.can_see_reports')
                            }}</label> <br>
                    </div>
                </div>
                <div class="form-group col-md-12 md-1">
                    <h6 class="m-0">{{trans('lang.payment_methods')}}</h6>

                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="can_manage_payment_methods"
                               v-model="check" value="can_manage_payment_methods" name="can_manage_payment_methods"/>
                        <label class="custom-control-label" for="can_manage_payment_methods">{{trans('lang.can_manage_payment_methods')
                            }}</label> <br>
                    </div>
                </div>

                <div class="col-12">
                    <button class="btn app-color mobile-btn" :disabled="errors.any()" type="submit"
                            @click.prevent="save()">{{ trans('lang.save') }}
                    </button>
                    <button class="btn cancel-btn mobile-btn" data-dismiss="modal" @click.prevent="">{{
                        trans('lang.cancel') }}
                    </button>
                </div>

            </form>
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
                title: '',
                check: [],
                add_role: '',
                delete_user: '',
                array: '',
                isSubmitted: false,
                error: false,
                errorInvalid: false,
                invalidMessage: '',
                is_disabled: false,
                hidePreLoader: true,
            }
        },
        created() {
            if (this.id) {
                this.getRoleData('/rolepermissions/' + this.id);
            }
        },
        methods: {
            save() {
                let instance = this;
                instance.$validator.validateAll().then((result) => {
                    if (result) {
                        instance.inputFields = {
                            title: instance.title,
                            permissions: instance.check,
                        };

                        if (instance.id) {
                            instance.postDataMethod('/addrole/' + instance.id, instance.inputFields);
                        } else {
                            instance.postDataMethod('/addrole', instance.inputFields);
                        }
                    }
                });
            },
            postDataThenFunctionality(response) {
                let instance = this;
                $(instance.modalID).modal('hide');
                instance.$hub.$emit('reloadDataTable');
            },
            getRoleData(route) {
                let instance = this;
                this.setPreLoader(false);
                this.axiosGet(route,
                    function (response) {
                        instance.setPreLoader(true);
                        instance.check = response.data.permissions;
                        instance.title = response.data.title;
                    },
                    function (errror) {
                        instance.setPreLoader(true);
                    },
                );
            },

        }
    }
</script>