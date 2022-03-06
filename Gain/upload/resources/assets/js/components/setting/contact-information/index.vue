<template>
    <div class="main-layout-card">
        <div class="main-layout-card-header">
            <div class="main-layout-card-content-wrapper">
                <div class="main-layout-card-header-contents">
                    <h5 class="bluish-text no-margin">{{ trans('lang.contact_information') }}</h5>
                </div>
            </div>
        </div>
        <pre-loader v-if="!hidePreLoader"></pre-loader>
        <div class="main-layout-card-content" v-else>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="title">{{trans('lang.title') }}</label>
                    <input id="title" v-model="title" name="title" type="text"
                           class="form-control"
                           v-validate="'required'"
                           :class="{ 'is-invalid': errors.has('title') }">
                    <div class="heightError">
                        <small class="text-danger" v-show="errors.has('title')">
                            {{errors.first('title') }}
                        </small>
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label for="title">{{trans('lang.contact_email') }}</label>
                    <input id="contact_email" v-model="contact_email" name="contact_email" type="text"  data-vv-as="contact email"
                           class="form-control">
                </div>

                <div class="form-group col-md-6">
                    <label for="sub_title">{{trans('lang.sub_title') }}</label>
                    <textarea id="sub_title" class="form-control"
                              v-model="sub_title"
                              name="sub title"
                              v-validate="'required'"
                              :class="{ 'is-invalid': errors.has('sub title') }"></textarea>
                    <div class="heightError">
                        <small class="text-danger" v-show="errors.has('sub title')">
                            {{errors.first('sub title') }}
                        </small>
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label for="sub_title">{{trans('lang.address') }}</label>
                    <textarea id="address" class="form-control"
                              v-model="address" name="address"
                              ></textarea>
                </div>


                <div class="form-group col-md-6">
                    <label for="title">{{trans('lang.phone') }}</label>
                    <input id="phone" v-model="phone" name="phone" type="text"
                           class="form-control">
                </div>

                <div class="col-12">
                    <button class="btn app-color mobile-btn" type="submit" @click.prevent="save()">{{trans('lang.save') }}</button>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    import axiosGetPost from '../../../helper/axiosGetPostCommon';

    export default {
        extends: axiosGetPost,
        data() {
            return {
                contact_info: [],
                hidePreLoader: true,
            }
        },
        created() {
            this.readData();
        },
        methods: {
            setPreloader: function (type, action) {
                this.preloaderType = type;
                this.hidePreloader = action;
            },
            readData() {
                let instance = this;
                instance.hidePreLoader = false;
                instance.axiosGet('/get-contact-info',
                    function (response) {
                        instance.hidePreLoader = true;
                        instance.title =  response.data.title;
                        instance.sub_title =  response.data.sub_title;
                        instance.address =  response.data.address;
                        instance.contact_email =  response.data.email;
                        instance.phone =  response.data.phone;
                    },
                    function (error) {
                        instance.hidePreLoader = true;
                    });
            },
            save() {
                let instance = this;
                    instance.$validator.validateAll().then(result => {
                        if (result) {
                            instance.inputFields = {
                                title: instance.title,
                                sub_title: instance.sub_title,
                                address: instance.address,
                                contact_email: instance.contact_email,
                                phone: instance.phone,
                            };
                            instance.postDataMethod('/contact-info-update/1', instance.inputFields);
                        }
                    });
            },
            postDataCatchFunctionality(error) {
                this.showErrorAlert(error.data.message);
            },
        }
    }
</script>
