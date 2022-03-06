<template>
    <div class="main-layout-card">
        <div class="main-layout-card-header-with-button">
            <div class="main-layout-card-content-wrapper">
                <div class="main-layout-card-header-contents">
                    <h5 class="m-0">{{ trans('lang.break_time') }}</h5>
                </div>
                <div class="main-layout-card-header-contents text-right">
                    <button class="btn btn-primary app-color mobile-btn" data-toggle="modal"
                            :data-target="'#'+addEditModalId"
                            @click.prevent="addEditAction('')">
                        {{ trans('lang.add') }}
                    </button>
                </div>
            </div>
        </div>

        <datatable-component class="main-layout-card-content" :options="tableOptions"></datatable-component>

        <!-- add edit modal -->
        <div class="modal fade" :id="addEditModalId" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <break-time-add-edit-modal v-if="isActive" 
                                            :id="selectedItemId"
                                            :modal-id="addEditModalId"/>
            </div>
        </div>

        <!-- delete modal -->
        <confirmation-modal
                id="confirm-delete"
                message="payment_method_deleted_permanently"
                firstButtonName="yes"
                secondButtonName="no"
                @confirmationModalButtonAction="confirmationModalButtonAction"/>
    </div>
</template>
<script>
    import axiosGetPost from '../../../helper/axiosGetPostCommon';

    export default {
        extends: axiosGetPost,
        data() {
            return {
                addEditModalId: 'add-edit-modal',
                selectedItemId: '',
                isActive:false,
                tableOptions: {
                    tableName: 'breack_time',
                    columns: [
                        {title: 'lang.title', key: 'title', type: 'text', sortable: true},
                        {title: 'lang.start_time', key: 'start_time', type: 'text', sortable: false},
                        {title: 'lang.end_time', key: 'end_time', type: 'text', sortable: false},
                        {
                            title: 'lang.enable',
                            key: 'is_enable', 
                            type: 'text',
                            sortable: false,
                        },
                        {title: 'lang.action', key: 'action', type: 'component', componentName: 'action_component'}
                    ],
                    source: '/break-times',
                    right_align: ['action']
                }
            }
        },

        mounted() {
            let instance = this,
                modalId = `#${instance.addEditModalId}`;

            instance.$hub.$on('viewEdit', function (id) {
                instance.addEditAction(id);
            });
            instance.modalCloseAction(modalId);
        },

        methods: {
            confirmationModalButtonAction() {
                let instance = this,
                    url = `/break-time/${instance.deleteID}/delete`;

                instance.deleteDataMethod(url, instance.deleteIndex);
            },
        },
       
    }
</script>