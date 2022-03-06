<template>
    <div class="salon-landing-wrapper">
        <!--Header area-->
        <div class="header-top-section">
            <nav class="navbar navbar-expand-lg fixed-top">
                <div class="container">
                    <a class="navbar-brand landing-navbar-brand js-scroll-trigger" href="#"
                       @click.prevent="navigateToHomeSection">
                        <img :src="publicPath+'/uploads/logo/'+appLogo" alt="" class="logo">
                    </a>
                    <button class="navbar-toggler"
                            type="button"
                            @click.prevent="openMobileMenu">
                        <i class="las la-bars"/>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarResponsive">
                        <ul class="navbar-nav ml-auto scroll-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="#home">
                                    <span>{{ trans('lang.home') }}</span>
                                </a>
                            </li>
                            <li class="nav-item" v-show="!hideService">
                                <a class="nav-link" href="#services">
                                    <span>{{ trans('lang.services') }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#contact">
                                    <span>{{ trans('lang.contact') }}</span>
                                </a>
                            </li>

                            <li v-if="user === null && checksignup.can_signup === 1" class="nav-item">
                                <a class="nav-link" :href="publicPath+'/register'">
                                    {{ trans('lang.sign_up') }}
                                </a>
                            </li>
                            <li v-if="user === null" class="nav-item">
                                <a class="nav-link" :href="publicPath+'/login'">
                                    {{ trans('lang.sign_in') }}
                                </a>
                            </li>
                            <li v-if="user" class="nav-item">
                                <a href="#" class="nav-link" @click="dashboard">
                                    <span>{{ trans('lang.my_account') }}</span>
                                </a>
                            </li>
                            <li v-if="user" class="nav-item">
                                <a href="#" class="nav-link" @click="logout">
                                    <span>{{ trans('lang.logout_nv') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!--Mobile Menu-->
            <div id="mobileMenuWrapper" class="mobile-menu-wrapper">
                <nav class="navbar">
                    <div class="menu-header-content">
                        <a class="navbar-brand landing-navbar-brand" href="">
                            <img :src="publicPath+'/uploads/logo/'+appLogo" alt="" class="logo">
                        </a>
                        <a href="#" class="close-btn" @click.prevent="closeMobileMenu()">
                            <i class="la la-close"/>
                        </a>
                    </div>
                    <div class="menu-layout-content">
                        <ul class="navbar-nav ml-auto scroll-nav">
                            <li class="nav-item" @click.prevent="closeMobileMenu">
                                <a class="nav-link" href="#home">
                                    <span>{{ trans('lang.home') }}</span>
                                </a>
                            </li>
                            <li class="nav-item" @click.prevent="closeMobileMenu" v-show="!hideService">
                                <a class="nav-link" href="#services">
                                    <span>{{ trans('lang.services') }}</span>
                                </a>
                            </li>
                            <li class="nav-item" @click.prevent="closeMobileMenu">
                                <a class="nav-link" href="#contact">
                                    <span>{{ trans('lang.contact') }}</span>
                                </a>
                            </li>
                            <li v-if="user === null" class="nav-item">
                                <a class="nav-link" :href="publicPath+'/register'">
                                    {{ trans('lang.sign_up') }}
                                </a>
                            </li>
                            <li v-if="user === null" class="nav-item">
                                <a class="nav-link" :href="publicPath+'/login'">
                                    {{ trans('lang.sign_in') }}
                                </a>
                            </li>
                            <li v-if="user" class="nav-item">
                                <a href="#" class="nav-link" @click="dashboard">
                                    <span>{{ trans('lang.my_account') }}</span>
                                </a>
                            </li>
                            <li v-if="user" class="nav-item">
                                <a href="#" class="nav-link" @click="logout">
                                    <span>{{ trans('lang.logout_nv') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
            <div id="mySidenavClose"
                 class="mobile-menu-overlay"
                 :class="{'d-none':isMobileMenuActive === false}"
                 @click.prevent="closeMobileMenu()">
            </div>
        </div>
        <pre-loader v-if="!fullPageLoader"/>
        <div v-else>
            <!--Service booking area-->
            <service_booking_landing :user="user"
                                     :payment_methods="paymentMethods"
                                     :landing_page_header="landing_page_header"
                                     :landing_page_message="landing_page_message"
                                     :checksignup="checksignup"
                                     :service="service"
                                     @serviceLength="serviceLength"/>

            <!--Footer area-->
            <div id="contact" class="footer-section">
                <div class="footer-top-section">
                    <div class="container">
                        <div class="mb-5">
                            <h2 class="section-title mb-0">
                                {{contactInfo.title}}
                            </h2>
                            <p class="subtitle">{{contactInfo.sub_title}}</p>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-8">
                                <pre-loader v-if="contactpreloader"/>
                                <form v-else>
                                    <div v-if="confirmAlert" id="confirmAlert" class="alert alert-dismissible fade show"
                                         role="alert">
                                        {{ trans('lang.contact_confirm_message')}}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-placeholder" for="email">
                                            {{ trans('lang.your_email_here')}}
                                        </label>
                                        <input type="email" id="email" class="form-control" name="email"
                                               v-validate="'required|email'"
                                               data-vv-as="email" v-model="email">

                                        <div class="heightError">
                                            <small class="text-danger" v-show="errors.has('email')">
                                                {{ errors.first('email') }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-placeholder" for="subject">
                                            {{ trans('lang.subject')}}
                                        </label>
                                        <input type="text" id="subject" class="form-control" name="subject"
                                               v-validate="'required'"
                                               data-vv-as="subject" v-model="subject">

                                        <div class="heightError">
                                            <small class="text-danger" v-show="errors.has('subject')">
                                                {{ errors.first('subject') }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-placeholder" for="message">
                                            {{ trans('lang.your_text_here')}}
                                        </label>
                                        <textarea id="message" class="form-control" rows="9" name="message"
                                                  v-validate="'required'"
                                                  data-vv-as="message" v-model="message"/>

                                        <div class="heightError">
                                            <small class="text-danger" v-show="errors.has('message')">
                                                {{ errors.first('message') }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="form-group mb-0">
                                        <button type="submit" class="btn btn-landing" @click.prevent="save()">
                                            <span>{{ trans('lang.send')}}</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="widget-wrapper text-center">
                                    <div class="widget-icon-wrapper">
                                        <i class="las la-map-marker la-2x"/>
                                    </div>
                                    <h5>{{ trans('lang.visit_us')}}</h5>
                                    <p>
                                        {{contactInfo.address}}
                                    </p>
                                </div>
                                <div class="widget-wrapper text-center">
                                    <div class="widget-icon-wrapper">
                                        <i class="las la-phone la-2x"/>
                                    </div>
                                    <h5>{{ trans('lang.call_us')}}</h5>
                                    <p>
                                        {{contactInfo.phone}}
                                    </p>
                                </div>
                                <div class="widget-wrapper text-center">
                                    <div class="widget-icon-wrapper">
                                        <i class="las la-envelope la-2x"/>
                                    </div>
                                    <h5>{{ trans('lang.mail_us')}}</h5>
                                    <p>
                                        {{contactInfo.email}}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--footer bottom-->
                <div class="footer-bottom-section">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <ul class="nav justify-content-center justify-content-md-start justify-content-lg-start footer-nav-menu">
                                    <li class="nav-item">
                                        <a class="nav-link" href="#home"
                                           @click="smoothScrollToSectionFromFooter('#home')">
                                            {{ trans('lang.home') }}
                                        </a>
                                    </li>
                                    <li class="nav-item" v-show="!hideService">
                                        <a class="nav-link" href="#services"
                                           @click="smoothScrollToSectionFromFooter('#services')">
                                            {{ trans('lang.services') }}
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#contact"
                                           @click="smoothScrollToSectionFromFooter('#contact')">
                                            {{ trans('lang.contact') }}
                                        </a>
                                    </li>

                                    <li v-if="user === null && checksignup.can_signup === 1" class="nav-item">
                                        <a class="nav-link" href="register">
                                            {{ trans('lang.sign_up') }}
                                        </a>
                                    </li>
                                    <li v-if="user === null" class="nav-item">
                                        <a class="nav-link" href="login">
                                            {{ trans('lang.sign_in') }}
                                        </a>
                                    </li>


                                    <li v-if="user" class="nav-item">
                                        <a class="nav-link" href="#" @click="dashboard">
                                            {{ trans('lang.my_account') }}
                                        </a>
                                    </li>
                                    <li v-if="user" class="nav-item">
                                        <a class="nav-link" href="#" @click="logout">
                                            {{ trans('lang.logout_nv') }}
                                        </a>
                                    </li>

                                </ul>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <ul class="nav justify-content-center justify-content-md-end justify-content-lg-end text-white footer-nav-social">
                                    <li v-if="custom_content !== null" class="nav-item">
                                        <a class="nav-link service-policy" href="#" data-toggle="modal"
                                           data-target="#servicePolicyModal">
                                            <span :class="{'service-policy-link': custom_content !== null && hasSocialLink }">
                                                Service Policy
                                            </span>
                                        </a>
                                    </li>
                                    <li v-if="sociallinkdata.twitter != null" class="nav-item">
                                        <a class="nav-link social-link" :href="sociallinkdata.twitter" target="_blank">
                                            <i class="lab la-twitter"/>
                                        </a>
                                    </li>
                                    <li v-if="sociallinkdata.facebook != null" class="nav-item">
                                        <a class="nav-link social-link" :href="sociallinkdata.facebook" target="_blank">
                                            <i class="lab la-facebook-f"/>
                                        </a>
                                    </li>
                                    <li v-if="sociallinkdata.youtube != null" class="nav-item">
                                        <a class="nav-link social-link" :href="sociallinkdata.youtube" target="_blank">
                                            <i class="lab la-youtube"/>
                                        </a>
                                    </li>
                                    <li v-if="sociallinkdata.linkedin != null" class="nav-item">
                                        <a class="nav-link social-link" :href="sociallinkdata.linkedin" target="_blank">
                                            <i class="lab la-linkedin-in"/>
                                        </a>
                                    </li>
                                    <li v-if="sociallinkdata.instagram != null" class="nav-item">
                                        <a class="nav-link social-link" :href="sociallinkdata.instagram"
                                           target="_blank">
                                            <i class="lab la-instagram"/>
                                        </a>
                                    </li>
                                    <li v-if="sociallinkdata.google_plus != null" class="nav-item">
                                        <a class="nav-link social-link" :href="sociallinkdata.google_plus"
                                           target="_blank">
                                            <i class="lab la-google-plus"/>
                                        </a>
                                    </li>
                                    <li v-if="sociallinkdata.pinterest != null" class="nav-item">
                                        <a class="nav-link social-link" :href="sociallinkdata.pinterest"
                                           target="_blank">
                                            <i class="lab la-pinterest"/>
                                        </a>
                                    </li>
                                    <li v-if="sociallinkdata.tumblr != null" class="nav-item">
                                        <a class="nav-link social-link" :href="sociallinkdata.tumblr" target="_blank">
                                            <i class="lab la-tumblr"/>
                                        </a>
                                    </li>
                                    <li v-if="sociallinkdata.flickr != null" class="nav-item">
                                        <a class="nav-link social-link" :href="sociallinkdata.flickr" target="_blank">
                                            <i class="lab la-flickr"/>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-12">
                                <p class="text-white-50 mb-0 copyright-text">
                                    {{ copyright_text }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Service Policy Modal -->
        <div class="modal modal-landing fade" id="servicePolicyModal" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-body justify-content-center">
                        <h4 class="modal-title text-center mb-4" id="exampleModalLabel">
                            {{ trans('lang.service_policy') }}
                        </h4>
                        <img :src="publicPath+'/images/service-policy.png'" class="img-fluid mx-auto d-block mb-4"
                             width="50%" alt="">
                        <div class="text-justify mb-4" v-html="custom_content"/>
                        <div class="row justify-content-center">
                            <div class="col-4">
                                <button type="button" class="btn btn-block btn-landing" data-dismiss="modal">
                                    {{ trans('lang.close_btn') }}
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

    import axiosGetPost from '../../helper/axiosGetPostCommon';
    import {
        setServiceIdToCookie,
        getServiceIdFromCookie,
        deleteServiceIdFromCookie
    } from './helper/SalonLandingCookieSet.js';

    export default {
        extends: axiosGetPost,
        props: ['copyright_text', 'landing_page_header', 'landing_page_message', 'sessionstatus', 'checksignup', 'user', 'service'],
        data() {
            return {
                isActive: false,
                modalID: '#add-edit-modal',
                selectedItemId: '',
                custom_content: '',
                email: '',
                subject: '',
                message: '',
                contactInfo: {},
                hasSocialLink: false,
                contactpreloader: false,
                sociallinkdata: {
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
                timePreLoader: true,
                submitted: false,
                confirmAlert: false,
                fullPageLoader: true,
                paymentMethods: [],
                hideService: false,
                hasData: value => {
                    return !_.isEmpty(value)
                },
                isMobileMenuActive: false,
            }
        },
        created() {
            this.getContactInfo('/get-contact-info');
            this.getSocialLinkData('/getAllsocialData');
            this.getServicePolicy();
            this.getPaymentMethods();
        },
        methods: {
            navigateToHomeSection() {
                $("html, body").animate(
                    {
                        scrollTop: $("#home").offset().top
                    }, 600,
                    function () {
                        window.location.hash = "";
                    }
                );
            },
            getServicePolicy() {
                let instance = this;
                instance.fullPageLoader = false;
                this.axiosGet('/getfrontservicepolicy',
                    function (response) {
                        instance.custom_content = response.data.custom_content;
                        instance.fullPageLoader = true;
                    },
                    function (error) {
                        instance.fullPageLoader = true;
                    },
                );
            },
            save() {
                let instance = this;
                instance.$validator.validateAll().then((result) => {
                    if (result) {
                        instance.inputFields = {
                            email: instance.email,
                            subject: instance.subject,
                            message: instance.message,
                        };
                        instance.contactpreloader = true;
                        instance.postDataMethod('/contact-us', instance.inputFields);
                    }
                });
            },
            getSocialLinkData(route) {
                let instance = this;
                instance.axiosGet(route,
                    function (response) {
                        
                        instance.sociallinkdata = response.data;

                        if (instance.sociallinkdata.facebook != null ||
                            instance.sociallinkdata.instagram != null ||
                            instance.sociallinkdata.linkedin != null ||
                            instance.sociallinkdata.twitter != null ||
                            instance.sociallinkdata.google_plus != null ||
                            instance.sociallinkdata.youtube != null ||
                            instance.sociallinkdata.pinterest != null ||
                            instance.sociallinkdata.tumblr != null ||
                            instance.sociallinkdata.flickr != null
                        ) instance.hasSocialLink = true;
                    },
                    function (error) {

                    },
                );
            },
            getContactInfo(route) {
                let instance = this;
                this.axiosGet(route,
                    function (response) {
                        instance.contactInfo = response.data;
                    },
                    function (error) {

                    }
                );
            },
            postDataThenFunctionality(response) {
                let instance = this;
                instance.email = '';
                instance.subject = '';
                instance.message = '';
                instance.$validator.reset();
                this.contactpreloader = false;
                instance.confirmAlert = true;
                setTimeout(function () {
                    $('#confirmAlert').delay(5000).fadeOut('slow');
                }, 100)
            },
            postDataCatchFunctionality(error) {
                this.$validator.reset();
                this.contactpreloader = false;
            },
            logout() {
                this.redirect('/logout');
                deleteServiceIdFromCookie();
            },
            dashboard() {
                this.redirect('/dashboard');
            },
            smoothScrollToSectionFromFooter(id) {
                $("html, body").animate({
                        scrollTop: $(id).offset().top - 60
                    }, 1000, function () {
                        /*window.location.hash = "";*/
                    }
                );
            },
            getPaymentMethods() {
                let instance = this;
                this.axiosGet('/getpmethods',
                    function (response) {
                        instance.paymentMethods = response.data;
                    },
                    function (response) {
                    },
                );
            },
            serviceLength(value) {
                if (value < 1) this.hideService = true;
                else this.hideService = false;
            },
            openMobileMenu() {
                this.isMobileMenuActive = true;
                if (window.innerWidth > 667) {
                    $('#mobileMenuWrapper').width('300px');
                    $('html, body').css('overflowY', 'hidden');
                } else {
                    $('#mobileMenuWrapper').width('60vw');
                    $('html, body').css('overflowY', 'hidden');
                }
            },
            closeMobileMenu() {
                $('#mobileMenuWrapper').width('0');
                $('html, body').css('overflowY', 'auto');
                this.isMobileMenuActive = false;
            },
        }
    }
</script>
