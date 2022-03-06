<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-light fixed-top">
            <a href="#" class="d-lg-none app-color-text" @click.prevent="showSideNavAction(true)" v-if="!showSideNav">
                <i class="la la-navicon la-2x"/>
            </a>
            <a href="#" class="d-lg-none app-color-text" @click.prevent="showSideNavAction(false)" v-else>
                <i class="la la-close la-2x"/>
            </a>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item client-new-booking">
                    <a class="nav-link" href="#" @click="newBooking">
                        <span>
                            {{ trans('lang.new_booking') }}
                        </span>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle hide-dropdown-icon"
                       href="#"
                       id="navbar-notification-dropdown"
                       data-toggle="dropdown"
                       aria-haspopup="true"
                       aria-expanded="false">
                        <i class="la la-bell la-2x"/>
                    </a>
                    <div class="navbar-notification-container dropdown-menu dropdown-menu-right animated bounceInDown profile-dropdown border-0 p-0"
                         aria-labelledby="navbar-notification-dropdown">
                        <div class="ticker"></div>
                        <div class="p-2">
                            {{ trans('lang.your_notifications') }}
                        </div>
                        <div class="dropdown-divider m-0"></div>
                        <a v-for="item in items"
                           :href="publicPath+'/myprofile'"
                           @click.prevent="upNofify(item.id,item.booking_id)"
                           class="dropdown-item d-flex align-items-center p-2">
                            <i class="la la-user la-2x pr-3"/>
                            {{ trans('lang.profile_title') }}
                        </a>
                        <div class="text-center p-2"
                             v-if="!hideCircleLoader && items.length == 0">
                            {{ trans('lang.you_have_no_notifications')}}
                        </div>
                        <div class="dropdown-divider m-0"></div>
                        <div class="text-center py-2">
                            <a href="#" @click.prevent="allNoti()" class="text-center">
                                {{ trans('lang.view_all') }}
                            </a>
                        </div>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle hide-dropdown-icon"
                       href="#"
                       id="navbar-profile-dropdown"
                       data-toggle="dropdown"
                       aria-haspopup="true"
                       aria-expanded="false"
                       @click.prevent="showSideNavAction(false)">
                        <img :src="publicPath+'/uploads/profile/'+profile.avatar" alt class="rounded-circle avatar"/>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right animated bounceInDown profile-dropdown border-0 p-0"
                         aria-labelledby="navbar-profile-dropdown">
                        <div class="ticker"></div>
                        <img :src="publicPath+'/uploads/profile/'+profile.avatar" alt
                             class="rounded-circle avatar-large"/>
                        <div class="user-name">
                            <div>{{ profile.first_name+' '+profile.last_name }}</div>
                        </div>
                        <div class="dropdown-divider m-0"></div>
                        <a :href="publicPath+'/myprofile'"
                           class="dropdown-item d-flex align-items-center p-2">
                            <i class="la la-user la-2x pr-3"></i>
                            {{ trans('lang.profile_title') }}
                        </a>
                        <a :href="publicPath+'/logout'"
                           class="dropdown-item d-flex align-items-center p-2">
                            <i class="la la-sign-out la-2x pr-3"></i>
                            {{ trans('lang.logout_nv') }}
                        </a>
                    </div>
                </li>
            </ul>
            <div class="side-nav"
                 :class="{'side-nav-animate-show':showSideNav, 'side-nav-animate-hide': showSideNav===false}">
                <ul>
                    <li>
                        <a href="#" @click="newBooking"><i class="la la-plus-circle la-2x"></i>
                            <div>{{ trans('lang.new_booking') }}</div>
                        </a>
                    </li>
                    <li>
                        <a href="#" @click="dashboard"><i class="la la-desktop la-2x"></i>
                            <div>{{ trans('lang.dashboard') }}</div>
                        </a>
                    </li>
                    <li>
                        <a href="#" @click="bookingsclient"><i class="la la-user la-2x"></i>
                            <div>{{ trans('lang.bookings') }}</div>
                        </a>
                    </li>
                    <li>
                        <a href="#" @click="myprofile"><i class="la la-user la-2x"></i>
                            <div>{{ trans('lang.my_profile') }}</div>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="side-nav-close"
                 :class="{'d-none':!showSideNav}"
                 @click.prevent="showSideNavAction(false)">
            </div>
        </nav>
    </div>
</template>

<script>
    import axiosGetPost from '../../helper/axiosGetPostCommon';

    export default {
        props: ["profile"],
        extends: axiosGetPost,
        data() {
            return {
                first_name: '',
                last_name: '',
                avatar: '',
                id: '',
                read_by: [],
                items: [],
                date_time: '',
                count: 0,
                hideCircleLoader: true,
                users: [],
                showSideNav: '',
            }
        },
        created() {
            this.readNotification('/notify');
        },
        methods:
            {
                showSideNavAction(value) {
                    if (value === false && this.showSideNav == '') {
                        this.showSideNav = '';
                    } else {
                        this.showSideNav = value;
                    }
                },

                readNotification(route) {
                    let instance = this;
                    instance.axiosGet(route,
                        function (response) {
                            instance.items = response.data.notify;
                            instance.count = response.data.count;
                            setTimeout(function () {
                                instance.hideCircleLoader = false;
                            })
                        },
                    );
                },

                localTime(date_time) {
                    return moment(date_time + ' Z', 'YYYY-MM-DD HH:mm:ss Z', true).format(this.date_time);
                },

                upNofify(id) {
                    this.axiosPost('/upnotify/' + id,
                        {
                            read_by: this.profile.id
                        },
                    );
                    this.redirect("/booking/" + id);
                },
                upCount(id) {
                    this.axiosPost('/countup/' + id,
                        {
                            read_by: this.profile.id
                        },
                    );
                    this.count = 0;
                },
                allNotification() {
                    this.redirect('/notification');
                },
                newBooking() {
                    let instance = this;
                    instance.redirect('/');
                },
                dashboard() {
                    let instance = this;
                    instance.redirect('/dashboard');
                },
                bookingsclient() {
                    let instance = this;
                    instance.redirect('/bookingsclient');
                },
                myprofile() {
                    let instance = this;
                    instance.redirect('/myprofile');
                },
                logout() {
                    let instance = this;
                    instance.redirect('/logout');
                },
            }
    }
</script>
