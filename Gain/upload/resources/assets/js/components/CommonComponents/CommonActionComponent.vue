<template>
    <div class="dropdown show action-dropdown">
        <a class="dropdown-toggle"
           href="#"
           id="dropdownMenuLink"
           data-toggle="dropdown">
            <i class="la la-ellipsis-v la-1x"/>
        </a>
        <div class="dropdown-menu">
            <a class="dropdown-item"
               href=""
               data-toggle="modal"
               data-target="#add-edit-modal"
               @click.prevent="viewEdit(rowData.id)">
                {{ trans('lang.edit') }}
            </a>
            <a class="dropdown-item"
               href=""
               v-show="isShowing"
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
        props: ['rowData', 'rowIndex', 'iconStatus'],
        data() {
            return {
                isShowing: true,
            }
        },
        mounted() {
            let instance = this;
            instance.$hub.$on('viewEdit', function (id) {
                instance.addEditAction(id);
            });
        },
        methods: {
            viewEdit(id) {
                this.$hub.$emit('viewEdit', id);
            },
            selectedDeletableId(id, index) {
                this.$hub.$emit('selectedDeletableId', id, index);
            },
        },
        created() {
            if (this.iconStatus) {
                this.isShowing = this.iconStatus;
            }
        }
    }
</script>
