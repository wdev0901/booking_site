<template>
    <div class="main-layout-wrapper">
        <nav aria-label="breadcrumb" v-if="profile.is_admin == 1 || profile.role_id > 0 ">
            <ol class="breadcrumb bg-transparent m-0">
                <li class="breadcrumb-item">
                    <span>{{trans('lang.profile_title')}}</span>
                </li>
            </ol>
        </nav>
        <div class="container-fluid p-0">
            <div class="row mr-0">
                <div class="col-12 col-md-4 col-lg-4 col-xl-3 pr-0">
                    <div class="main-layout-card mb-3">
                        <div class="main-layout-card-header">
                            <div class="main-layout-card-content-wrapper">
                                <div class="main-layout-card-header-contents">
                                    <div class="text-center profile-image-container">
                                        <div v-if="avatar">
                                            <img class="img-fluid profile-image" :src="avatar">
                                        </div>
                                        <div v-else>
                                            <img class="img-fluid profile-image"
                                                 :src="publicPath+'/uploads/profile/'+profile.avatar" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="main-layout-card-content">
                            <div class="text-center user-name-text">
                                <h5 class="m-0">{{ profile.first_name+' '+profile.last_name }} </h5>
                            </div>
                        </div>
                    </div>
                    <div class="main-layout-card mb-3">
                        <ul class="list-group list-group-flush list-group-border-fix">
                            <li class="list-group-item" :class="{'active-border': isActive==1}"
                                @click.prevent="isActive = 1, newTop= 0, newHeight = 49">
                                {{ trans('lang.profile') }}
                            </li>
                            <li class="list-group-item" :class="{'active-border': isActive==2}"
                                @click.prevent="isActive = 2, newTop= 49, newHeight = 49">
                                {{ trans('lang.change_password') }}
                            </li>
                            <span class="active">
                                <li class="list-group-item" :class="{'active-border': isActive==3}"
                                    @click.prevent="isActive = 3, newTop= 49, newHeight = 49">
                                    {{ trans('lang.google_calendar') }}
                                </li>
                            </span>
                        </ul>
                    </div>
                </div>
                <div class="col-12 col-md-8 col-lg-8 col-xl-9 pr-0">
                    <div class="main-layout-card">
                        <div class="main-layout-card-header">
                            <div class="main-layout-card-content-wrapper">
                                <div class="main-layout-card-header-contents">
                                    <h4 class="m-0" v-if="isActive == 1">{{ trans('lang.profile_title') }}</h4>
                                    <h4 class="m-0" v-if="isActive == 2">{{ trans('lang.change_password') }}</h4>
                                    <h4 class="m-0" v-if="isActive == 3">{{ trans('lang.google_calendar') }}</h4>
                                </div>
                            </div>
                        </div>
                        <div v-if="isActive == 1 || isActive == 2 || isActive == 3" class="main-layout-card-content">
                            <profile-form :profile="profile" v-show="isActive == 1"/>
                            <change-password :user="user" v-show="isActive == 2"/>
                            <google-calendar v-show="isActive == 3"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import axiosGetPost from '../../helper/axiosGetPostCommon';

    export default {
        extends: axiosGetPost,
        props: ['profile', 'user'],
        data() {
            return {
                first_name: '',
                last_name: '',
                email: '',
                date_of_birth: null,
                gender: '',
                password: '',
                avatar: '',
                isActive: 1,
            }
        },
        created() {
            let queryString = this.getParameterByName('is_active');
            if (queryString != null) this.isActive = queryString;
        },
        mounted() {
            if (sessionStorage.isActive == '3') {
                this.isActive = 3;
            }
            sessionStorage.clear();

            let instance = this;

            $('.datepicker').on('change', function () {
                var dob = $(this).val();
                instance.profile.date_of_birth = dob;
            });

            $('#gender').on('change', function () {
                var gender2 = $(this).val();
                instance.profile.gender = gender2
            });
        },
        methods: {
            getParameterByName(name, url) {
                if (!url) url = window.location.href;
                name = name.replace(/[\[\]]/g, '\\$&');
                let regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
                    results = regex.exec(url);
                if (!results) return null;
                if (!results[2]) return '';
                return decodeURIComponent(results[2].replace(/\+/g, ' '));
            },
            profileImageChange(event) {
                let input = event.target;
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = (e) => {
                        this.profile.avatar = e.target.result;
                        this.avatar = e.target.result;
                    }

                    reader.readAsDataURL(input.files[0]);
                    let fileName = input.files[0].name;
                    $('#profile-image-change').next('.custom-file-label').html(fileName);
                } else {
                    this.profile.avatar = '';
                    $('#profile-image-change').next('.custom-file-label').html(this.trans('lang.image_only'));
                }
            },
        },
    }
</script>