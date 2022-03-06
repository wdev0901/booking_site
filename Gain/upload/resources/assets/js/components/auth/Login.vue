<template>
    <div class="back-img"
         :style="{'background-image': 'url(' + publicPath+'/images/' + 'background/background-image.jpeg' + ')'}">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-12 col-md-6 offset-md-6 col-lg-4 offset-lg-8 col-xl-4 offset-xl-8">
                    <div class="sign-in-sign-up-content">
                        <form class="sign-in-sign-up-form">
                            <div class="text-center mb-4 application-logo">
                                <img :src="publicPath+'/uploads/logo/'+appLogo" alt="" class="img-fluid logo">
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <h6 class="text-center mb-0">
                                        {{ trans('lang.hi_there') }}
                                    </h6>
                                    <label class="text-center d-block">{{ trans('lang.sign_in_to_your_dashboard')
                                        }}</label>
                                </div>
                            </div>
                            <div v-if="alertMessage.length>0" class="alertBranch">
                                <div class="alert alert-warning alertBranch" role="alert">
                                    {{alertMessage}}
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label for="email">{{ trans('lang.email_or_phone') }}</label>
                                    <input id="email"
                                           v-model="email"
                                           type="text"
                                           name="email"
                                           class="form-control"
                                           v-validate="'required'"
                                           :placeholder="trans('lang.enter_email_or_phone')"
                                           :class="{ 'is-invalid': submitted && errors.has('email') }"/>

                                    <div class="heightError" v-if="submitted && errors.has('email')">
                                        <small class="text-danger" v-show="errors.has('email')">
                                            {{ errors.first('email') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label for="password">{{ trans('lang.login_password') }}</label>
                                    <input id="password"
                                           ref="password"
                                           v-model="password"
                                           name="password"
                                           type="password"
                                           class="form-control"
                                           v-validate="'required'"
                                           :placeholder="trans('lang.enter_password')"
                                           :class="{ 'is-invalid': submitted && errors.has('password') }"/>
                                    <div class="heightError">
                                        <small class="text-danger" v-show="errors.has('password')">
                                            {{ errors.first('password') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row loginButton">
                                <div class="form-group col-12">
                                    <common-submit-button class="btn-block text-center auth-button"
                                                          :buttonLoader="buttonLoader"
                                                          :isDisabled="isDisabled"
                                                          :isActiveText="isActiveText"
                                                          buttonText="login"
                                                          v-on:submit="loginPost"/>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-6">
                                    <a @click="redirectHome"
                                       href="#"
                                       class="bluish-text">
                                        <i class="las la-home"/>
                                        {{ trans('lang.back_to_home')}}
                                    </a>
                                </div>
                                <div class="form-group col-6 text-right">
                                    <a href="#"
                                       @click="passwordReset"
                                       class="bluish-text">
                                        <i class="las la-lock"/>
                                        {{ trans('lang.forgot_password') }}
                                    </a>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-12">
                                    <p class="text-center mt-5">
                                        {{ copyright_text }}
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import axiosGetPost from "../../helper/axiosGetPostCommon";

    export default {
        extends: axiosGetPost,
        props: ['copyright_text', 'checkemail', 'checkpass'],
        data() {
            return {
                email: this.checkemail,
                password: this.checkpass,
                remember: true,
                isActive: "active",
                // new
                buttonLoader: false,
                isDisabled: false,
                isActiveText: false,
                submitted: false,
                alertMessage: "",
                preLoaderType: "load",
                hidePreLoader: false
            };
        },
        methods: {
            checkFocus(id) {
                return $(id).is(":focus");
            },
            loginPost() {
                //(this.submitted = true),
                   // this.$validator.validateAll().then(result => {
                       // if (result) {
                            this.inputFields = {
                                email: this.email,
                                password: this.password
                            };
                            this.buttonLoader = true;
                            this.isDisabled = true;
                            this.isActiveText = true;
                            this.loginPostMethod("/login", {
                                email: this.email,
                                password: this.password
                            });
                        //}
                    //});
            },
            passwordReset() {
                this.redirect("/password/reset");
            },
            redirectHome() {
                this.redirect("/");
            },
            loginPostSucces(response) {
                let instance = this;
                instance.redirect("/dashboard");
            },
            loginPostError(response) {
                let instance = this;
                instance.buttonLoader = false;
                instance.isDisabled = false;
                instance.isActiveText = false;
                instance.alertMessage = response.data.errors.email[0];
            }
        }
    };
</script>