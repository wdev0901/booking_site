<template>
    <div>
        <div class="main-layout-card">
            <div class="main-layout-card-header-with-button">
                <div class="main-layout-card-content-wrapper">
                    <div class="main-layout-card-header-contents">
                        <h5 class="bluish-text">{{ trans('lang.holidays') }}</h5>
                    </div>
                    <div class="main-layout-card-header-contents float-right">
                        <button class="btn btn-primary app-color mobile-btn" data-toggle="modal"
                                data-target="#add-edit-modal"
                                @click.prevent="addEditAction('')">
                            {{ trans('lang.add') }}
                        </button>
                    </div>
                </div>
            </div>
            <pre-loader v-if="!hidePreLoader"/>
            <div v-else class="main-layout-card-content main-holiday-calendar">
                <FullCalendar defaultView="dayGridMonth"
                              :plugins="calendarPlugins"
                              :showNonCurrentDates="false"
                              :locale="locale"
                              :locales="locales"
                              @dateClick="handleDateClick"
                              @eventClick="eventClick"
                              :header="{left: 'today', center: 'prev, title, next', right: ''}"
                              :events="holidays"/>
            </div>
        </div>

        <div class="modal fade" id="add-edit-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered short-modal-dialog" role="document">
                <holiday-modal class="modal-content"
                               v-if="isActive"
                               :id="selectedItemId"
                               :modalID="modalID"
                               :startDay="startDay"/>
            </div>
        </div>

        <!-- Delete Modal -->
        <confirmation-modal id="confirm-delete"
                            :message="'holiday_will_be_deleted'"
                            :firstButtonName="'yes'"
                            :secondButtonName="'no'"
                            @confirmationModalButtonAction="confirmationModalButtonAction"/>

    </div>
</template>

<script>
    import axiosGetPost from '../../../helper/axiosGetPostCommon';
    import FullCalendar from '@fullcalendar/vue';
    import dayGridPlugin from '@fullcalendar/daygrid';
    import interactionPlugin from "@fullcalendar/interaction";
    import allLocales from '@fullcalendar/core/locales-all';

    export default {
        extends: axiosGetPost,
        components: {
            FullCalendar
        },
        data() {
            return {
                events: [
                    {title: 'event 1', date: '2019-12-01'},
                    {title: 'event 2', date: '2019-12-02'}
                ],
                locales: allLocales,
                locale: this.trans('lang.calender_language_set'),
                modalID: '#add-edit-modal',
                selectedItemId: '',
                isActive: false,
                calendarPlugins: [dayGridPlugin, interactionPlugin],
                holidays: [],
                startDay: '',
                hidePreLoader: true,
            }
        },
        created() {
            let instance = this;
            instance.getHolidays('/holiday');
            instance.languageCacheClearForCalender();
        },
        mounted() {

            let instance = this;
            instance.$hub.$on('viewEdit', function (id) {
                instance.addEditAction(id);
            });

            $(instance.modalID).on('hidden.bs.modal', function (e) {
                instance.isActive = false;
                instance.selectedItemId = '';
                instance.startDay = '';
            });

            instance.$hub.$on('addCalenderHolidays', function () {
                instance.holidays = [];
                instance.getHolidays('/holiday');
            });

        },

        methods: {
            getHolidays(route) {
                let instance = this;
                instance.setPreLoader(false);
                instance.axiosGet(route,
                    function (response) {

                        instance.setPreLoader(true);
                        instance.holidays = response.data.data;

                    }, function (error) {
                        instance.setPreLoader(true);
                    });
            },
            handleDateClick(arg) {
                let instance = this;
                instance.$hub.$on('viewEdit', function (id) {
                    instance.addEditAction(id);
                });
                instance.isActive = true;
                // instance.startDay = moment(arg.date).toString();
                instance.startDay = arg.dateStr;
                $(instance.modalID).modal('show')

            },
            eventClick(arg) {
                let instance = this;
                instance.addEditAction(arg.event.id);
                $(instance.modalID).modal('show')
            },
            confirmationModalButtonAction() {
                let instance = this;
                $('#confirm-delete').modal('hide');
                $(instance.modalID).modal('hide');
                instance.setPreLoader(false);
                axios.post(`/holiday-delete/${instance.deleteID}`)
                    .then(res => {
                        instance.showSuccessAlert(res.data.message);
                        instance.getHolidays('/holiday');
                    })
                    .catch(err => {
                        instance.setPreLoader(true);
                    })

            },
        },
    }

</script>

