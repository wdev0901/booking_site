<template>
    <div class="main-layout-card">
        <div class="main-layout-card-header">
            <div class="main-layout-card-content-wrapper">
                <div class="main-layout-card-header-contents">
                    <h5 class="bluish-text m-0">{{ trans('lang.service_policy') }}</h5>
                </div>
            </div>
        </div>
        <pre-loader v-if="preloader"/>
        <div v-show="!preloader"  class="main-layout-card-content">
            <div class="form-row">
                <div class="form-group col-md-12">
                    <textarea id="custom-content"
                              rows="10"
                              class="form-control"
                              v-model="custom_content"/>
                </div>

                <div class="col-12">
                    <button class="btn app-color mobile-btn" type="submit" @click.prevent="saveServicepolicy()">
                        {{trans('lang.save') }}
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
        data() {
            return {
                custom_content: '',
                id: '',
                preloader: false,
            }
        },
        created(){
            this.readServicePolicy();
        },
        mounted() {
            this.loadSummerNote();
        },
        methods: {
            loadSummerNote(){
                let instance = this;
                $("#custom-content").summernote(
                    {
                        callbacks: {
                            onChange: function () {
                                let code = $(this).summernote("code");
                                instance.custom_content = code;
                            }
                        }
                    }
                );
            },
            readServicePolicy() {
                let instance = this;
                instance.preloader = true;
                this.axiosGet('/servicepolicy',
                    function (response) {
                        instance.custom_content = response.data.custom_content;
                        instance.id = response.data.id;
                        setTimeout(function(){
                            $("#custom-content").summernote("code", instance.custom_content);
                        });
                        //$("#custom-content").summernote("code", instance.custom_content);
                        instance.preloader = false;
                    },
                    function (error) {
                        instance.preloader = false;
                    },
                );
            },
            saveServicepolicy() {
                let instance = this;
                instance.preloader = true;

                let data = {
                    custom_content: instance.custom_content,
                };
                if (this.id) {
                    this.postDataMethod('/servicepolicy-update/' + this.id, data);
                }

            },

            postDataThenFunctionality(response) {
                setTimeout(function(){
                    $("#custom-content").summernote("code", this.custom_content);
                });
                this.preloader = false;
            },
            postDataCatchFunctionality(error) {
                this.showErrorAlert(error.data.message);
                this.preloader = false;
            },
        }
    }
</script>
