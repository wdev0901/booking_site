<template>
    <div class="main-layout-wrapper">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent m-0">
                <li class="breadcrumb-item">
                    <span>{{ trans('lang.service') }}</span>
                </li>
            </ol>
        </nav>
        <div class="main-layout-card">
            <div class="main-layout-card-header-with-button">
                <div class="main-layout-card-content-wrapper">
                    <div class="main-layout-card-header-contents">
                        <h5 class="bluish-text m-0">{{ trans('lang.service') }}</h5>
                    </div>
                    <div class="main-layout-card-header-contents text-right  ">
                        <button class="btn btn-primary app-color" data-toggle="modal" data-target="#add-edit-modal"
                                @click.prevent="addEditAction('')">{{ trans('lang.add') }}
                        </button>
                    </div>
                </div>
            </div>
            <datatable-component class="main-layout-card-content"
                                 :options="tableOptions"/>
        </div>
        <div class="modal fade" id="add-edit-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered big-modal-dialog" role="document">
                <createservice-form class="modal-content"
                                    v-if="isActive"
                                    :id="selectedItemId"
                                    :modalID="modalID"/>
            </div>
        </div>
        <div class="modal fade" id="custom-setting-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered big-modal-dialog" role="document">
                <custom-setting-modal class="modal-content"
                                      v-if="isActive2"
                                      :id="selectedItemId"
                                      :modalID="modalID2"/>

            </div>
        </div>
        <confirmation-modal id="confirm-delete"
                            :message="'service_deleted_permanently'"
                            :firstButtonName="'yes'"
                            :secondButtonName="'no'"
                            @confirmationModalButtonAction="confirmationModalButtonAction"/>

    </div>
</template>

<script>
    import axiosGetPost from '../../helper/axiosGetPostCommon';

    export default {
        extends: axiosGetPost,
        data() {
            let instance = this;
            return {
                hidePreLoader: true,
                modalID: '#add-edit-modal',
                modalID2: '#custom-setting-modal',
                isActive: false,
                isActive2: false,
                selectedItemId: '',
                hasData: value => {
                    return !_.isEmpty(value) ? true : false
                },
                tableOptions: {
                    tableName: 'services',
                    columns: [
                        {title: 'lang.title', key: 'title', type: 'text', uniquefield: 'id', sortable: true},
                        {title: 'lang.price', key: 'price', type: 'text', sortable: true},
                        {title: 'lang.duration', key: 'service_duration', type: 'text', sortable: true},
                        {title: 'lang.description', key: 'description', type: 'text', sortable: true},
                        {title: 'lang.starts', key: 'service_starts', type: 'text', sortable: true},
                        {title: 'lang.ends', key: 'service_ends', type: 'text', sortable: true},
                        {
                            title: 'lang.status', key: 'activation', type: 'custom',
                            modifier: function (rowData) {
                                if (rowData == '1') {
                                    return {
                                        value: instance.trans('lang.active'),
                                        class: "booking-status bg-success"
                                    }
                                } else if (rowData == '0') {
                                    return {
                                        value: instance.trans('lang.inactive'),
                                        class: "booking-status bg-warning"
                                    }
                                }
                            }
                        },
                        {title: 'lang.link', key: '', type: 'component', componentName: 'service-copy-component'},
                        {
                            title: 'lang.action',
                            key: 'action',
                            type: 'component',
                            componentName: 'service-action-component'
                        }

                    ],
                    right_align: ['action'],
                    source: '/servicelist',
                    search: false,
                    customField: true, // For custom field display in datatable.
                    positionAfter: 'activation',
                },

            }
        },
        mounted() {
            let instance = this;

            instance.$hub.$on('viewEdit', function (id) {
                instance.addEditAction(id);
            });

            instance.$hub.$on('customEdit', function (id) {
                instance.customEditAction(id);
            });

            instance.modalCloseAction(instance.modalID);
            instance.modalCloseAction2(instance.modalID2);

        },
        methods: {
            confirmationModalButtonAction() {
                let instance = this;
                instance.deleteDataMethod('/deleteservice/' + instance.deleteID, instance.deleteIndex);
            },
        }

    }
</script>