<template>
    <div class="main-layout-card">
        <div class="main-layout-card-header-with-button">
            <div class="main-layout-card-content-wrapper">
                <div class="main-layout-card-header-contents">
                    <h5 class="m-0">{{ trans('lang.roles') }}</h5>
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

        <datatable-component class="main-layout-card-content" :options="tableOptions"></datatable-component>
        <!--Pass current modelId and isActive for rerender modal on DOM -->
        <div class="modal fade" id="add-edit-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <role_add_edit_modal
                        class="modal-content"
                        :modalID="modalID"
                        v-if="isActive"
                        :id="selectedItemId"
                ></role_add_edit_modal>
            </div>
        </div>

        <!-- Delete Modal -->
        <confirmation-modal
                id="confirm-delete"
                :message="'role_deleted_permanently'"
                :firstButtonName="'yes'"
                :secondButtonName="'no'"
                @confirmationModalButtonAction="confirmationModalButtonAction"
        ></confirmation-modal>
        <!-- Modal End-->
    </div>
</template>
<script>
    import axiosGetPost from '../../../helper/axiosGetPostCommon';

    export default {
        extends: axiosGetPost,
        data() {
            return {
                seen: true,
                roleHide: 'hide',
                items: [],
                title: '',
                permissions: '',
                windowWidth: 0,
                deletePreloader: 'hide',
                modalID: '#add-edit-modal',
                isActive: false,
                selectedItemId: '',
                tableOptions: {
                    tableName: 'roles',
                    columns: [
                        {title: 'lang.title', key: 'title', type: 'text', sortable: true},
                        {title: 'lang.action', key: 'action', type: 'component', componentName: 'action_component'}
                    ],
                    source: '/roletitle',
                    right_align: ['action']
                }
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
                instance.deleteDataMethod('/deleterole/' + instance.deleteID, instance.deleteIndex);
            },
        }
    }
</script>