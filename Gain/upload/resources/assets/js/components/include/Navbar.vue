<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-light fixed-top">
            <a href="#" class="d-lg-none app-color-text" @click.prevent="showSideNavAction(true)" v-if="!showSideNav">
                <i class="la la-navicon la-2x"></i>
            </a>
            <a href="#" class="d-lg-none app-color-text" @click.prevent="showSideNavAction(false)" v-else>
                <i class="la la-close la-2x"></i>
            </a>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle hide-dropdown-icon"
                       href="#"
                       id="navbar-notification-dropdown"
                       data-toggle="dropdown"
                       aria-haspopup="true"
                       aria-expanded="false" @click.prevent="upCount(profile.id)">
                        <i class="la la-bell la-2x"></i>
                        <span class="badge badge-danger badge-notify" v-if="count!=0">{{count}}</span>
                    </a>
                    <div class="navbar-notification-container dropdown-menu dropdown-menu-right animated bounceInDown">
                        <div class="ticker"></div>
                        <p class="text-left p-2 mb-0">{{ trans('lang.your_notifications') }}</p>
                        <div class="notification">
                            <a v-for="item in items" href="#"
                               @click.prevent="upNofify(item.id,item.booking_id)"
                               class="list-group-item justify-content-between align-items-center list-group-item-primary notification-list">
                                <div class="d-flex">
                                    <div class="image-notification mr-3">
                                        <img :src="publicPath+'/uploads/profile/'+item.avatar"
                                             class="img-fluid rounded-circle"
                                             alt="quixote"
                                             width="40">
                                    </div>
                                    <p class="m-0">
                                        {{ item.first_name }} {{ item.last_name }} {{ trans('lang.'+item.event)+" #"+item.booking_id }}
                                        <br>
                                        <small class="small">{{dateFormatBooking(item.created_at)}}</small>
                                    </p>
                                </div>
                            </a>
                        </div>

                        <div class="dropdown-divider m-0"></div>

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
                        <img :src="publicPath+'/uploads/profile/'+profile.avatar" alt class="rounded-circle avatar-large"/>
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
                        <a :href="publicPath+'/dashboard'" class="app-color-text">
                            <i class="la la-desktop la-2x"></i>
                            {{ trans('lang.dashboard') }}
                        </a>
                    </li>
                    <li>
                        <a :href="publicPath+'/services'" class="app-color-text">
                            <i class="la la-globe la-2x"></i>
                            {{ trans('lang.services') }}
                        </a>
                    </li>
                    <li>
                        <a :href="publicPath+'/bookings'" class="app-color-text">
                            <i class="la la-credit-card la-2x"></i>
                            {{ trans('lang.bookings') }}
                        </a>
                    </li>
                    <li>
                        <a :href="publicPath+'/clients'" class="app-color-text">
                            <i class="la la-users la-2x"></i>
                            {{ trans('lang.clients') }}
                        </a>
                    </li>
                    <li>
                        <a :href="publicPath+'/reports'" class="app-color-text">
                            <i class="la la-pie-chart la-2x"></i>
                            {{ trans('lang.reports') }}
                        </a>
                    </li>
                    <li>
                        <a :href="publicPath+'/settings'" class="app-color-text">
                            <i class="la la-gear la-2x"></i>
                            {{ trans('lang.settings') }}
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
        props: [
            'profile',
            'services',
            'booking',
            'userroles',
            'usermanage',
            'appsettings',
            'emailsettings',
            'offdaysettings',
            'holidaysettings',
            'emailtemplate',
            'clientmanage',
            'reportpermission',
            'clientsetting'
        ],
        extends: axiosGetPost,
        data() {
            return {
                first_name: '',
                last_name: '',
                avatar: '',
                id: '',
                read_by: [],
                items: [],
                date_time: this.dateTimeFormat,
                count: 0,
                hideCircleLoader: true,
                users: [],
                showSideNav: '',
            }
        },
        created() {
            this.readNotifi('/notify');
        },
        methods: {
            showSideNavAction(value) {
                if (value === false && this.showSideNav == '') {
                    this.showSideNav = '';
                } else {
                    this.showSideNav = value;
                }
            },
            readNotifi(route) {
                let instance = this;
                instance.axiosGet(route,
                    function (response) {
                        instance.count = response.data.count;
                        instance.items = response.data.notify;
                        setTimeout(function () {
                            instance.hideCircleLoader = false;
                        })
                    },
                );
            },
            upNofify(id, booking_id) {
                this.axiosPost('/upnotify/' + id,
                    {
                        read_by: this.profile.id
                    },
                );
                this.redirect("/booking-details/" + booking_id);
            },
            upCount(id) {
                this.axiosPost('/countup/' + id,
                    {
                        read_by: this.profile.id
                    },
                );
                this.count = 0;
            },
            allNoti() {
                let instance = this;
                instance.redirect("/notification");
            },
            dashboardclick() {
                let instance = this;
                instance.redirect('/dashboard');
            },
            servicesclick() {
                let instance = this;
                instance.redirect('/services');
            },
            bookingsclick() {
                let instance = this;
                instance.redirect('/bookings');
            },
            clientsclick() {
                let instance = this;
                instance.redirect('/clients');
            },
            reportsclick() {
                let instance = this;
                instance.redirect('/reports');
            },
            settingclick() {
                let instance = this;
                instance.redirect('/settings');
            },
            myprofileclick() {
                let instance = this;
                instance.redirect('/myprofile');
            },
            logoutclick() {
                let instance = this;
                instance.redirect('/logout');
            },

        }
    }
</script>