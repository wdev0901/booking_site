<template>
    <div class="fixed-action-btn horizontal action-com-button">
        <a href="#" @click.prevent="">
            <i class="material-icons purple-grey-text text-darken-1 icon-vertically-middle">more_vert</i>
        </a>
        <ul>
            <li @click.prevent="selectedDeletableId(rowData.id,rowIndex)">
                <a class="btn-floating waves-effect materialize-red modal-trigger tooltipped" href="#confirm-delete"
                   data-position="bottom" data-delay="50" :data-tooltip="trans('lang.delete')">
                    <i class="material-icons white-text">delete_forever</i>
                </a>
            </li>
            <li @click.prevent="getActive(),editUser(rowData.id)">
                <a class="btn-floating bluish waves-effect waves-light modal-trigger tooltipped" href="#client-modal"
                   data-position="bottom" data-delay="50" :data-tooltip="trans('lang.edit')"><i
                        class="material-icons white-text icon-vertically-middle">mode_edit</i></a>
            </li>
        </ul>
    </div>
</template>
<script>
    export default {
        props: ['rowData', 'rowIndex'],
        mounted() {

            $('.fixed-action-btn').floatingActionButton({direction: 'left',});
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
            });
        },
        methods: {
            getActive() {
                this.$hub.$emit('getActive', true);
            },
            editUser(id) {
                this.$hub.$emit("editUser", id);
            },
            selectedDeletableId(id, index) {
                this.$hub.$emit('selectedDeletableId', id, index);
            },
        }
    }
</script>
