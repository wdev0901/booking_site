<template>
    <div>
        <div class="main-layout-wrapper">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent m-0">
                    <li class="breadcrumb-item">
                        <span>{{trans('lang.settings')}}</span>
                    </li>
                </ol>
            </nav>
            <div class="container-fluid pr-0 mt-0">
                <div class="row mr-0">
                    <div class="col-sm-12 col-md-12 col-lg-3 col-xl-2 settings-left-card">
                        <div class="main-layout-card">
                            <div class="main-layout-card-header">
                                <div class="main-layout-card-content-wrapper">
                                    <div class="main-layout-card-header-contents">
                                        <h5 class="bluish-text m-0">{{ trans('lang.settings') }}</h5>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <ul class="list-group list-group-flush" id="settings-list">
                                    <li class="list-group-item" :class="{'active-border':isSelectedTab(tab.name)}"
                                        @click.prevent="selectTab(tab.name, tab.component)" v-if="isVisible(tab.name)"
                                        v-for="tab in tabs">
                                        {{ trans(tab.lang) }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-9 col-xl-10 px-0">
                        <transition name="slide-fade" mode="out-in">
                            <component v-if="this.componentName" v-bind:is="this.componentName"
                                       :timezones="all_time_zone"></component>
                        </transition>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['all_time_zone', 'service_policy', 'app_settings', 'email_settings', 'email_templates', 'sms_settings', 'sms_templates', 'off_day', 'holiday', 'roles', 'users', 'client_settings', 'payment_settings', 'custom_input_settings', 'social_link', 'contact_information', 'updates', 'break_time'],
        data() {
            return {
                selectedTab: null,
                componentName: null,
                tabs: [
                    {name: "app_settings", lang: "lang.application", component: "application-setting"},
                    {name: "email_settings", lang: "lang.emails", component: "email-form"},
                    {name: "email_templates", lang: "lang.email_templates", component: "email-template-list"},
                    {name: "sms_settings", lang: "lang.sms_setting", component: "sms-setting"},
                    {name: "sms_templates", lang: "lang.sms_templates", component: "sms-template-list"},
                    {name: "off_day", lang: "lang.off_day", component: "offday-setting"},
                    {name: "holiday", lang: "lang.holidays", component: "holiday-index"},
                    {name: "break_time", lang: "lang.break_time", component: "break-time"},
                    {name: "client_settings", lang: "lang.clients", component: "client-setting"},
                    {name: "roles", lang: "lang.roles", component: "roles"},
                    {name: "users", lang: "lang.users", component: "user-list"},
                    {name: "payment_settings", lang: "lang.payment_methods", component: "payment-index"},
                    {name: "custom_input_settings", lang: "lang.custom_fields", component: "custom-fields"},
                    {name: "service_policy", lang: "lang.service_policy", component: "service-policy"},
                    {name: "social_link", lang: "lang.social_link", component: "social-link"},
                    {name: "contact_information", lang: "lang.contact_information", component: "contact-information"},
                    {name: "updates", lang: "lang.updates", component: "updates"}
                ],
                isVisible: function (tabName) {
                    return (this[tabName] == "1");
                },
                isSelectedTab: function (tabName) {
                    return (tabName === this.selectedTab);
                }
            }
        },
        methods: {
            selectTab: function (tabName, componentName) {
                this.selectedTab = tabName;
                this.componentName = componentName;
            },
            initSelectedTab: function () {
                let instance = this;

                this.tabs.forEach(function (tab) {
                    if (!instance.selectedTab && instance.isVisible(tab.name)) {
                        instance.selectTab(tab.name, tab.component);
                    }
                });
            }
        },
        mounted() {
            this.initSelectedTab();
        }
    }
</script>
