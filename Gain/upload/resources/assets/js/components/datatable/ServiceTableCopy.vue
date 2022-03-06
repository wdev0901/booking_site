<template>
    <a href="#"
       class="float-left"
       v-clipboard:copy="publicPath + '/services/' + rowData.alias"
       data-toggle="tooltip"
       data-placement="top"
       :title="trans('lang.click_link')"
       :data-original-title="trans('lang.click_link')"
       rel="tooltip">
        <i class="la la-copy purple-grey-text text-dark"/>
    </a>
</template>
<script>
    export default {
        props: ['rowData', 'rowIndex'],
        mounted() {
            $('[data-toggle="tooltip"]').tooltip();
        },
    }
</script>
