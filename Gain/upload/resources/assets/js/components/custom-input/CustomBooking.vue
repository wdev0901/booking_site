<template>
    <div>
        <preloaders v-if="hidePreloader!='hide'"></preloaders>
        <div v-show="hidePreloader=='hide'">
            <datatable-component v-if="refreshTable"
                                 :options="tableOptions"
                                 @setPreloader="getPreloader">
            </datatable-component>
        </div>
    </div>
</template>

<script>
    import axiosGetPost from '../../helper/axiosGetPostCommon';

    export default {
        extends: axiosGetPost,
        data() {
            return {
                selectedViewOption: 'service',
                isActive: false,
                items: [],
                selectedItemId: '',
                id: '',
                preloaderType: 'load',
                hidePreloader: '',
                tableOptions: {},
                sourceUrl: '/get-booking-field',
                refreshTable: true,
            }
        },
        mounted() {

            let instance = this;
            $('#custom-input-modal').modal(
                {
                    inDuration: 300, // Transition in duration
                    outDuration: 200, // Transition out duration
                    complete: function () {
                        instance.isActive = false;
                    },
                    onCloseEnd: function () {
                        instance.isActive = false;
                        instance.id = '';
                    }
                });
            $('#confirm-delete').modal({
                dismissible: true, // Modal can be dismissed by clicking outside of the modal
                opacity: .5, // Opacity of modal background
                inDuration: 300, // Transition in duration
                outDuration: 200, // Transition out duration
                startingTop: '0', // Starting top style attribute
                endingTop: '0', // Ending top style attribute
            });

            this.editField();
        },
        created() {
            this.readData();
        },
        methods: {
            getActive(isActive) {
                this.isActive = isActive;
            },
            readData() {
                let instance = this;
                instance.tableOptions = {
                    tableName: 'custom-inputs',
                    columns: [
                        {title: 'lang.field_type', key: 'field_type', type: 'text', sortable: true,},
                        {title: 'lang.label_name', key: 'label', type: 'text', sortable: true,},
                        {title: 'lang.status', key: 'is_enable', type: 'text', sortable: true,},
                        {title: 'lang.show_in_table', key: 'show_in_table', type: 'text', sortable: false,},
                        {title: 'lang.action', type: 'component', componentName: 'custom-field-action-component'}
                    ],
                    source: instance.sourceUrl,
                    search: true,
                }
            },
            getPreloader: function (type, action) {
                this.preloaderType = type;
                this.hidePreloader = action;
            },
            editField() {
                let instance = this;
                instance.$hub.$on('editField', function (id) {
                    instance.id = id;
                    instance.getActive(true);
                });
            },
        },
    }
</script>