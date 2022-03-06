<template>
    <div class="main-layout-wrapper">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent m-0">
                <li class="breadcrumb-item">
                    <span>{{ trans('lang.notifications') }}</span>
                </li>
            </ol>
        </nav>
        <div class="main-layout-card">
            <div class="main-layout-card-header">
                <div class="main-layout-card-content-wrapper">
                    <div class="main-layout-card-header-contents">
                        <h5 class="mb-0">{{ trans('lang.your_notifications') }}</h5>
                    </div>
                </div>
            </div>
            <div class="main-layout-card-content all-notification-wrapper">
                <div class="media"
                     @click.prevent="upNofify(notification.id,notification.booking_id)"
                     :class="{'unread-all-notification':  notification.read_by.indexOf(profile.id)==-1}"
                     v-for="notification in notifications">
                    <img :src="publicPath+'/uploads/profile/'+notification.avatar"
                         width="40"
                         class="align-self-center mr-3 rounded-circle"
                         alt="">
                    <div class="media-body">
                        <p class="mb-0">{{ notification.first_name }} {{ notification.last_name ? notification.last_name : ''}} {{trans('lang.'+notification.event)+" #"+notification.booking_id}}</p>
                        <p class="mb-0 small">{{ trans('lang.received_at') }} : {{ dateFormatBooking(notification.created_at)}}</p>
                    </div>
                </div>
                <div v-if="notifications.length == 0">
                    {{ trans('lang.you_have_no_notifications') }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axiosGetPost from '../../helper/axiosGetPostCommon';

    export default {
        extends: axiosGetPost,
        props: ['data_notify', 'auth_profile'],
        data() {
            return {
                date_time: this.dateTimeFormat,
                notifications: this.data_notify,
                profile: this.auth_profile,
            }
        },
        methods: {
            upNofify(id, booking_id) {
                let instance = this;
                instance.axiosPost('/upnotify/' + id, {
                        read_by: this.profile.id
                    });
                instance.redirect("/booking-details/" + booking_id);
            },
        },
    }
</script>