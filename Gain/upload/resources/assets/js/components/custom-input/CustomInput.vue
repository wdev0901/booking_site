<template>
    <div>
        <div class="main-layout-card-header-with-button">
            <div class="main-layout-card-content-wrapper">
                <div class="main-layout-card-header-contents">
                    <h5 class="bluish-text no-margin">{{ trans('lang.custom_field_settings') }}</h5>
                </div>
                <div class="main-layout-card-header-contents float-right">
                    <button class="btn btn-primary app-color">{{ trans('lang.add') }}</button>
                </div>
            </div>
        </div>
        
        <div class="row custom-data-field">
            <div class="col-12">
                <ul class="tabs tab-border">
                    <li class="tab">
                        <a class="active custom-tab" href="#service">
                            {{ trans('lang.services') }}
                        </a>
                    </li>
                    <li class="tab">
                        <a href="#booking" class="custom-tab">
                            {{ trans('lang.booking') }}
                        </a>
                    </li>
                </ul>
            </div>

            <div id="service" class="col-12">
                <custom-service></custom-service>
            </div>

            <div id="booking" class="col-12">
                <custom-booking></custom-booking>
            </div>

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
                refreshTable: true,
                deleteID: '',
                deleteIndex: '',
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

            $('.tabs').tabs();
            this.editField();

            this.$hub.$on('selectedDeletableId', function (id, index) {
                instance.deleteID = id;
                instance.deleteIndex = index;

            });

        },
        methods: {
            getActiveTab() {
                let id = $(".active").attr('href');
                if (id === '#booking') {
                    this.selectedViewOption = 'booking';
                } else {
                    this.selectedViewOption = 'service';
                }

                this.isActive = true;
            },
            getActive(isActive) {
                this.isActive = isActive;
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
            confirmationModalButtonAction() {
                let instance = this;
                let id = $(".active").attr('href');
                if (id === '#booking') {
                    instance.$hub.$emit('reloadDataTable');
                    instance.deleteBooking(instance.deleteID);
                } else {
                    instance.$hub.$emit('reloadDataTable');
                    instance.deleteService(instance.deleteID);
                }
            },
            deleteService(id) {
                let instance = this;
                this.getPreloader('delete', '');
                instance.axiosPost('/deletesettingservice/' + id, {},
                    function (response) {
                        instance.$hub.$emit('reloadDataTable');
                        M.toast({html: response.data.msg});
                        instance.setPreLoader('load', 'hide');
                    },
                    function (error) {
                        instance.getPreloader('delete', 'hide');
                    });
            },
            deleteBooking(id) {
                let instance = this;
                this.getPreloader('delete', '');
                instance.axiosPost('/deletesettingbooking/' + id, {},
                    function (response) {
                        instance.$hub.$emit('reloadDataTable');
                        M.toast({html: response.data.msg});
                        instance.setPreLoader('load', 'hide');
                    },
                    function (error) {
                        instance.getPreloader('delete', 'hide');
                    });
            }
        },
    }
</script>