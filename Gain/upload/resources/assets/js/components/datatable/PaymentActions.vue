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
               data-target="#add-edit-modal"
               @click.prevent="viewEdit(rowData.id)">
                {{ trans('lang.edit') }}
            </a>
            <a href=""
               class="dropdown-item"
               v-if="rowData.type === 'custom' "
               data-toggle="modal"
               data-target="#confirm-delete"
               @click.prevent="selectedDeletableId(rowData.id,rowIndex)">
                {{ trans('lang.delete') }}
            </a>
        </div>
    </div>
</template>

<script>
    import axiosGetPost from '../../helper/axiosGetPostCommon.js';

    export default {
        extends: axiosGetPost,
        name: "PaymentActions",
        props: ['rowData', 'rowIndex'],
        mounted() {
            let instance = this;

            instance.$hub.$on('viewEdit', function (id) {
                instance.addEditAction(id);
            });
        },
        methods: {
            selectedDeletableId(id, index) {
                this.$hub.$emit('selectedDeletableId', id, index);
            },
            viewEdit(id) {
                let instance = this;
                instance.$hub.$emit('viewEdit', id);
            },
        }
    }
</script>
