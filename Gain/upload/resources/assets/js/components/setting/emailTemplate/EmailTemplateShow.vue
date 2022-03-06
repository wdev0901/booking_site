<template>
    <div>
        <div class="modal-layout-header">
            <div class="container-fluid p-0">
                <div class="row">
                    <div class="col-10">
                        <h4 class="m-0">{{ trans('lang.'+name) }}</h4>
                    </div>
                    <div class="col-2 text-right">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click.prevent="">
                            <span aria-hidden="true"><i class="la la-close"></i></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-layout-content">

            <pre-loader v-if="!hidePreLoader"></pre-loader>

            <div class="container-fluid p-0" v-show="hidePreLoader">
                <div class="row">
                    <div class="col-12">
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="subject">{{ trans('lang.subject') }}</label>
                                <input class="form-control" v-validate="'required'" id="subject" name="subject"
                                       :data-vv-as="trans('lang.subject_small')" type="text" v-model="subject">
                                <div class="heightError">
                                    <small class="text-danger" v-show="errors.has('subject')">{{
                                        errors.first('subject') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12">
                                <div class="form-group">
                                    <textarea class="form-control" rows="3" v-model="custom_content"
                                              id="custom-content"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12" v-if="name=='reset_password'">
                                <div class="chip" v-for="reset in available_variables.reset_password">
                                    {{reset}}
                                </div>
                            </div>
                            <div class="form-group col-12" v-if="name=='user_invitation'">
                                <div class="chip" v-for="reset in available_variables.user_invitation">
                                    {{reset}}
                                </div>
                            </div>
                            <div class="form-group col-12" v-if="name=='account_verification'">
                                <div class="chip" v-for="reset in available_variables.account_verification">
                                    {{reset}}
                                </div>
                            </div>
                            <div class="form-group col-12" v-if="name=='invoice'">
                                <div class="chip" v-for="reset in available_variables.invoice">
                                    {{reset}}
                                </div>
                            </div>
                        </div>
                        <div class="container-fluid">
                            <div class="row">
                                <button class="btn btn-primary app-color mobile-btn" type="submit"
                                        @click.prevent="save()">{{ trans('lang.save') }}
                                </button>
                                <button class="btn btn-secondary cancel-btn mobile-btn" data-dismiss="modal"
                                        @click.prevent="">{{ trans('lang.cancel') }}
                                </button>
                                <button class="btn btn-danger waves-effect waves-light mobile-btn ml-auto"
                                        v-if="isCustom" @click.prevent="restoreToDefault()">
                                    {{trans('lang.restore_default')}}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import axiosGetPost from '../../../helper/axiosGetPostCommon';

    export default {

        props: ['id', 'name', 'modalID'],
        extends: axiosGetPost,
        data() {
            return {
                subject: '',
                custom_content: '',
                isCustom: '',
                available_variables: {
                    reset_password: ["{app_name}", "{reset_password_link}"],
                    user_invitation: ["{app_name}", "{invited_by}", "{verification_link}"],
                    account_verification: ["{app_name}", "{first_name}", "{last_name}", "{verification_link}"],
                },
                restoreButtonTriggered: false,
            }
        },
        created() {
            if (this.id) {
                this.getEmailTemplateData('/gettemplatecontent/' + this.id);
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
            save() {

                this.$validator.validateAll().then((result) => {
                    if (result) {
                        this.inputFields = {
                            subject: this.subject,
                            custom_content: this.custom_content,
                            template_name: this.name
                        };
                        if (this.id) {
                            this.postDataMethod('/setcustomcontent/' + this.id, this.inputFields);
                        }
                    }
                });
            },
            getEmailTemplateData(route) {
                let instance = this;
                instance.setPreLoader(false);
                instance.axiosGet(route,
                    function (response) {
                        instance.subject = response.data.emailSubject;
                        instance.custom_content = response.data.content;
                        instance.isCustom = response.data.isCustom;
                        $("#custom-content").summernote("code", instance.custom_content);
                        instance.setPreLoader(true);
                    },
                    function (response) {
                        instance.setPreLoader(true);
                    },
                );
            },
            postDataThenFunctionality(response) {
                if (!this.restoreButtonTriggered) {
                    $(this.modalID).modal('hide');
                } else {
                    this.getEmailTemplateData('/gettemplatecontent/' + this.id);
                }
            },
            postDataCatchFunctionality(error) {
                this.showErrorAlert(error.data.message);
            },
            restoreToDefault() {
                this.custom_content = '';
                this.restoreButtonTriggered = true;
                this.save();
            },
        },
    }
</script>