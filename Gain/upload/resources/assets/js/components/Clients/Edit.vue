<template>
    <div>
        <div class="modal-layout-header">
            <div class="container-fluid p-0">
                <div class="row">
                    <div class="col-10">
                        <h4 class="m-0" v-if="id">{{ trans('lang.edit') }}</h4>
                    </div>
                    <div class="col-2 text-right">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click.prevent="">
                            <span aria-hidden="true"><i class="la la-close"></i></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <pre-loader v-if="!hidePreLoader"></pre-loader>
        <div class="modal-layout-content" v-else>
            <form class="form-row">
                <div class="form-group col-md-12">
                    <label for="first_name">{{ trans('lang.first_name') }}</label>
                    <input class="form-control" v-validate="'required'" id="first_name" name="first_name"
                           :data-vv-as="trans('lang.first_name_small')" type="text" v-model="first_name">
                    <div class="heightError">
                        <small class="text-danger" v-show="errors.has('first_name')">{{
                            errors.first('first_name') }}
                        </small>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <label for="last_name">{{ trans('lang.last_name') }}</label>
                    <input class="form-control" v-validate="'required'" id="last_name" name="last_name"
                           :data-vv-as="trans('lang.last_name_small')" type="text" v-model="last_name">
                    <div class="heightError">
                        <small class="text-danger" v-show="errors.has('last_name')">{{
                            errors.first('last_name') }}
                        </small>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <label for="email">{{ trans('lang.login_email') }}</label>
                    <input id="email" name="email" class="form-control" type="text"
                           v-validate="'required'" v-model="email" :class="{ 'is-invalid': errors.has('email') }">
                    <div class="heightError" v-show="errors.has('last_name')">
                        <small class="text-danger" v-show="errors.has('email')">{{ errors.first('email') }}</small>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <label for="phone">{{ trans('lang.phone') }}</label>
                    <input id="phone" name="phone" v-model="phone" class="form-control" type="text">
                </div>
                <div class="col-12">
                    <button class="btn app-color mobile-btn" type="submit" @click.prevent="update()">
                        {{trans('lang.save') }}
                    </button>
                    <button class="btn cancel-btn mobile-btn" data-dismiss="modal" @click.prevent="">{{
                        trans('lang.cancel') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
<script>
    import axiosGetPost from '../../helper/axiosGetPostCommon';

    export default {
        props: ['id', 'modalID'],
        extends: axiosGetPost,
        data() {
            return {
                first_name: '',
                last_name: '',
                email: '',
                phone: '',
                hidePreLoader: true,
            }
        },
        created() {
            if (this.id) {
                this.getClientData('/client-edit/' + this.id);
            }
        },
        methods: {
            update() {
                let instance = this;
                (instance.submitted = true),
                    instance.$validator.validateAll().then(result => {
                        if (result) {
                            instance.inputFields = {
                                first_name: instance.first_name,
                                last_name: instance.last_name,
                                email: instance.email,
                                phone: instance.phone,
                            };

                            if (instance.id) {
                                instance.postDataMethod('/client-update/' + instance.id, instance.inputFields);
                            }
                        }
                    });
            },

            postDataThenFunctionality(response) {
                let instance = this;
                $(instance.modalID).modal('hide');
                instance.$hub.$emit('reloadDataTable');
            },
            postDataCatchFunctionality(error) {
                let instance = this;
            },
            getClientData(route) {
                let instance = this;
                this.setPreLoader(false);
                instance.axiosGet(route,
                    function (response) {
                        instance.first_name = response.data.first_name;
                        instance.last_name = response.data.last_name;
                        instance.email = response.data.email;
                        instance.phone = response.data.phone;
                        instance.setPreLoader(true);
                    },
                    function (error) {
                        instance.setPreLoader(true);
                    },
                );
            },

        }
    }
</script>