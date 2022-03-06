<template>
    <div>
        <div class="modal-layout-header">
            <div class="container-fluid p-0">
                <div class="row">
                    <div class="col-10">
                        <h4 class="m-0" v-if="id">{{ trans('lang.change_user_role') }}</h4>
                        <h4 class="m-0" v-else>{{ trans('lang.invite_user') }}</h4>
                    </div>
                    <div class="col-2 text-right">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click.prevent="">
                            <span aria-hidden="true">
                                <i class="la la-close"/>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <pre-loader v-if="!hidePreLoader"/>
        <div class="modal-layout-content" v-else>
            <form class="form-row">
                <div class="form-group col-md-12" v-if="!id">
                    <label for="invitation-email">{{ trans('lang.login_email') }}</label>
                    <input id="invitation-email"
                           v-validate="'required'"
                           name="email"
                           class="form-control"
                           type="email"
                           v-model="email"
                           :class="{ 'is-invalid': errors.has('email') }">
                    <div class="heightError" v-show="errors.has('email')">
                        <small class="text-danger" v-show="errors.has('email')">
                            {{ errors.first('email') }}
                        </small>
                    </div>
                </div>

                <div class="form-group col-md-12" v-else>
                    <label>{{ trans('lang.name') }}</label>
                    <h6 class="m-0"> {{name}} </h6>
                </div>

                <div class="form-group maergin-top col-md-12">
                    <label for="roles">{{ trans('lang.role') }}</label>
                    <select v-model="invited_as"
                            v-validate="'required'"
                            name="inviteAs"
                            data-vv-as="role"
                            id="roles"
                            class="custom-select"
                            :class="{ 'is-invalid': errors.has('inviteAs') }">
                        <option value="" disabled selected>{{ trans('lang.choose_one') }}</option>
                        <option v-for="role in roles" :value="role.id"> {{role.title}}</option>
                    </select>
                    <div class="heightError">
                        <small class="text-danger" v-show="errors.has('inviteAs')">
                            {{ errors.first('inviteAs') }}
                        </small>
                    </div>
                </div>

                <div class="col-12">
                    <button class="btn app-color mobile-btn" type="submit" @click.prevent="save()" v-if="id">
                        {{ trans('lang.save') }}
                    </button>
                    <button class="btn app-color mobile-btn" type="submit" @click.prevent="save()" v-else>
                        {{ trans('lang.invite_button') }}
                    </button>
                    <button class="btn cancel-btn mobile-btn" data-dismiss="modal" @click.prevent="">
                        {{ trans('lang.cancel') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
<script>
    import axiosGetPost from '../../../helper/axiosGetPostCommon';

    export default {
        props: ['id', 'modalID'],
        extends: axiosGetPost,
        data() {
            return {
                hidePreLoader: true,
                email: '',
                invited_as: '',
                roles: [],
                name: '',

            }
        },
        created() {
            if (this.id) {
                this.getUserData('/user-role/' + this.id);
            } else {
                this.getRoleId('/allroleid');
            }
        },
        methods: {
            getUserData(route) {
                let instance = this;
                this.setPreLoader(false);
                this.axiosGet(route,
                    function (response) {
                        instance.name = response.data.full_name;
                        instance.invited_as = response.data.role_id;

                        //Chain method for response.
                        instance.getRoleId('/allroleid');
                    },
                    function (response) {
                        instance.setPreLoader(true);
                    },
                );
            },
            save() {
                let instance = this;
                instance.$validator.validateAll().then((result) => {
                    if (result) {
                        instance.inputFields = {
                            email: instance.email,
                            invited_as: instance.invited_as,
                            role_id: instance.invited_as,
                        };

                        if (instance.id) {
                            instance.postDataMethod('/roleassign/' + instance.id, instance.inputFields);
                        } else {
                            instance.postDataMethod('/invite', instance.inputFields);
                        }
                    }
                });
            },
            postDataThenFunctionality(response) {
                $(this.modalID).modal('hide');
                this.$hub.$emit('reloadDataTable');
            },
            postDataCatchFunctionality(error) {
                let instance = this;
            },
            getRoleId(route) {
                let instance = this;
                instance.setPreLoader(false);
                instance.axiosGet(route,
                    function (response) {
                        instance.roles = response.data;
                        instance.setPreLoader(true);
                    },
                    function (error) {
                        instance.setPreLoader(true);
                    },
                );
            }
        },
    }
</script>
