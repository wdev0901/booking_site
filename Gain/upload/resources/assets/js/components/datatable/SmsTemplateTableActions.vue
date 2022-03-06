<template>
    <div class="dropdown show action-dropdown">
        <a class="dropdown-toggle"
           href="#"
           id="dropdownMenuLink"
           data-toggle="dropdown">
            <i class="la la-ellipsis-v la-1x"/>
        </a>
        <div class="dropdown-menu">
            <a href=""
               class="dropdown-item"
               data-toggle="modal"
               data-target="#email-template-modal"
               @click.prevent="viewTemplate(rowData.id,rowData.template_type)">
                {{ trans('lang.edit') }}
            </a>
        </div>
    </div>
</template>

<script>

    export default {
        props: ['rowData', 'rowIndex'],
        methods: {
            viewTemplate(id, title) {
                this.$hub.$emit('viewTemplateEdit', id, title);
            },
        }
    }
</script>
