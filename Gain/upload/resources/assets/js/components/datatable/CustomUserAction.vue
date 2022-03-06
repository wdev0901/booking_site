<template>
    <div class="dropdown show action-dropdown">
        <a class="dropdown-toggle"
           href="#"
           id="dropdownMenuLink"
           data-toggle="dropdown">
            <i class="la la-ellipsis-v la-1x"/>
        </a>
        <div class="dropdown-menu">
            <a :href="publicPath+'/user/'+rowData.id"
               class="dropdown-item">
                {{ trans('lang.view') }}
            </a>
            <a href="" v-if="rowData.is_admin != 1"
               class="dropdown-item"
               data-toggle="modal"
               data-target="#add-edit-modal"
               @click.prevent="viewEdit(rowData.id)">
                {{ trans('lang.edit') }}
            </a>
            <span v-if="rowData.is_admin != 1">
                <a href=""
                   data-toggle="modal"
                   data-target="#confirm-enable"
                   @click.prevent="selectedDeletableId(rowData.id,rowIndex)"
                   v-if="rowData.disabled == 0"
                   class="dropdown-item">
                    {{ trans('lang.disable') }}
                </a>
                <a href=""
                   data-toggle="modal"
                   data-target="#confirm-disable"
                   @click.prevent="selectedDeletableId(rowData.id,rowIndex)"
                   v-else
                   class="dropdown-item">
                    {{ trans('lang.enable') }}
                </a>
            </span>
            <a href=""
               class="dropdown-item"
               v-if="rowData.is_admin != 1"
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
        name: "CustomUserAction",
        extends: axiosGetPost,
        props: ['rowData', 'rowIndex', 'iconStatus'],
        data() {
            return {
                isShowing: true,
            }
        },
        created() {
            if (this.iconStatus) {
                this.isShowing = this.iconStatus;
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
    }
</script>
