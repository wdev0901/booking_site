<template>
    <div class="dropdown show action-dropdown">
        <a class="dropdown-toggle"
           href="#"
           id="dropdownMenuLink"
           data-toggle="dropdown">
            <i class="la la-ellipsis-v la-1x"></i>
        </a>
        <div class="dropdown-menu">
            <a class="dropdown-item"
               href="#"
               data-toggle="modal"
               data-target="#booking-modal"
               @click.prevent="viewEdit(rowData.id)">
                {{ trans('lang.edit') }}
            </a>
            <a href=""
               class="dropdown-item"
               @click.prevent="bookingDetails(rowData.id)">
                {{ trans('lang.view') }}
            </a>
            <a v-if="rowData.payment_status == 'due' && rowData.status != 'canceled'"
               href=""
               class="dropdown-item"
               data-toggle="modal"
               data-target="#due-payment-modal"
               @click.prevent="duePayment(rowData.id)">
                {{ trans('lang.pay') }}
            </a>
            <a v-if="rowData.status === 'pending'"
               href=""
               class="dropdown-item"
               @click.prevent="confirm(rowData.id)">
                {{ trans('lang.confirm') }}
            </a>
            <a v-if="rowData.status === 'pending' && rowData.payment_stat !==0"
               href=""
               class="dropdown-item"
               data-toggle="modal"
               data-target="#confirm-cancel-booking"
               @click.prevent="cancelBookingFromDatatable(rowData.id)">
                {{ trans('lang.cancel') }}
            </a>
            <a class="dropdown-item"
               href=""
               data-toggle="modal"
               data-target="#confirm-delete"
               @click.prevent="selectedDeletableId(rowData.id,rowIndex)">
                {{ trans('lang.delete') }}
            </a>
        </div>
    </div>
</template>

<script>
    import axiosGetPost from '../../helper/axiosGetPostCommon';

    export default {
        extends: axiosGetPost,
        props: ['rowData', 'rowIndex'],
        data() {
            return {}
        },
        mounted() {
            let instance = this;
            $('#confirm-delete-' + this.rowIndex).modal('show');
            
            instance.$hub.$on('viewEdit', function (id) {
                instance.addEditAction(id);
            });
        },
        methods: {
            readData() {
                this.$emit('readData');
            },
            getActive(isActive) {
                this.$hub.$emit("getActive", isActive);
            },
            confirm(id) {
                this.$hub.$emit('confirmBookingFromDatatable', id);
            },
            bookingDetails(id) {
                this.$hub.$emit('bookingDetails', id)
            },
            cancelBookingFromDatatable(id) {
                this.$hub.$emit('cancelBookingFromDatatable', id)
            },
            duePayment(id) {
                this.$hub.$emit('duePayment', id);
            },
            changeBookingValue(value) {
                this.bookingWatcher = value;
            },
            selectedDeletableId(id, index) {
                this.$hub.$emit('selectedDeletableId', id, index);
            },
            viewEdit(id) {
                this.$hub.$emit('viewEdit', id);
            },
        }
    }
</script>
