<template>
    <div class="main-layout-card">
        <div class="custom-tabs">
            <ul class="nav nav-tabs">
                <li class="nav-item d-flex justify-content-center" :class="{'active':isSelectedTab(tab.name)}"
                    @click.prevent="selectTab(tab.name, tab.component)" v-for="tab in tabs">
                    <a class="nav-link" href="#"> {{ trans(tab.lang) }} </a>
                </li>
            </ul>
        </div>
        <transition name="slide-fade" mode="out-in">
            <component v-if="this.componentName"
                       v-bind:is="this.componentName">
            </component>
        </transition>
    </div>
</template>
<script>
    import axiosGetPost from '../../../helper/axiosGetPostCommon';

    export default {
        extends: axiosGetPost,
        data() {
            return {
                selectedTab: null,
                componentName: null,
                tabs: [
                    {name: "service_field", lang: "lang.services", component: "service-custom-field"},
                    {name: "booking_field", lang: "lang.booking", component: "booking-custom-field"},
                ],
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
                    if (!instance.selectedTab) {
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
