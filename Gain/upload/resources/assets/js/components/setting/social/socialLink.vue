<template>

    <div class="main-layout-card">
        <div class="main-layout-card-header">
            <div class="main-layout-card-content-wrapper">
                <div class="main-layout-card-header-contents">
                    <h5 class="bluish-text m-0">{{ trans('lang.social_links') }}</h5>
                </div>
            </div>
        </div>
        <pre-loader v-if="preloader"/>
        <div class="main-layout-card-content" v-else>
            <form>
                <div class="form-row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="facebook">{{ trans('lang.facebook') }}</label>
                            <input type="text" class="form-control" id="facebook" name="facebook"
                                   v-model="item.facebook">
                        </div>

                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="instagram">{{ trans('lang.instagram') }}</label>
                            <input type="text" class="form-control" id="instagram" name="instagram"
                                   v-model="item.instagram">
                        </div>

                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="linkedin">{{ trans('lang.linkedin') }}</label>
                            <input type="text" class="form-control" id="linkedin" name="linkedin"
                                   v-model="item.linkedin">

                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="twitter">{{ trans('lang.twitter') }}</label>
                            <input type="text" class="form-control" id="twitter" name="twitter" v-model="item.twitter">

                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="google_plus">{{ trans('lang.google_plus') }}</label>
                            <input type="text" class="form-control" id="google_plus" name="google_plus"
                                   v-model="item.google_plus">

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="youtube">{{ trans('lang.youtube') }}</label>
                            <input type="text" class="form-control" id="youtube" name="youtube" v-model="item.youtube">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="pinterest">{{ trans('lang.pinterest') }}</label>
                            <input type="text" class="form-control" id="pinterest" name="pinterest"
                                   v-model="item.pinterest">

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tumblr">{{ trans('lang.tumblr') }}</label>
                            <input type="text" class="form-control" id="tumblr" name="tumblr" v-model="item.tumblr">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="flickr">{{ trans('lang.flickr') }}</label>
                            <input type="text" class="form-control" id="flickr" name="flickr" v-model="item.flickr">
                        </div>
                    </div>

                </div>
                <div class="form-row">
                    <div class="form-group col-md-11">
                        <button class="btn btn-primary app-color mobile-btn"
                                @click.prevent="updateSocialLink()" type="submit">{{
                            trans('lang.save') }}
                        </button>
                    </div>
                </div>

            </form>
        </div>

    </div>
</template>
<script>
    import axiosGetPost from '../../../helper/axiosGetPostCommon';

    export default {
        extends: axiosGetPost,
        data() {
            return {
                item: {
                    facebook: '',
                    instagram: '',
                    linkedin: '',
                    twitter: '',
                    google_plus: '',
                    youtube: '',
                    pinterest: '',
                    tumblr: '',
                    flickr: '',
                },
                preloader: false,
                submitted: false,

            }
        },
        mounted() {
            this.readsociallinkData();
        },
        methods: {
            readsociallinkData() {
                let instance = this;
                instance.preloader = true;
                this.axiosGet('/social-link',
                    function (response) {

                        instance.item = response.data;
                        instance.preloader = false;
                    },
                    function (error) {
                        instance.preloader = false;
                    },
                );
            },
            updateSocialLink() {
                let instance = this;
                instance.preloader = true;
                for (let key in this.item) {
                    if (key == "id" || key == "created_at" || key == "updated_at") {

                    } else {
                        if (this.item[key] == "" || this.item[key] == null) {

                            this.item[key] = null;
                        } else {
                            if (!_.startsWith(this.item[key], 'https://')) {
                                this.item[key] = 'https://' + this.item[key];
                            }
                        }
                    }
                }
                this.inputFields = {
                    facebook: this.item.facebook,
                    instagram: this.item.instagram,
                    linkedin: this.item.linkedin,
                    twitter: this.item.twitter,
                    google_plus: this.item.google_plus,
                    youtube: this.item.youtube,
                    pinterest: this.item.pinterest,
                    tumblr: this.item.tumblr,
                    flickr: this.item.flickr,
                };
                this.postDataMethod('/social-link-update', this.inputFields);
            },
            postDataThenFunctionality(response) {
                this.preloader = false;
            },
            postDataCatchFunctionality(error) {
                this.showErrorAlert(error.data.message);
            },
        }
    }
</script>
