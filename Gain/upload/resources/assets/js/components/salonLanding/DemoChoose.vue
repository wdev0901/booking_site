<template>
    <div id="demoChoosePage">
        <header>
            <nav class="navbar navbar-light bg-light">
                <div class="container px-0">
                    <a class="navbar-brand" href="#">
                        <img src="uploads/logo/default-logo.png" alt="">
                    </a>

                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="#home">
                                <button type="button" class="btn btn-outline-info">
                                    {{ trans('lang.buy_now') }}
                                </button>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <main>
            <section class="application-types">
                <div class="container">
                    <div class="row justify-content-center align-items-center">
                        <div class="col-12 col-sm-6 col-md-5 col-lg-5 col-xl-5">
                            <div class="demo-card card-1 text-center">
                                <div class="demo-card-header">
                                    <img src="uploads/general.png" class="img-fluid mx-auto d-block" width="60%" alt="">
                                    <div class="mx-auto img-underline"></div>
                                    <h3 class="mb-0">{{ trans('lang.general') }}</h3>
                                </div>
                                <div class="body-text my-5">
                                    Works for any service. <br>
                                    Supports daily & hourly service. <br>
                                    Easy to book available slots only. <br>
                                    Useful to manage booking.
                                </div>
                                <div>
                                    <a href="" class="btn btn-demo-card d-inline-flex"
                                       @click.prevent="redirectToApplicationType('general')">
                                        <roller-loader v-if="buttonGeneralLoader"/>
                                        {{ trans('lang.see_demo') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-5 col-lg-5 col-xl-5">
                            <div class="demo-card card-2 text-center">
                                <div class="demo-card-header">
                                    <img src="uploads/saloon.png" class="img-fluid mx-auto d-block" width="60%" alt="">
                                    <div class="mx-auto img-underline"></div>
                                    <h3 class="mb-0">{{ trans('lang.salon') }}</h3>
                                </div>
                                <div class="body-text my-5">
                                    Dedicated for salon only. <br>
                                    Interactive booking page for user. <br>
                                    Consider price in settings for child. <br>
                                    Easy to add service assistance.
                                </div>
                                <div>
                                    <a href="" class="btn btn-demo-card d-inline-flex"
                                       @click.prevent="redirectToApplicationType('salon')">
                                        <roller-loader v-if="buttonSalonLoader"/>
                                        {{ trans('lang.see_demo') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</template>

<script>
    import axiosGetPost from '../../helper/axiosGetPostCommon';

    export default {
        extends: axiosGetPost,
        name: "demoChoose",
        data() {
            return {
                buttonSalonLoader: false,
                buttonGeneralLoader: false,
            }
        },
        methods: {
            redirectToApplicationType(type) {
                let instance = this,
                    businessType;
                if (type === 'general') {
                    businessType = type;
                    instance.buttonGeneralLoader = true;
                }
                else {
                    businessType = type;
                    instance.buttonSalonLoader = true;
                }

                instance.axiosPost('/update-business-type', {
                        business_type: businessType,
                    },
                    function (response) {
                        instance.redirect('/');
                    },
                    function (error) {

                    }
                );
            }
        }
    }
</script>