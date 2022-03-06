<template>
    <div class="main-layout-wrapper">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent m-0">
                <li class="breadcrumb-item">
                    <span>{{ trans('lang.clients') }}</span>
                </li>
            </ol>
        </nav>
        <div class="main-layout-card">
            <div class="main-layout-card-header-with-button">
                <div class="main-layout-card-content-wrapper">
                    <div class="main-layout-card-header-contents">
                        <h5 class="bluish-text m-0">{{ trans('lang.clients') }}</h5>
                    </div>
                </div>
            </div>
            <datatable-component class="main-layout-card-content"
                                 :options="tableOptions">
            </datatable-component>
            <!--Pass current modelId and isActive for rerender modal on DOM -->
            <div class="modal fade" id="add-edit-modal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered big-modal-dialog" role="document">
                    <client_edit class="modal-content"
                                 v-if="isActive"
                                 :id="selectedItemId"
                                 :modalID="modalID">
                    </client_edit>
                </div>
            </div>
        </div>
        <confirmation-modal id="confirm-delete"
                            :message="'client_will_be_deleted_permanently'"
                            :firstButtonName="'yes'"
                            :secondButtonName="'no'"
                            @confirmationModalButtonAction="confirmationModalButtonAction">
        </confirmation-modal>
    </div>
</template>

<script>
    import axiosGetPost from '../../helper/axiosGetPostCommon';

    export default {
        extends: axiosGetPost,
        data() {
            return {
                modalID: '#add-edit-modal',
                isActive: false,
                selectedItemId: '',
                hasData: value => {
                    return !_.isEmpty(value) ? true : false
                },
                tableOptions: {
                    tableName: 'clients',
                    columns: [
                        {
                            title: 'lang.name',
                            key: 'full_name',
                            type: 'clickable_link',
                            source: 'client',
                            uniquefield: 'id',
                            sortable: true
                        },
                        {title: 'lang.emails', key: 'email', type: 'text', sortable: true},
                        {title: 'lang.phone', key: 'phone', type: 'text', sortable: true},
                        {
                            title: 'lang.action',
                            key: 'action',
                            type: 'component',
                            componentName: 'action_component'
                        }
                    ],
                    right_align: ['action'],
                    source: '/clientlist',
                    search: true,
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
                instance.deleteDataMethod('/deleteclient/' + instance.deleteID, instance.deleteIndex);
            },
        }
    }
</script>