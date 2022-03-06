<template>
    <div>
        <div class="modal-layout-header">
            <div class="container-fluid p-0">
                <div class="row">
                    <div class="col-10">
                        <h4 class="m-0" v-if="id">{{ trans('lang.edit_holiday') }}</h4>
                        <h4 class="m-0" v-else>{{ trans('lang.add_holiday') }}</h4>
                    </div>
                    <div class="col-2 text-right">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click.prevent="">
                            <span aria-hidden="true"><i class="la la-close"/></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <pre-loader v-if="!hidePreLoader"/>
        <div class="modal-layout-content" v-else>
            <form class="form-row">
                <div class="form-group col-md-12">
                    <label for="title">{{ trans('lang.title') }}</label>
                    <input v-validate="'required'" name="title" id="title" type="text" class="form-control"
                           v-model="title"
                           :class="{ 'is-invalid': errors.has('title') }">
                    <div class="heightError">
                        <small class="text-danger" v-show="errors.has('title')">{{ errors.first('title') }}</small>
                    </div>
                </div>

                <div class="form-group col-md-12">
                    <label class="" for="start_date">{{ trans('lang.start_date') }}</label>
                    <datepicker
                            :input-class="{ 'is-invalid': errors.has('start date')}" v-validate="'required'"
                            name="start date"
                            id="start_date"
                            v-model="start_date"
                            :bootstrap-styling="true"
                            :format="datePickerDateFormatter(start_date)">
                    </datepicker>
                    <div v-if="errors.has('start date')" class="heightError">
                        <small class="text-danger" v-show="errors.has('start date')">{{ errors.first('start date') }}
                        </small>
                    </div>
                </div>

                <div class="form-group col-md-12">
                    <label class="">{{ trans('lang.end_date') }}</label>

                    <datepicker
                            :input-class="{ 'is-invalid': errors.has('end date')}" v-validate="'required'"
                            name="end date"
                            id="datePickerDb"
                            v-model="end_date"
                            :bootstrap-styling="true"
                            :format="datePickerDateFormatter(end_date)">
                    </datepicker>

                    <div v-if="errors.has('end date')" class="heightError">
                        <small class="text-danger" v-show="errors.has('end date')">{{ errors.first('start date') }}
                        </small>
                    </div>
                </div>

                <div class="col-12">
                    <button class="btn app-color mobile-btn" type="submit" @click.prevent="save()">
                        {{trans('lang.save')}}
                    </button>
                    <button type="button" class="btn cancel-btn mobile-btn" data-dismiss="modal" @click.prevent="">
                        {{trans('lang.cancel')}}
                    </button>

                    <button v-if="id" type="button" data-toggle="modal" data-target="#confirm-delete"
                            class="btn btn-danger mobile-btn float-right" @click.prevent="selectedDeletableId(id)">
                        {{trans('lang.delete')}}
                    </button>
                </div>
            </form>

        </div>
    </div>
</template>
<script>
    import axiosGetPost from '../../../helper/axiosGetPostCommon';

    export default {
        extends: axiosGetPost,
        props: ['id', 'modalID', 'startDay'],
        data() {
            return {
                hidePreLoader: true,
                item: [],
                title: '',
                start_date: '',
                end_date: '',
                error: false,
                is_disabled: false,
                errorInvalid: {
                    title: false,
                    start: false,
                    end: false,
                },
                dateEndError: this.trans('lang.required_input_field'),
            }
        },
        created() {
            if (this.id) {
                this.getHolidaysData('/holidays/' + this.id);
            }
        },
        mounted() {
            this.start_date = this.startDay;
            this.end_date = this.startDay;
        },
        methods: {
            getHolidaysData(route) {
                let instance = this;
                instance.setPreLoader(false);
                instance.axiosGet(route,
                    function (response) {
                        instance.title = response.data.title;
                        instance.start_date = response.data.start_date;
                        instance.end_date = response.data.end_date;
                        instance.setPreLoader(true);

                    }, function (error) {
                        instance.setPreLoader(true);
                    });
            },
            save() {
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        this.inputFields = {
                            title: this.title,
                            start_date: this.start_date,
                            end_date: this.end_date,
                        };

                        if (this.id) {
                            this.postDataMethod('/holiday-update/' + this.id, this.inputFields);
                        } else {
                            this.postDataMethod('/holiday-store', this.inputFields);
                        }
                    }
                });
            },
            postDataThenFunctionality(response) {
                $(this.modalID).modal('hide');
                this.$hub.$emit('addCalenderHolidays');
            },
            postDataCatchFunctionality(error) {
                let instance = this;
            },
            selectedDeletableId(id) {
                this.$hub.$emit('selectedDeletableId', id, 1);
            }
        }
    }
</script>