<template>
    <form>
        <pre-loader v-if="showPreLoader"/>
        <div v-else>
            <div class="form-row">
                <div class="col-12">
                    <div v-if="invalidToken">
                        <div class="">
                            {{trans('lang.cant_connect_google_calender_using_this_credentials')}}
                        </div>
                    </div>
                    <div class="">
                        <div>
                            {{trans('lang.get_your_credentials_from')}}
                            <a href="https://console.developers.google.com" target="_blank">
                                {{trans('lang.google_developers_console')}}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="form-group col-12">
                    <label for="client_id">{{ trans('lang.client_id') }}</label>
                    <input id="client_id"
                           v-validate="'required'"
                           v-model="clientId"
                           type="text"
                           data-vv-as="client id"
                           name="client-id"
                           class="form-control"
                           :class="{ 'is-invalid': submitted && errors.has('client-id') }">
                    <div class="heightError" v-if="submitted && errors.has('client-id')">
                        <small class="text-danger" v-show="errors.has('client-id')">
                            {{ errors.first('client-id') }}
                        </small>
                    </div>
                </div>
                <div class="form-group col-12">
                    <label for="client_secret">{{ trans('lang.client_secret') }}</label>
                    <input id="client_secret"
                           v-validate="'required'"
                           v-model="clientSecret"
                           type="text"
                           name="client-secret"
                           class="form-control"
                           :class="{ 'is-invalid': submitted && errors.has('client-secret') }">
                    <div class="heightError" v-if="submitted && errors.has('client-secret')">
                        <small class="text-danger" v-show="errors.has('client-secret')">
                            {{ errors.first('client-secret') }}
                        </small>
                    </div>
                </div>
                <div class="form-group col-12">
                    <label>{{trans('lang.please_use_this_url_to_create_your_google_app')}}</label>
                    <div>
                        <span class="font-weight-bold">{{ trans('lang.redirect_url') }}</span> : {{redirectUrl}}
                        <span class="ml-2">
                            <a href="#"
                               class="tooltip-copy"
                               @click.prevent="copyLink">
                                <i class="la la-copy purple-grey-text text-darken-1 icon-vertically-middle"/>
                            </a>
                        </span>
                    </div>
                </div>
                <div class="col-12">
                    <common-submit-button class="btn-profile-component"
                                          :buttonLoader="buttonLoader"
                                          :isDisabled="isDisabled"
                                          :isActiveText="isActiveText"
                                          buttonText="save"
                                          v-on:submit="save"/>
                </div>
            </div>
        </div>
    </form>
</template>

<script>
    import axiosGetPost from '../../helper/axiosGetPostCommon';

    export default {
        extends: axiosGetPost,
        data() {
            return {
                clientId: '',
                clientSecret: '',
                redirectUrl: '',
                showPreLoader: true,
                invalidToken: false,
                buttonLoader: false,
                isDisabled: false,
                isActiveText: false,
                submitted: false,
            }
        },
        created() {
            this.redirectUrl = this.publicPath + '/myprofile';
            this.getData()
        },
        mounted() {

        },
        methods: {
            save() {
                let instance = this;
                instance.submitted = true;
                instance.$validator.validateAll().then((result) => {
                    if (result) {
                        instance.buttonLoader = true;
                        instance.isDisabled = true;
                        instance.isActiveText = true;
                        instance.axiosPost('/save-google-calendar', {
                                clientId: instance.clientId,
                                clientSecret: instance.clientSecret,
                            },
                            function (response) {
                                if (!response.data.status) {
                                    instance.invalidToken = response.data.error;
                                } else {
                                    window.open(response.data.status, "_blank");
                                }
                                instance.showPreLoader = false;
                            },
                            function (error) {
                                instance.showPreLoader = false;
                            }
                        );
                    }
                });
            },
            getData() {
                let instance = this;
                instance.axiosGet('/get-calender-data',
                    function (response) {
                        instance.clientId = response.data.id;
                        instance.clientSecret = response.data.secret;
                        instance.invalidToken = response.data.error;
                        instance.renderTooltip();
                        instance.showPreLoader = false;
                    },
                    function (error) {
                        instance.showPreLoader = false;
                    }
                );
            },
            copyLink(alias, index) {
                alias = this.publicPath + '/myprofile';
                let $temp = $("<input>");
                $("body").append($temp);
                $temp.val(alias).select();
                document.execCommand("copy");
                $temp.remove();
                setTimeout( function() {
                    $('.tooltip-copy').tooltip('dispose').tooltip({title: 'Copied'}).tooltip('show');
                }, 100);
            }
        }
    }
</script>