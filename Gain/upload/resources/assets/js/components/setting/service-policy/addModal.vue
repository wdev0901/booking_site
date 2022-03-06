<template>
    <div>
        <div class="modal-header">
            <h4 class="modal-title">{{ trans('lang.service_policy') }}</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="title">{{trans('lang.title') }}</label>
                    <input id="title" v-model="title" name="title" type="text"
                           class="form-control"
                           v-validate="'required'"
                           :class="{ 'is-invalid': submitted && errors.has('title') }">
                    <div class="heightError">
                        <small class="text-danger" v-show="errors.has('title')">
                            {{
                            errors.first('title') }}
                        </small>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <label for="description">{{trans('lang.description') }}</label>
                    <textarea id="description" class="form-control"
                              v-model="description" name="description"
                              v-validate="'required'"
                              :class="{ 'is-invalid': submitted && errors.has('description') }"></textarea>
                    <div class="heightError">
                        <small class="text-danger" v-show="errors.has('description')">
                            {{
                            errors.first('description') }}
                        </small>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>{{trans('lang.consider_children_for_price') }}</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" v-model="consider_children_for_price"
                               name="consider_children_for_price" id="childen_for_price_yes" v-validate="'required'"
                               :class="{ 'is-invalid': submitted && errors.has('consider_children_for_price') }"
                               value="yes">
                        <label for="childen_for_price_yes" class="radio-button-label">{{trans('lang.yes') }}</label>
                        <input class="form-check-input" type="radio" v-model="consider_children_for_price"
                               name="consider_children_for_price" id="consider_children_for_price"
                               v-validate="'required'"
                               :class="{ 'is-invalid': submitted && errors.has('consider_children_for_price') }"
                               value="no">
                        <label for="childen_for_price_no" class="radio-button-label">{{trans('lang.no') }}</label>
                    </div>
                    <div class="heightError">
                        <small class="text-danger" v-show="errors.has('consider_children_for_price')">
                            {{
                            errors.first('consider_children_for_price') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary app-color" @click.prevent="inviteUser()" type="submit">{{trans('lang.save')
                }}
            </button>
        </div>
    </div>
</template>
<script>
    import axiosGetPost from '../../../helper/axiosGetPostCommon';

    export default {
        extends: axiosGetPost,
        data() {
            return {
                title: '',
                description: '',
                consider_children_for_price: '',
                submitted: false,
            }
        },
        methods: {
            inviteUser() {
                (this.submitted = true),
                    this.$validator.validateAll().then(result => {
                        if (result) {
                            this.inputFields = {
                                title: this.title,
                                description: this.description,
                                consider_children_for_price: this.consider_children_for_price,
                            };

                            this.axiosPost("/servicepolicystore", {
                                title: this.title,
                                description: this.description,
                                consider_children_for_price: this.consider_children_for_price,
                            });
                        }
                    });

            },
        }
    }
</script>