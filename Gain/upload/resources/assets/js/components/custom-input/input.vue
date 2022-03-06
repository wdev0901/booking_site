<template>
    <div>
        <div v-if="custom_form_fields.length > 0">
            <template v-for="custom_form_fields in newfields">
                <div class="custom-input-layout">
                    <div v-for="(customField, index) in custom_form_fields">
                        <div :class="{'custom-field-margin': index + 1 !== custom_form_fields.length.length}"
                             v-if="customField.field_type != 'checkbox' && customField.field_type != 'radio'">
                            <label :for="'custom-field'+customField.id">{{customField.label}}</label>
                            <input :id="'custom-field'+customField.id"
                                   class="form-control"
                                   @keyup="passData"
                                   :type="customField.field_type"
                                   v-model="options[customField.id]"
                                   v-if="customField.field_type != 'date' && customField.field_type != 'select' && customField.field_type != 'textarea'">

                            <datepicker :id="'custom-field'+customField.id"
                                        @change="passData"
                                        v-model="options[customField.id]"
                                        :bootstrap-styling="true"
                                        :format="datePickerDateFormatter(options[customField.id])"
                                        v-else-if="customField.field_type == 'date'">
                            </datepicker>


                            <select :id="'custom-field'+customField.id"
                                    class="custom-select"
                                    @change="passData"
                                    v-model="options[customField.id]"
                                    v-else-if="customField.field_type == 'select'">
                                <option value="" disabled selected>{{ trans('lang.choose_one') }}</option>
                                <option v-for="option in customField.options" :value="option">{{option}}</option>
                            </select>
                            <textarea :id="'custom-field'+customField.id"
                                      class="form-control"
                                      @change="passData"
                                      v-model="options[customField.id]"
                                      v-else>
                            </textarea>
                        </div>
                        <div :class="{'custom-field-margin': index + 1 !== custom_form_fields.length.length}"
                             v-else-if="customField.field_type == 'checkbox'">
                            <label class="d-block">{{customField.label}}</label>
                            <template v-for="(option,index) in customField.options">
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input :id="'custom-field'+customField.id+index"
                                           :type="customField.field_type"
                                           class="custom-control-input"
                                           @change="checkBox()"
                                           v-model="check"
                                           :value="customField.id+option">
                                    <label class="custom-control-label" :for="'custom-field'+customField.id+index">{{option}}</label>
                                </div>
                            </template>
                        </div>
                        <div :class="{'custom-field-margin': index + 1 !== custom_form_fields.length.length}" v-else>
                            <label class="d-block">{{customField.label}}</label>
                            <template v-for="(option,index) in customField.options">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input :id="'custom-field'+customField.id+index"
                                           :type="customField.field_type"
                                           class="custom-control-input"
                                           @change="passData()"
                                           v-model="options[customField.id]"
                                           :value="option">
                                    <label class="custom-control-label" :for="'custom-field'+customField.id+index">{{option}}</label>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </div>

    </div>
</template>

<script>
    import axiosGetPost from '../../helper/axiosGetPostCommon';

    export default {
        extends: axiosGetPost,
        props: ['custom_form_fields', 'date_id', 'newCustomFieldData', 'size'],
        data() {
            return {
                options: {},
                check: [],
                radioText: [],
                dateId: [],
                newDateId: [],
                DelCheck: [],
            }
        },
        mounted() {
            let instance = this;
            instance.custom_form_fields.forEach(function (field) {
                if (field.field_type == 'date') {
                    $('#custom-field' + field.id).on('change', function () {
                        let value = $(this).val();
                        let id = $(this).attr("id");
                        id = id.replace('custom-field', '');
                        instance.options[id] = value;
                        let check = instance.newDateId.includes(parseInt(id));
                        if (!check) {
                            instance.newDateId.push(id);
                        }

                        instance.passData();
                    });
                }
            });


            instance.options = instance.newCustomFieldData;
        },
        computed: {
            newfields: function () {
                let index, increment, newFields = [], newArray = [];
                if (this.size == 'large') increment = 2;
                else increment = 3;

                for (index = 0; index <= this.custom_form_fields.length; index += increment) {
                    newArray = this.custom_form_fields.filter(function (element, i) {
                        return index <= i && i < index + increment;
                    });

                    newFields.push(newArray);
                }
                return newFields;
            }
        },

        watch: {
            newCustomFieldData(newValue) {
                this.setData(newValue);

            }
        },
        created() {

        },
        methods: {
            passData() {
                this.$emit('fieldOptions', this.options, this.newDateId);
            },
            setData(value) {
                let instance = this;
                if (value != null) {
                    instance.dateId = instance.date_id;
                    instance.options = value;
                    instance.newDateId = instance.dateId;
                    instance.dateId.forEach(function (element) {
                        instance.options[element] = moment(instance.options[element], 'YYYY-MM-DD').format(instance.dateFormat.toUpperCase());
                    });

                    if (value['check'] != undefined) {
                        instance.custom_form_fields.forEach(function (element) {
                            if (element.field_type == 'checkbox') {
                                element.options.forEach(function (option) {
                                    instance.DelCheck.push(element.id + option);
                                });
                            }
                        });

                        let checkOptions = value['check'];
                        checkOptions.forEach(function (value) {
                            if (instance.DelCheck.includes(value)) {
                                instance.check.push(value)
                            }
                        });

                        instance.options.check = instance.check;
                    }
                }

                instance.passData();
            },
            checkBox() {
                let instance = this;
                instance.options.check = instance.check;
                instance.passData();

            },
        },
    }

</script>