<template>

    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">{{ trans('lang.break_time') }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <pre-loader v-if="!hidePreLoader" class="small-loader-container"></pre-loader>

            <form v-else class="form-row">
                <div class="form-group col-lg-12">

                    <label for="input-title">{{trans('lang.title') }}</label>

                    <input id="input-title" name="title"
                           v-model="submitData.title" type="text" class="form-control"
                           v-validate="'required'"
                           :class="{ 'is-invalid': isSubmit && errors.has('title') }">

                    <div class="heightError">
                        <small class="text-danger" v-show="errors.has('title')">
                            {{errors.first('title') }}
                        </small>
                    </div>
                </div>

                <div class="form-group col-lg-6">
                    <label for="start_time">{{ trans('lang.start_time') }}</label>
                    <div>
                        <vue-timepicker id="start_time"
                                        class="form-control"
                                        :placeholder="trans('lang.start_time')"
                                        hide-clear-button
                                        name="startTime"
                                        v-model="submitData.start_time"
                                        :format="timePickerFormat">
                        </vue-timepicker>
                    </div>
                    <div v-if="isSubmit && submitData.start_time == timePickerDefaultValueFormat"
                         class="heightError">
                        <small class="text-danger">
                            {{ trans('lang.start_time_is_required') }}
                        </small>
                    </div>
                </div>
                <div class="form-group col-lg-6">
                    <label for="end_time">{{ trans('lang.end_time') }}</label>
                    <div>
                        <vue-timepicker id="end_time"
                                        class="form-control"
                                        hide-clear-button
                                        :placeholder="trans('lang.end_time')"
                                        name="endTime"
                                        v-model="submitData.end_time"
                                        :format="timePickerFormat">
                        </vue-timepicker>
                    </div>
                    <div v-show="isSubmit && submitData.start_time == timePickerDefaultValueFormat"
                         class="heightError">
                        <small class="text-danger">
                            {{ trans('lang.end_time_is_required') }}
                        </small>
                    </div>
                </div>

                <div class="form-group col-12">
                    <label class="d-block" for="enabled">{{ trans('lang.enabled') }}</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="yes" id="yes" value="1"
                               v-model="submitData.is_enable">
                        <label class="form-check-label" for="yes">{{ trans('lang.yes') }}</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="no" id="no" value="0"
                               v-model="submitData.is_enable">
                        <label class="form-check-label" for="no">{{ trans('lang.no') }}</label>
                    </div>
                </div>

                <div class="col-12">
                    <button class="btn app-color mobile-btn" type="submit" @click.prevent="save()">{{trans('lang.save')}}</button>

                    <button class="btn cancel-btn mobile-btn" data-dismiss="modal" @click.prevent=""> {{trans('lang.cancel') }}</button>
                </div>
            </form>
        </div>
    </div>
    
</template>

<script>
    import axiosGetPost from '../../../helper/axiosGetPostCommon';

    export default {
        extends: axiosGetPost,
        props: {
            id: {
                default: null,
            },
            modalId: {
                type: String
            }
        },
        data(){
            return{
                isSubmit: false,
                timePickerDefaultValueFormat: '',
                submitData: {
                    title: '',
                    start_time: '',
                    end_time: '',
                    is_enable: '1'
                }
            }
        },
        mounted(){
            let instance = this;
            // get edit data
            if(instance.id) instance.getEditData();
            // 
            instance.setTimePickerDefaultValue();
        },
        methods:{
            save(){
                let instance = this;
                instance.isSubmit = true;

                this.$validator.validateAll().then((result) => {
                    if (result 
                        && instance.submitData.start_time != instance.timePickerDefaultValueFormat
                        && instance.submitData.end_time != instance.timePickerDefaultValueFormat) 
                    {
                        if (this.id) {
                            this.postDataMethod('/break-time/' + this.id, this.submitData);
                        }
                        else {
                            this.postDataMethod('/break-time/store', this.submitData);
                        }
                    }
                });
            },
            postDataThenFunctionality(response) {
                $(`#${this.modalId}`).modal('hide');
                this.$hub.$emit('reloadDataTable');
            },

            getEditData() {
                let instance = this,
                    url = `/break-time/${this.id}`;

                this.setPreLoader(false);

                this.axiosGet(url,
                    function (response) {

                        instance.submitData = response.data;
                        
                        instance.setPreLoader(true);
                    },
                    function (response) {
                        instance.setPreLoader(true);
                    },
                );
            },

            setTimePickerDefaultValue() {
                let instance = this;

                if (this.timeFormat == "12h") instance.timePickerDefaultValueFormat = "hh:00 AM";
                else instance.timePickerDefaultValueFormat = "HH:00";
                
                instance.submitData.start_time = instance.timePickerDefaultValueFormat;
                instance.submitData.end_time = instance.timePickerDefaultValueFormat;
            },
        }
    }
</script>