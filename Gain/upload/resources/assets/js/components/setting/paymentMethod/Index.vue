<template>
    <div class="main-layout-card">
        <div class="main-layout-card-header-with-button">
            <div class="main-layout-card-content-wrapper">
                <div class="main-layout-card-header-contents">
                    <h5 class="m-0">{{ trans('lang.payment_methods') }}</h5>
                </div>
                <div class="main-layout-card-header-contents text-right">
                    <button class="btn btn-primary app-color mobile-btn" data-toggle="modal"
                            data-target="#add-edit-modal"
                            @click.prevent="addEditAction('')">
                        {{ trans('lang.add') }}
                    </button>
                </div>
            </div>
        </div>

        <pre-loader v-if="!hidePreLoader"></pre-loader>
        <datatable-component v-else class="main-layout-card-content" :options="tableOptions"></datatable-component>

        <!--Pass current modelId and isActive for rerender modal on DOM -->
        <div class="modal fade" id="add-edit-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <payment-methods-add-edit-modal
                        class="modal-content"
                        :modalID="modalID"
                        v-if="isActive"
                        :id="selectedItemId"
                ></payment-methods-add-edit-modal>
            </div>
        </div>
        <!-- Delete Modal -->
        <confirmation-modal
                id="confirm-delete"
                :message="'payment_method_deleted_permanently'"
                :firstButtonName="'yes'"
                :secondButtonName="'no'"
                @confirmationModalButtonAction="confirmationModalButtonAction"
        ></confirmation-modal>

    </div>
</template>
<script>
    import axiosGetPost from '../../../helper/axiosGetPostCommon';

    export default {
        extends: axiosGetPost,
        data() {
            return {
                isActive: false,
                items: [],
                modalID: '#add-edit-modal',
                selectedItemId: '',
                hidePreLoader: true,
                dateformat: '',
                allPaymentTypes: [],
                tableOptions: {
                    tableName: 'payment_types',
                    columns: [
                        {title: 'lang.name', key: 'title', type: 'text', sortable: true},
                        {title: 'lang.available_to_client', key: 'available_to_client', type: 'text', sortable: true},
                        {title: 'lang.enabled', key: 'enable', type: 'text', sortable: true},
                        {
                            title: 'lang.action',
                            type: 'component',
                            key: 'action',
                            componentName: 'payment-action-component',

                        }
                    ],
                    source: '/payments',
                    search: false,
                    right_align: 'action'
                },

            }
        },

        mounted() {
            let instance = this;
            instance.$hub.$on('viewEdit', function (id) {
                instance.addEditAction(id);
            });

            instance.modalCloseAction(instance.modalID);
        },

        methods: {
            confirmationModalButtonAction() {
                // set delete route with global deleteID && deleteIndex.
                let instance = this;
                instance.deleteDataMethod('/payment-delete/' + instance.deleteID, instance.deleteIndex);
            },
        },
    }

</script>