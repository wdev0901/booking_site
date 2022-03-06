<template>
    <button class="btn btn-info mobile-btn d-inline-flex"
            type="submit"
            @click.prevent="submit()"
            :disabled="isDisabled">
        <span class="w-100" :class="{buttonText:buttonLoader}">
            <roller-loader class="d-inline mx-2" v-if="buttonLoader"/>
            {{ trans('lang.'+buttonText) }}
        </span>
    </button>
</template>
<script>
    export default {
        props: ["buttonLoader", "isDisabled", "isActiveText", "buttonText"],
        methods: {
            submit() {
                let instance = this;
                instance.$emit("submit");
            }
        }
    };
</script>
