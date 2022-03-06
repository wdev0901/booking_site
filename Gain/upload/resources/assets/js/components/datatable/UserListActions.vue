<template>
    <div class="dropdown show action-dropdown">
        <a class="dropdown-toggle"
           href="#"
           id="dropdownMenuLink"
           data-toggle="dropdown">
            <i class="la la-ellipsis-v la-1x"></i>
        </a>
        <div :id="rowData.id + 'dropdown'" class="dropdown-menu">
            <a @click.prevent="userDetails(rowData.id)"
               class="dropdown-item"
               href="#">
                {{ trans('lang.view') }}
            </a>
            <a @click.prevent="disableEnableUser(rowData.id,1)"
               v-if="rowData.disabled==0 && rowData.is_admin==0"
               class="dropdown-item"
               href="#disable-user">
                {{ trans('lang.disable') }}
            </a>
            <a @click.prevent="disableEnableUser(rowData.id,0)"
               v-if="rowData.disabled==1  && rowData.is_admin==0"
               class="dropdown-item"
               href="#enable-user">
                {{ trans('lang.enable') }}
            </a>
            <a @click.prevent="editAction(), changeUserRole(rowData.id, rowData.full_name, rowData.role_id)"
               class="dropdown-item"
               href="#edit-user-invite-modal">
                {{ trans('lang.edit') }}
            </a>
            <a @click.prevent="deleteUser(rowData.id,rowIndex)"
               v-if="rowData.is_admin == 0"
               class="dropdown-item"
               href=""
               data-toggle="modal"
               data-target="#confirm-delete">
                {{ trans('lang.delete') }}
            </a>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['rowData', 'rowIndex'],
        data() {
            return {}
        },
        mounted() {
            let instance = this;
            $('#confirm-delete-' + this.rowIndex).modal({
                    dismissible: true, // Modal can be dismissed by clicking outside of the modal
                    opacity: .5, // Opacity of modal background
                    inDuration: 300, // Transition in duration
                    outDuration: 200, // Transition out duration
                    startingTop: '0', // Starting top style attribute
                    endingTop: '0', // Ending top style attribute
                    complete: function () {
                    },
                }
            );
        },
        methods: {
            editAction() {
                this.$hub.$emit('editAction', true);
            },
            changeUserRole(id, name, roleId) {
                this.$hub.$emit('changeUserRole', id, name, roleId);
            },
            disableEnableUser(id) {
                this.$hub.$emit('disableEnableUserAction', id);
            },
            userDetails(id) {
                this.$hub.$emit('userDetails', id);
            },
            deleteUser(id, index) {
                this.$hub.$emit('selectedDeletableId', id, index);
            },
        }
    }
</script>