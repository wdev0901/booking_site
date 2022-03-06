<template>
    <div>
        <pre-loader v-if="!hidePreLoader"/>
        <div class="service-book-area" v-else>

            <div class="main-banner"
                 :style="{'background-image': 'url(' + publicPath+'/images/' + 'background/background-image.jpeg' + ')'}">
                <div class="container">
                    <div class="text-white">
                        <h1 class="mb-0 banner-title">{{ landing_page_header }}</h1>
                        <p v-if="landing_page_message != ''" class="mb-4 banner-subtitle">
                            {{ landing_page_message }}
                        </p>
                    </div>
                    <div class="banner-area"
                         :class="{'pb-0': (service_id && isContinue == false && isBookForm == false) || isBookForm}">
                        <div class="banner-service-area">
                            <div class="bg-white banner-content rounded-sm"
                                 :class="{'bottom-radius-0': (service_id && isContinue == false && isBookForm == false) || isBookForm}">
                                <div class="row">
                                    <div :class="employees.length > 0 ? 'col-12 col-sm-12 col-md-4 col-lg-4' : 'col-12 col-sm-12 col-md-6 col-lg-6'">
                                        <v-selectpage class="mb-4 mb-sm-4 mb-md-0 mb-xl-0 text-truncate"
                                                      :placeholder="trans('lang.select_service')"
                                                      :data="serviceListData"
                                                      key-field="id"
                                                      show-field="title"
                                                      :pagination="false"
                                                      v-model="service_id">
                                        </v-selectpage>
                                    </div>
                                    <div :class="employees.length > 0 ? 'col-12 col-sm-12 col-md-4 col-lg-4' : 'col-12 col-sm-12 col-md-6 col-lg-6'">
                                        <div class="dropdown guest-selection">
                                            <button class="btn btn-block dropdown-toggle"
                                                    type="button"
                                                    id="dropdownMenuButton"
                                                    data-toggle="dropdown"
                                                    aria-haspopup="true"
                                                    value="somthimg"
                                                    aria-expanded="false">
                                                <i class="las la-user-tie text-landing-color"/>
                                                <template>{{ noOfAdult }} {{ trans('lang.adults')}} </template> <template v-if="selectService.consider_children_for_price == 'Yes'"> - {{ noOfChildren }} {{ trans('lang.child')}}</template>
                                            </button>
                                            <div class="dropdown-menu dropdown-guest"
                                                 aria-labelledby="dropdownMenuButton">
                                                <form>
                                                    <div class="dropdown-item">
                                                        {{ trans('lang.adults')}}
                                                        <span class="guest-number float-right">
                                                        <a href="#" class="text-secondary"
                                                           @click.prevent="plusMinusButtonAction('-', 'adult')">
                                                            <i class="las la-minus-circle"/>
                                                        </a>
                                                        <span class="mx-2">{{ noOfAdult }}</span>
                                                        <a href="#" class="text-secondary tooltip-guest-adult"
                                                           @click.prevent="plusMinusButtonAction('+', 'adult')">
                                                            <i class="las la-plus-circle"/>
                                                        </a>
                                                    </span>
                                                    </div>
                                                    <div class="dropdown-item"
                                                         v-if="selectService.consider_children_for_price == 'Yes'">
                                                        {{ trans('lang.children')}}
                                                        <span class="guest-number float-right">
                                                            <a href="#" class="text-secondary"
                                                               @click.prevent="plusMinusButtonAction('-', 'children')">
                                                                <i class="las la-minus-circle"/>
                                                            </a>
                                                            <span class="mx-2">{{ noOfChildren }}</span>
                                                            <a href="#" class="text-secondary tooltip-guest-child"
                                                               @click.prevent="plusMinusButtonAction('+', 'children')">
                                                                <i class="las la-plus-circle"/>
                                                            </a>
                                                        </span>
                                                        <br>
                                                        <span class="text-secondary">(0-{{ ageRange }} years, {{ discount }} %)</span>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-if="employees.length > 0" class="col-12 col-sm-12 col-md-4 col-lg-4">
                                        <v-selectpage class="mb-4 mb-sm-4 mb-md-0 mb-xl-0"
                                                      :placeholder="trans('lang.select_assistant')"
                                                      :data="employees"
                                                      key-field="id"
                                                      show-field="full_name"
                                                      :pagination="false"
                                                      v-model="employee_id">
                                        </v-selectpage>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="calendar-form-wrapper">
                <div class="container">
                    <div class="bg-white calendar-form-container"
                         :class="{'custom-margin-top': (service_id && isContinue == false && isBookForm == false) || isBookForm}">
                        <pre-loader v-if="!hideCalenderLoader"/>
                        <div v-else class="row justify-content-center">
                            <div v-if="service_id && isContinue == false && isBookForm == false" class="col-12">
                                <div class="calendarTimeSlot">
                                    <div class="calendar-main">
                                        <div :class="{'bookCalendar': bookCalendar == true}">
                                            <div class="row justify-content-center">
                                                <div class="col-12 col-sm-12"
                                                     :class="{'col-md-8 col-lg-8': isTime }">
                                                    <FullCalendar v-if="isFullCalendar"
                                                                  defaultView="dayGridMonth"
                                                                  :locale="locale"
                                                                  :locales="locales"
                                                                  :plugins="calendarPlugins"
                                                                  :showNonCurrentDates="true"
                                                                  :weekends="true"
                                                                  :selectable="true"
                                                                  @dateClick="handleDateClick"
                                                                  :header="{left: 'prev', center: 'title', right: 'next'}"/>
                                                </div>
                                                <div v-if="isTime"
                                                     class="col-12 col-sm-12 col-md-4 col-lg-4 col-lg-4">
                                                    <div class="time-slot-container">
                                                        <pre-loader v-if="!timePreLoader"/>
                                                        <div class=""
                                                             v-for="(serviceTime, index) in serviceTiming"
                                                             v-else>
                                                            <a href="#"
                                                               class="single-slot"
                                                               :class="{'selectedTime': selectTime.includes(serviceTime), 'disabled-nav': noOfAdult + noOfChildren > availableSeatsForTiming[index] || availableSeatsForTiming[index] == 0}"
                                                               @click.prevent="handleDateTime(serviceTime, index)">
                                                                <span>{{serviceTime}} </span>
                                                                <span v-show="noOfAdult + noOfChildren > availableSeatsForTiming[index] || availableSeatsForTiming[index] == 0"> -{{trans('lang.available')}}:{{ availableSeatsForTiming[index] }}</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-center" v-if="isCalendarSet">
                                    <div class="col-6 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                                        <a @click.prevent="bookingForm()">
                                            <button type="button" class="btn btn-landing btn-block btn-continue">
                                                {{ trans('lang.continue') }}
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div v-if="isBookForm" class="col-12">
                                <pre-loader v-if="!submitPreLoader"/>

                                <!-- booking information -->
                                <form v-else>
                                    <div class="row mb-5">
                                        <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                            <div v-if="!selectService.service_image"
                                                 class="service-image-wrapper service-img-container"
                                                 :style="{'background-image': 'url(' + publicPath+'/uploads/' + 'service/service.png' + ')'}">
                                            </div>
                                            <div v-else
                                                 class="service-image-wrapper service-img-container"
                                                 :style="{'background-image': 'url(' + publicPath+'/files/' + selectService.service_image.image + ')'}">
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-8 col-lg-8">
                                            <div class="service-information-wrapper">
                                                <div class="mb-3 service-title">
                                                    <h4>{{ selectService.title }}</h4>
                                                </div>
                                                <div class="mb-3">
                                                    <p class="text-with-opacity">{{ selectService.description }}</p>
                                                </div>
                                                <div class="mb-3">
                                                    <div class="mb-0">
                                                        <span class="service-info-heading">{{ trans('lang.salon_book_date') }}: </span>

                                                        <span class="service-info-value">{{ dateFormatBooking(startDay) }}</span>

                                                        <a href="#"
                                                           class="ml-2 tooltip-change-date"
                                                           @click.prevent="reSetDateTime">
                                                            <i class="las la-calendar"/> {{ trans('lang.change') }}
                                                        </a>
                                                    </div>
                                                    <p class="mb-0">
                                                        <span class="service-info-heading">{{ trans('lang.time') }}: </span>
                                                        <span class="badge badge-no-slot" v-if="disable">
                                                                {{ trans('lang.no_slots_available') }}
                                                            </span>
                                                        <span class="badge mr-1" v-else v-for="stime in selectTime">
                                                                {{stime}}
                                                            </span>
                                                    </p>
                                                    <p class="mb-0">
                                                        <span class="service-info-heading">{{ trans('lang.no_of_guest') }}: </span>
                                                        <span class="service-info-value">{{ noOfAdult }} {{ trans('lang.adults')}} - {{ noOfChildren }} {{ trans('lang.children')}}</span>

                                                    </p>
                                                </div>

                                                <h5 class="mb-3 text-landing-color">
                                                    {{ trans('lang.price') }}: {{
                                                    numberFormatSalonGeneralBooking(selectService.price) }}
                                                </h5>
                                                <div class="alert price-label mb-0" role="alert">
                                                    <span>{{ trans('lang.net_price') }}</span>
                                                    <span class="float-right">{{ numberFormatSalonGeneralBooking(netPrice) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="form-group col-md-6">
                                            <label for="full_name">{{ trans('lang.full_name') }}</label>
                                            <input v-validate="'required'"
                                                   :data-vv-as="trans('lang.full_name')"
                                                   name="full_name"
                                                   id="full_name"
                                                   type="text"
                                                   class="form-control"
                                                   :disabled="!isEmpty(user)"
                                                   v-model="full_name"
                                                   @input="setServiceIdCookie">
                                            <div class="heightError">
                                                <small class="text-danger" v-show="errors.has('full_name')">
                                                    {{ errors.first('full_name') }}
                                                </small>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="email">{{ trans('lang.input_email') }}</label>
                                            <!--Either email or phone-->
                                            <!--<input name="email"
                                                   id="email"
                                                   type="text"
                                                   class="form-control"
                                                   @change="resetCustomEmailPhoneValidation"
                                                   v-model="email">
                                            <div class="heightError">
                                                <small class="text-danger" v-if="isEmailOrPhoneProvided">
                                                    {{ trans('lang.either_email_or_phone_is_required') }}
                                                </small>
                                            </div>
-->

                                            <input v-validate="'required'"
                                                   :data-vv-as="trans('lang.input_email')"
                                                   name="email"
                                                   id="email"
                                                   type="text"
                                                   class="form-control"
                                                   v-model="email"
                                                   :disabled="!isEmpty(user)"
                                                   @input="setServiceIdCookie">
                                            <div class="heightError">
                                                <small class="text-danger" v-show="errors.has('email')">
                                                    {{ errors.first('email') }}
                                                </small>
                                            </div>

                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="phone_number">
                                                {{ trans('lang.phone_number') }}
                                            </label>
                                            <vue-tel-input id="phone_number"
                                                           type="text"
                                                           v-model="phone_number"
                                                           mode="international"
                                                           name="phone_number"
                                                           @input="getCountry"
                                                           :disabled="!isEmpty(user)"
                                                           @change="resetCustomEmailPhoneValidation"
                                                           :placeholder="trans('lang.enter_phone')"
                                                           class="form-control"></vue-tel-input>
                                        </div>

                                        <div class="form-group col-6">
                                            <custom-input-field v-if="isActiveFields"
                                                                :custom_form_fields="customFields"
                                                                :date_id="customFieldDateId"
                                                                :newCustomFieldData="newCustomFieldData"
                                                                size="large"
                                                                @fieldOptions="fieldOptions">
                                            </custom-input-field>
                                        </div>
                                    </div>
                                    <div class="row justify-content-center">
                                        <div class="form-group col-6 col-sm-4 col-md-4 col-lg-4 col-xl-4 mb-0 text-center">
                                            <button class="btn btn-block btn-landing" type="submit" v-if="!disable"
                                                    @click.prevent="checkConfirm">
                                                {{ trans('lang.confirm') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--Service list area-->
            <div id="services" class="service-list-area">

                <!-- Multiple service -->
                <div class="container" v-if="serviceListData.length > 1">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-5">
                                <h2 class="section-title mb-0">
                                    {{ trans('lang.our_services') }}
                                </h2>
                                <p class="subtitle">{{ trans('lang.service_subtitle') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div :class="{'col-sm-6 col-md-6':(serviceListData.length === 2),
                        'col-sm-4 col-md-4':(serviceListData.length === 3 || serviceListData.length === 5 || serviceListData.length  === 6 || serviceListData.length  === 9),
                        'col-sm-3 col-md-3 ': (serviceListData.length)}" v-for="serviceData in serviceListData">
                            <div class="card">
                                <div v-if="!serviceData.service_image"
                                     class="service-img-container"
                                     :class="{'height-16-rem': (serviceListData.length === 3 || serviceListData.length === 5 || serviceListData.length  === 6 || serviceListData.length  === 9), 'height-11-rem': (serviceListData.length)}"
                                     :style="{'background-image': 'url(' + publicPath+'/uploads/' + 'service/service.png' + ')'}">
                                </div>
                                <div v-else
                                     class="service-img-container"
                                     :class="{'height-16-rem': (serviceListData.length === 3 || serviceListData.length === 5 || serviceListData.length  === 6 || serviceListData.length  === 9), 'height-11-rem': (serviceListData.length)}"
                                     :style="{'background-image': 'url(' + publicPath+'/files/' + serviceData.service_image.image + ')'}">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title text-truncate mb-4">{{ serviceData.title }}</h5>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="media mb-3">
                                                <i class="las la-clock align-self-center mr-2"/>
                                                <div class="media-body">
                                                    <p class="service-info-heading">{{ trans('lang.duration')}}:</p>
                                                    <p class="service-info-value mb-0">
                                                        {{ humanReadableTimeFormat(serviceData.service_duration) }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="media mb-3">
                                                <i class="las la-dollar-sign la-2x align-self-center mr-2"/>
                                                <div class="media-body">
                                                    <p class="service-info-heading">
                                                        {{trans('lang.price_per_person')}}:
                                                    </p>
                                                    <p class="service-info-value mb-0">
                                                        {{ numberFormatSalonGeneralBooking(serviceData.price) }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="media">
                                                <i class="las la-chair la-2x align-self-center mr-2"/>
                                                <div class="media-body">
                                                    <p class="service-info-heading">
                                                        {{trans('lang.capacity_per_service')}}:
                                                    </p>
                                                    <p class="service-info-value mb-0">
                                                        {{ serviceData.available_seat }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer pt-0">
                                    <a href="#"
                                       class="btn btn-landing"
                                       @click.prevent="serviceBook(serviceData)">
                                        {{trans('lang.book')}}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Single service -->
                <div class="container" v-else-if="serviceListData.length === 1">

                    <div class="row">
                        <div class="col-12">
                            <div class="mb-5">
                                <h2 class="section-title mb-0">
                                    {{ trans('lang.our_service') }}
                                </h2>
                                <p class="subtitle">{{ trans('lang.you_can_book_service_easily') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="row">
                                    <div class="col-12 col-sm-4 pr-sm-0">
                                        <div v-if="!singleService.service_image"
                                             class="service-img-container rounded-left-img-container"

                                             :style="{'background-image': 'url(' + publicPath+'/uploads/' + 'service/service.png' + ')'}">
                                        </div>
                                        <div v-else
                                             class="service-img-container rounded-left-img-container"

                                             :style="{'background-image': 'url(' + publicPath+'/files/' + singleService.service_image.image + ')'}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-8 pl-sm-0">
                                        <div class="card-body">
                                            <div class="mb-3 service-title">
                                                <h4>{{ singleService.title }}</h4>
                                            </div>
                                            <div class="mb-3">
                                                <p class="text-with-opacity">{{ singleService.description }}</p>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="media mb-3">
                                                        <i class="las la-clock align-self-center mr-2"/>
                                                        <div class="media-body">
                                                            <p class="service-info-heading">{{
                                                                trans('lang.duration')}}:</p>
                                                            <p class="service-info-value mb-0">
                                                                {{
                                                                humanReadableTimeFormat(singleService.service_duration)
                                                                }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="media mb-3">
                                                        <i class="las la-dollar-sign la-2x align-self-center mr-2"/>
                                                        <div class="media-body">
                                                            <p class="service-info-heading">
                                                                {{trans('lang.price_per_person')}}:
                                                            </p>
                                                            <p class="service-info-value mb-0">
                                                                {{ numberFormatSalonGeneralBooking(singleService.price)
                                                                }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="media">
                                                        <i class="las la-chair la-2x align-self-center mr-2"/>
                                                        <div class="media-body">
                                                            <p class="service-info-heading">
                                                                {{trans('lang.capacity_per_service')}}:
                                                            </p>
                                                            <p class="service-info-value mb-0">
                                                                {{ singleService.available_seat }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer pt-0">
                                            <a href="#"
                                               class="btn btn-landing"
                                               @click.prevent="serviceBook(singleService)">
                                                {{trans('lang.book')}}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--Thank you modal-->
        <div class="modal modal-landing fade" id="success-modal" tabindex="-1" role="dialog"
             aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body justify-content-center">
                        <h4 class="modal-title text-center mb-4">{{trans('lang.congratulations') }}</h4>
                        <div class="text-center thank-you-icon">
                            <i class="las la-check la-4x mb-4 animated rotateIn"/>
                        </div>
                        <h5 class="text-center mb-4">
                            {{trans('lang.successful_service_booked_message') }}
                        </h5>
                        <div class="row justify-content-center">
                            <div class="col-6 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                                <button type="button"
                                        class="btn btn-block btn-landing"
                                        data-dismiss="modal"
                                        @click.prevent="resetBook()">
                                    {{ trans('lang.thank_you') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--Payment modal-->
        <div class="modal modal-landing fade" id="payment-modal" tabindex="-1" role="dialog"
             aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <pre-loader v-if="!hidePreLoader"/>
                    <div v-else class="modal-body justify-content-center">
                        <h4 class="mb-4 text-center">
                            {{ trans('lang.hi') }} {{first_name}} {{last_name}}! <br>
                            <span class="text-payment-confirm">
                                {{ trans('lang.confirm_payment_message') }}
                            </span>
                        </h4>
                        <h1 class="m-3 text-center text-landing-color text-net-price">
                            {{ numberFormatSalonGeneralBooking(netPrice) }}
                        </h1>

                        <div class="payments-button-wrapper">
                            <div class="row" v-if="!enablePaypal">
                                <div class="col-12">
                                    <div class="payment-methods-button-container"
                                         v-for="paymentMethod in paymentMethods">
                                        <a href="#"
                                           class="btn payment-methods-btn"
                                           v-if="paymentMethod.type=='stripe' && paymentMethod.available_to_client==1 && paymentMethod.enable==1 && paymentMethod.option"
                                           :class="{'selectedPaymentMethod': paymentMethodClicked==paymentMethod.title}"
                                           @click.prevent="paymentMethodClicked = paymentMethod.title,isPaymentMethodDefault=true,selectedMethodId=paymentMethod.id,paymentMethodType=paymentMethod.type,pay()">
                                            {{paymentMethod.title}}
                                        </a>
                                        <a href="#"
                                           class="btn payment-methods-btn"
                                           v-if="paymentMethod.type=='paypal' && paymentMethod.available_to_client==1 && paymentMethod.enable==1 && paymentMethod.option"
                                           :class="{'selectedPaymentMethod': paymentMethodClicked==paymentMethod.title}"
                                           @click.prevent="paymentMethodClicked = paymentMethod.title,isPaymentMethodDefault=true,selectedMethodId=paymentMethod.id,paymentMethodType=paymentMethod.type,pay()">
                                            {{paymentMethod.title}}
                                        </a>
                                        <a href="#"
                                           class="btn payment-methods-btn"
                                           v-else-if="paymentMethod.type!='stripe' && paymentMethod.type!='paypal' && paymentMethod.available_to_client==1 && paymentMethod.enable==1"
                                           :class="{'selectedPaymentMethod': paymentMethodClicked==paymentMethod.title}"
                                           @click.prevent="paymentMethodClicked = paymentMethod.title,isPaymentMethodDefault=false,selectedMethodId=paymentMethod.id,paymentMethodType=paymentMethod.type">
                                            {{paymentMethod.title}}
                                        </a>
                                    </div>
                                    <div class="row justify-content-center" v-if="!checkPaymentMethod(paymentMethods)">
                                        <div class="col-6 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                                            <div class="user-info-button">
                                                <button v-if="isPaymentMethodDefault"
                                                        class="btn btn-primary app-color mobile-btn btn-block"
                                                        id="default"
                                                        type="submit"
                                                        :disabled="!paymentMethodClicked"
                                                        @click.prevent="">
                                                    {{ trans('lang.book') }}
                                                </button>
                                                <button v-else
                                                        class="btn btn-primary app-color mobile-btn btn-block"
                                                        type="submit"
                                                        :disabled="!paymentMethodClicked"
                                                        @click.prevent="book(0, selectedMethodId)">
                                                    {{ trans('lang.book') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" v-if="enablePaypal">
                                <div class="col-12">
                                    <div class="payment-methods-button-container">
                                        <div id="paypal-button-container"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--Authentication warning modal-->
        <div class="modal fade" id="authentication-warning-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content px-4 pb-4 pt-3 text-center">
                    <div class="my-2">
                        <i class="la la-exclamation-circle la-5x text-warning"/>
                    </div>
                    <h5>{{ trans('lang.you_cant_book') }}</h5>
                    <h6>{{ trans('lang.please_login_and_try_again') }}</h6>
                    <div class="my-4">
                        <button class="btn btn-landing" @click="signIn">
                            {{trans('lang.sign_in')}}
                        </button>
                        <button class="btn btn-landing" @click="register">
                            {{trans('lang.sign_up')}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import 'popper.js';
    import axiosGetPost from '../../helper/axiosGetPostCommon';
    import FullCalendar from '@fullcalendar/vue';
    import dayGridPlugin from '@fullcalendar/daygrid';
    import interactionPlugin from "@fullcalendar/interaction";
    import allLocales from '@fullcalendar/core/locales-all';
    import {VueTelInput} from 'vue-tel-input';
    import {
        setServiceIdToCookie,
        getServiceIdFromCookie,
        deleteServiceIdFromCookie
    } from './helper/SalonLandingCookieSet.js';

    export default {
        extends: axiosGetPost,
        props: ['landing_page_header', 'landing_page_message', 'payment_methods', 'user', 'checksignup', 'service'],
        components: {
            FullCalendar,
            VueTelInput
        },
        data() {
            return {
                locales: allLocales,
                locale: this.trans('lang.calender_language_set'),

                modalID: '#success-modal',
                isActiveFields: false,
                customFields: [],
                customFieldDateId: [],
                customFieldsOptions: {},
                newCustomFieldData: {},
                serviceListData: [],
                serviceTiming: [],
                hidePreLoader: true,
                hideCalenderLoader: true,
                continuePreLoader: false,
                timePreLoader: true,
                submitPreLoader: true,
                service_id: '',
                isContinue: false,
                isCalendar: false,
                isTime: false,
                isCalendarSet: false,
                isBookForm: false,
                bookCalendar: false,
                calendar_inner: true,
                successBook: false,
                selectService: {},
                calendarPlugins: [dayGridPlugin, interactionPlugin],
                first_name: '',
                last_name: '',
                full_name: '',
                email: '',
                phone_number: '',
                startDay: '',
                selectTime: [],
                employees: [],
                employee_id: '',
                noOfAdult: 1,
                noOfChildren: 0,
                seat: 1,
                availableSeat: '',
                availableSeatsForTiming: [],
                ageRange: '',
                discount: '',
                netPrice: '',
                enableDates: [],
                disableDates: [],
                calendarDates: [],
                isFullCalendar: false,
                holidays: [],
                offDays: [],
                disable: false,

                // Payment Variables
                selectedMethodId: 0,
                enablePaypal: false,
                paymentMethodType: '',
                paymentMethodClicked: false,
                isPaymentMethodDefault: false,
                slot: [],
                paymentMethods: [],
                paypalError: true,
                singleService: {},
                isEmailOrPhoneProvided: false,
                phone_object: {},
                cookieData: null,
                isResetDateTimeClick: false,
            }
        },
        created() {
            this.getServiceData('/getAllServiceFormData');
            this.languageCacheClearForCalender();
        },
        mounted() {
            let instance = this;

            if (instance.user) {
                instance.first_name = instance.user.first_name;
                instance.last_name = instance.user.last_name;
                instance.full_name = instance.user.first_name + " " + instance.user.last_name;

                instance.email = instance.user.email;
                instance.phone_number = instance.user.phone;
            }
            instance.modalCloseAction(instance.modalID);

            $('#success-modal').on('hidden.bs.modal', function (e) {
                instance.resetBook();
            });

            $('#payment-modal').on('hidden.bs.modal', function (e) {
                instance.selectedMethodId = 0;
                instance.enablePaypal = false;
                instance.paymentMethodClicked = false;
                $('.paypal-buttons').css('display', 'none');
            });

            $(window).resize(function () {
                setTimeout(function () {
                    instance.setDropdownWidth();
                    instance.setTimeSlotPadding();
                    instance.setDropdownGuestWidth();
                    instance.setServiceImageWrapperHeight();
                });
            });

            /* If user want alias service link to be auto selected */
            //if(instance.service) instance.serviceBook(instance.service);
            this.cookieData = getServiceIdFromCookie(this.appVersion);
            if (this.cookieData) {
                this.setCalenderValueFromCookie();
            }

        },
        watch: {
            service_id() {
                let instance = this;
                instance.isTime = false;
                instance.bookCalendar = false;
                instance.isCalendarSet = false;
                instance.isFullCalendar = false;
                instance.startDay = '';

                instance.reSetBookingForm();
                instance.getService();
                instance.readSupportingData();

                setTimeout(function () {
                    instance.controlPreAndNextBtn();
                    $('.tooltip-guest-adult').tooltip('dispose').tooltip('hide');
                    $('.tooltip-guest-child').tooltip('dispose').tooltip('hide');
                });
            },
            isBookForm() {
                let instance = this;
                setTimeout(function () {
                    instance.setTimeSlotPadding();
                    instance.setServiceImageWrapperHeight();
                });
            },
            employees() {
                let instance = this;
                setTimeout(function () {
                    instance.setDropdownWidth();
                    instance.setDropdownGuestWidth();
                });
            },
            payment_methods: function (value) {
                this.paymentMethods = value;
                if (value) {
                    this.checkPaymentTypeStripe();
                }
            }
        },
        methods: {
            isEmpty(obj) {
                return _.isEmpty(obj) ? true : false;
            },
            getCountry(number, isValid, country) {
                this.phoneObject = isValid.number.significant;
                this.setServiceIdCookie();
            },
            reSetBookingForm() {
                if (this.user == null) {
                    this.isBookForm = false;
                    this.first_name = '';
                    this.last_name = '';
                    this.full_name = '';
                    this.email = '';
                    this.phone_number = '';
                }
            },
            setCalenderValueFromCookie() {
                this.service_id = this.cookieData.serviceId;
                this.isFullCalendar = true;
            },

            setLandingPageFromCookie() {
                if (this.cookieData.serviceId == this.service_id) {

                    this.startDay = this.cookieData.date;
                    this.serviceTiming = this.cookieData.serviceTiming;
                    this.availableSeatsForTiming = this.cookieData.availableSeatsForTiming;
                    this.selectTime = this.cookieData.selectTime;

                    if (this.user) {
                        this.full_name = this.user.full_name;
                    }else if (this.cookieData.full_name) {
                        this.full_name = this.cookieData.full_name;
                    } else {
                        this.full_name = '';
                    }

                    if (this.user) {
                        this.email = this.user.email;
                    } else if (this.cookieData.email) {
                        this.email = this.cookieData.email;
                    } else {
                        this.email = '';
                    }

                    if (this.user) {
                        this.phone_number = this.user.phone ? this.user.phone : '';
                    } else if (this.cookieData.phone_number) {
                        this.phone_number = this.cookieData.phone_number;
                    } else {
                        this.phone_number = '';
                    }

                    if (this.selectTime.length) this.isCalendarSet = true;
                    if (this.cookieData.noOfAdult) this.noOfAdult = this.cookieData.noOfAdult;
                    if (this.cookieData.noOfChildren) this.noOfChildren = this.cookieData.noOfChildren;
                    if (this.serviceTiming.length > 0) this.isTime = true;

                    if (this.cookieData.isBookForm) this.bookingForm();
                }
            },

            controlPreAndNextBtn() {
                let instance = this;
                $(".fc-prev-button").on("click", function () {
                    instance.enableDisableDates();
                });
                $(".fc-next-button").on("click", function () {
                    instance.enableDisableDates();
                });
            },
            enableDisableDates(isTimeExecute = true) {
                let instance = this,
                    fcDayTop = $(".fc-day-top"),
                    today = new Date(),
                    currentMonth = today.getMonth() + 1,
                    lastDayOfMonth = new Date(today.getFullYear(), currentMonth, 0);
                if (isTimeExecute) {
                    instance.isTime = false;
                }
                instance.calendarDates = [];
                instance.enableDates = [];
                instance.disableDates = [];
                instance.checkSlotsOfToday();

                if (this.cookieData && !this.isResetDateTimeClick) {
                    this.setLandingPageFromCookie();
                }
                fcDayTop.each(function () {
                    let value = $(this).data("date");
                    instance.calendarDates.push(value);

                    if (instance.cookieData && !instance.isResetDateTimeClick) {

                        if (instance.cookieData.serviceId == instance.service_id) {

                            if (instance.startDay == value) {
                                $(this).addClass("active-date");
                            }
                        }

                    }
                });

                if (
                    !_.isEmpty(instance.selectService.service_starting_date) &&
                    !_.isEmpty(instance.selectService.service_ending_date)
                ) {
                    let start = instance.selectService.service_starting_date,
                        end = instance.selectService.service_ending_date;
                    instance.enableDates = instance.getDates(start, end);
                } else {
                    today = moment(today).format("YYYY-MM-DD");
                    lastDayOfMonth = moment(lastDayOfMonth).format("YYYY-MM-DD");
                    instance.calendarDates.forEach(function (item) {
                        if (moment(item).format("YYYY-MM-DD") >= today) {
                            instance.enableDates.push(item);
                        }
                    })
                }
                instance.disableDates = instance.calendarDates.filter(
                    ele => !instance.enableDates.includes(ele)
                );
                let itemHolidays = [];
                if (instance.holidays) {
                    for (let item of instance.holidays) {
                        itemHolidays = instance.getDates(item.start_date, item.end_date);
                        instance.disableDates = instance.disableDates.concat(itemHolidays);
                        itemHolidays = [];
                    }
                }
                let offDayInText = [];
                if (instance.offDays) {
                    for (let item of instance.offDays) {
                        if (item == 1) {
                            offDayInText.push('fc-sun');
                        } else if (item == 2) {
                            offDayInText.push('fc-mon');
                        } else if (item == 3) {
                            offDayInText.push('fc-tue');
                        } else if (item == 4) {
                            offDayInText.push('fc-wed');
                        } else if (item == 5) {
                            offDayInText.push('fc-thu');
                        } else if (item == 6) {
                            offDayInText.push('fc-fri');
                        } else if (item == 7) {
                            offDayInText.push('fc-sat');
                        }
                    }
                }
                setTimeout(function () {
                    fcDayTop.each(function () {
                        let ele = $(this).data("date");
                        $(this).remove("fc-other-month");
                        if (instance.disableDates.includes(ele)) {
                            $(this).addClass("fc-other-month");
                        }
                        if (offDayInText.length > 0) {
                            for (let item of offDayInText) {
                                if ($(this).hasClass(item)) {
                                    $(this).remove("fc-other-month");
                                    instance.disableDates.push($(this).data("date"));
                                    $(this).addClass("fc-other-month");
                                }
                            }
                        }
                    });
                }, 500);
            },
            getDates(currentDate, stopDate) {
                let dateArray = [];
                currentDate = moment(currentDate);
                stopDate = moment(stopDate);
                while (currentDate <= stopDate) {
                    dateArray.push(moment(currentDate).format("YYYY-MM-DD"));
                    currentDate = moment(currentDate).add(1, "days");
                }
                return dateArray;
            },

            checkSlotsOfToday() {
                let instance = this,
                    today = new Date();
                today = moment(today).format("YYYY-MM-DD");

                instance.axiosGet('/servicetiming/' + this.service_id + '/' + today,
                    function (response) {
                        if (response.data.stack.length < 1) {
                            instance.disableDates.push(today);
                        }
                    }
                );
            },
            setDropdownWidth() {
                let parentWidth = $('.sp-input-container').width();
                $('.sp-result-area').css('width', parentWidth + 'px');
            },
            setDropdownGuestWidth() {
                let parentWidth = $('.guest-selection').width();
                $('.dropdown-guest').css('min-width', parentWidth + 'px');
            },
            setTimeSlotPadding() {
                let parentHeight = $('.fc-header-toolbar').height(),
                    calendarHeight = $('.fc-view-container').height();
                $('.time-slot-container').css({
                        'margin-top': parentHeight + 15 + 'px',
                        'max-height': calendarHeight + 'px'
                    }
                );
            },
            setServiceImageWrapperHeight() {
                let parentHeight = $('.service-information-wrapper').height();
                $('.service-image-wrapper').css('height', parentHeight + 'px');
            },
            getService() {
                let instance = this;
                if (instance.service_id) {
                    instance.continuePreLoader = true;
                    instance.hideCalenderLoader = false;
                    instance.axiosGet('/service-by-id/' + instance.service_id,
                        function (response) {
                            instance.hideCalenderLoader = true;
                            instance.isFullCalendar = true;
                            instance.selectService = response.data;
                            instance.selectTime = [];
                            instance.serviceTiming = [];
                            instance.availableSeat = instance.selectService.available_seat - 1;
                            instance.netPrice = instance.selectService.price;
                            if (instance.selectService.consider_children_for_price != 'Yes') {
                                instance.noOfChildren = 0;
                                instance.noOfAdult = 1;
                                instance.child = 'Child';
                            }
                            instance.noOfAdult = 1;
                            instance.ageRange = instance.selectService.age_range;
                            instance.discount = instance.selectService.percentage;
                            let service_employee = response.data.service_employee;

                            if (!_.isEmpty(response.data.service_employee)) {
                                response.data.service_employee.forEach(s_employee => {
                                    instance.employees.push(s_employee.user);
                                });
                            } else {
                                instance.employees = [];
                            }
                            instance.continuePreLoader = false;

                            setTimeout(function () {
                                instance.enableDisableDates();
                                instance.controlPreAndNextBtn();
                            });
                            instance.setServiceIdCookie();
                        },
                        function (error) {
                            instance.continuePreLoader = false;
                        });
                }
            },
            getTiming() {
                let instance = this;
                instance.timePreLoader = false;
                instance.axiosGet('/servicetiming/' + instance.service_id + '/' + instance.startDay,
                    function (response) {
                        instance.serviceTiming = response.data.stack;
                        instance.availableSeatsForTiming = response.data.seat;
                        instance.timePreLoader = true;

                        setTimeout(function () {
                            instance.setTimeSlotPadding();
                        });
                        instance.setServiceIdCookie();
                    },
                    function (error) {
                        instance.timePreLoader = true;
                    });

            },
            reSetDateTime() {
                let instance = this;
                this.isResetDateTimeClick = true;
                this.isTime = true;
                this.isContinue = false;
                this.isBookForm = false;
                this.isCalendarSet = true;
                setTimeout(function () {
                    instance.setActiveClass();
                    instance.enableDisableDates(false);
                    instance.controlPreAndNextBtn();
                });
                $('.tooltip-change-date').tooltip('dispose').tooltip('hide');
                this.setServiceIdCookie();
            },
            bookingForm() {
                let instance = this;
                this.isBookForm = true;
                this.isTime = false;
                this.isContinue = false;
                this.isCalendarSet = false;
                setTimeout(function () {
                    $('.tooltip-change-date').tooltip({
                            title: instance.trans('lang.click_here_to_change_date'),
                            placement: 'right',
                            trigger: 'hover'
                        }
                    );
                }, 100);
                this.countNetPrice();
                this.setServiceIdCookie();
            },
            serviceBook(service) {
                let instance = this;
                $("html, body").animate(
                    {
                        scrollTop: $("#home").offset().top
                    }, 600,
                    function () {
                        window.location.hash = "";
                    }
                );

                instance.service_id = service.id.toString();
                instance.selectService = service;
            },
            handleDateClick(arg) {
                let instance = this;
                if (instance.checksignup.submit_booking_after_login === 1 && instance.user == null) {
                    $('#authentication-warning-modal').modal('show');
                } else {
                    if (!this.disableDates.includes(arg.dateStr)) {
                        instance.startDay = arg.dateStr;
                        instance.selectTime = [];
                        instance.isCalendarSet = false;
                        instance.isTime = true;
                        instance.bookCalendar = true;
                        instance.calendar_inner = false;
                        instance.getTiming();
                    } else {
                        $(".fc-highlight").css("opacity", 0);
                    }
                }
                instance.setActiveClass();
                instance.setServiceIdCookie();
            },
            handleDateTime(time, index) {
                let instance = this,
                    bookedSeats = instance.noOfAdult + instance.noOfChildren;
                if (bookedSeats <= instance.availableSeatsForTiming[index]) {
                    if (instance.selectTime.includes(time)) {
                        let selectedTimeIndex = instance.selectTime.indexOf(time);
                        instance.selectTime.splice(selectedTimeIndex, 1);
                    } else {
                        if (instance.selectService.multiple_bookings == 0) {
                            instance.selectTime = [];
                            instance.selectTime.push(time);
                            instance.disable = false;
                        } else {
                            instance.selectTime.push(time);
                            instance.disable = false;
                        }
                    }
                }
                instance.isCalendarManipulate();
                instance.setServiceIdCookie();
            },
            isCalendarManipulate() {
                let instance = this;
                if (instance.selectTime.length < 1) {
                    instance.isCalendarSet = false;
                } else {
                    instance.isCalendarSet = true;
                }
            },
            setActiveClass() {
                let instance = this,
                    fcDayTop = $(".fc-day-top");
                fcDayTop.each(function () {
                    let item = $(this).data("date");
                    $(this).removeClass("active-date");
                    if (instance.startDay == item) {
                        $(this).addClass("active-date");
                    }
                });
            },
            getServiceData(route) {
                let instance = this;
                instance.hidePreLoader = false;
                instance.axiosGet(route,
                    function (response) {
                        if (instance.service == false) {
                            //for all service list
                            instance.serviceListData = response.data.services;

                            if (instance.serviceListData.length == 1) {
                                //for single service list
                                instance.singleService = instance.serviceListData[0];
                            }

                        } else {

                            //alias service
                            if (instance.service) {
                                instance.serviceListData.push(instance.service);
                                instance.singleService = instance.serviceListData[0];
                            }
                        }

                        instance.holidays = response.data.holidays;
                        instance.offDays = response.data.offDays;
                        instance.hidePreLoader = true;
                        instance.$emit('serviceLength', instance.serviceListData.length);
                        setTimeout(function () {

                            instance.setDropdownWidth();
                            instance.setDropdownGuestWidth();
                            $('.guest-selection').on('hide.bs.dropdown', function () {
                                $('#dropdownMenuButton').css('color', '#333');
                            });

                            /*Remove first new line from banner subtitle*/
                            let preElements = document.querySelectorAll('.banner-subtitle');
                            Array.prototype.forEach.call(preElements, function (pre) {
                                pre.textContent = pre.textContent.replace(/^\s+/, '');
                            });
                        });
                    },
                    function (error) {
                        instance.hidePreLoader = true;
                    },
                );
            },
            resetBook() {
                this.service_id = '';
                this.noOfAdult = 1;
                this.noOfChildren = 0;
                this.employees = [];

                this.cookieData = null;
                deleteServiceIdFromCookie(this.appVersion);

                this.getServiceData('/getAllServiceFormData');
            },
            cancelBook() {
                this.isBookForm = false;
                this.isContinue = false;
                this.isCalendar = false;
                this.isTime = false;
                this.isCalendarSet = false;
                this.bookCalendar = false;
                this.continuePreLoader = false;
                this.calendar_inner = true;
                this.service_id = '';
            },
            plusMinusButtonAction(value, type) {
                let instance = this;
                instance.seat = instance.noOfAdult + instance.noOfChildren;

                if (type == 'adult') {
                    if (value == '+') {
                        if (0 < instance.availableSeat) {
                            instance.noOfAdult++;
                            --instance.availableSeat;
                        } else {
                            if (instance.selectService.multiple_bookings == 0) {
                                setTimeout(function () {
                                    $('.tooltip-guest-adult').tooltip('dispose').tooltip(
                                        {
                                            title: instance.trans('lang.tooltip_guest_selection_alert_without_multi_booking')
                                        }
                                    ).tooltip('show');
                                }, 100);
                            } else {
                                setTimeout(function () {
                                    $('.tooltip-guest-adult').tooltip('dispose').tooltip(
                                        {
                                            title: instance.trans('lang.tooltip_guest_selection_alert')
                                        }
                                    ).tooltip('show');
                                }, 100);
                            }
                        }
                    } else {
                        if (instance.selectService.consider_children_for_price == 'Yes') {
                            if (instance.seat > 1 && instance.noOfAdult > 0) {
                                --instance.noOfAdult;
                                instance.availableSeat++;
                            }

                        } else {
                            if (instance.seat > 1 && instance.noOfAdult > 1) {
                                --instance.noOfAdult;
                                instance.availableSeat++;
                            }
                        }
                        setTimeout(function () {
                            $('.tooltip-guest-adult').tooltip('dispose').tooltip('hide');
                            $('.tooltip-guest-child').tooltip('dispose').tooltip('hide');
                        }, 100);
                    }
                } else {
                    if (value == '+') {
                        if (0 < instance.availableSeat) {
                            instance.noOfChildren++;
                            --instance.availableSeat;
                        } else {
                            if (instance.selectService.multiple_bookings == 0) {
                                setTimeout(function () {
                                    $('.tooltip-guest-child').tooltip('dispose').tooltip(
                                        {
                                            title: instance.trans('lang.tooltip_guest_selection_alert_without_multi_booking')
                                        }
                                    ).tooltip('show');
                                }, 100);
                            } else {
                                setTimeout(function () {
                                    $('.tooltip-guest-child').tooltip('dispose').tooltip(
                                        {
                                            title: instance.trans('lang.tooltip_guest_selection_alert')
                                        }
                                    ).tooltip('show');
                                }, 100);
                            }
                        }
                    } else {
                        if (instance.seat > 1 && instance.noOfChildren > 0) {
                            --instance.noOfChildren;
                            instance.availableSeat++;
                        }
                        setTimeout(function () {
                            $('.tooltip-guest-adult').tooltip('dispose').tooltip('hide');
                            $('.tooltip-guest-child').tooltip('dispose').tooltip('hide');
                        }, 100);
                    }
                }


                if (instance.noOfAdult > 1) {
                    instance.adult = 'Adults';
                } else instance.adult = 'Adult';

                if (instance.noOfChildren > 1) {
                    instance.child = "Children";
                } else instance.child = "Child";

                instance.seat = instance.noOfAdult + instance.noOfChildren;
                // instance.countNetPrice();
                instance.removeSelectedTimeIsDisabled();
                instance.setServiceIdCookie();
            },
            removeSelectedTimeIsDisabled() {
                let instance = this,
                    bookedSeats = instance.noOfAdult + instance.noOfChildren;
                this.serviceTiming.forEach(function (item, index, itemArr) {
                    if (bookedSeats > instance.availableSeatsForTiming[index]) {
                        // itemArr.splice(index, 1);
                        let selectTimeIndex = instance.selectTime.findIndex(function (element) {
                            return element == item;
                        });
                        if (selectTimeIndex >= 0) {
                            instance.selectTime.splice(selectTimeIndex, 1);
                            if (instance.selectTime.length < 1) {
                                instance.countNetPrice();
                                instance.disable = true;
                            }
                        }
                    }
                });
                instance.countNetPrice();
                setTimeout(function () {
                    instance.isCalendarManipulate();
                })
            },
            countNetPrice() {
                let instance = this;
                let childPrice = instance.selectService.price - ((instance.selectService.price * instance.discount) / 100);
                let totalChildPrice = instance.noOfChildren * childPrice;
                let totalAdultPrice = instance.selectService.price * instance.noOfAdult;
                instance.netPrice = (totalChildPrice + totalAdultPrice) * instance.selectTime.length;
            },
            splitFullName(fullName) {
                let instance = this;
                if (fullName.indexOf(' ') >= 0) {
                    let tempName = fullName.split(" ");

                    instance.first_name = "";

                    tempName.forEach((element, index) => {
                        if (index != tempName.length - 1) {
                            instance.first_name = instance.first_name + ' ' + element;
                        }
                    });

                    instance.last_name = tempName[tempName.length - 1];
                } else {
                    instance.first_name = fullName;
                    instance.last_name = '';

                }

            },

            resetCustomEmailPhoneValidation() {
                let instance = this;
                instance.isEmailOrPhoneProvided = false;
            },
            save() {
                let instance = this;
                instance.$validator.validateAll().then((result) => {
                    instance.eitherEmailOrPhoneValidation();
                    if (result && instance.eitherEmailOrPhoneValidation()) {

                        //split full name into first name and last name
                        instance.splitFullName(instance.full_name);

                        instance.inputFields = {
                            first_name: instance.first_name,
                            last_name: instance.last_name,
                            email: instance.email,
                            phone_number: instance.phone_number,
                            phone_object: instance.phoneObject,
                            booking_date: instance.startDay,
                            title: instance.selectService.title,
                            price: instance.selectService.price,
                            bill: instance.selectService.price,
                            slot: instance.selectTime,
                            seat: instance.seat,
                            adult: instance.noOfAdult,
                            children: instance.noOfChildren,
                            id: instance.service_id,
                            service_list: instance.service_id,
                            duration_type: instance.selectService.service_duration_type,
                            customFields: JSON.stringify(instance.customFieldsOptions)
                        };
                        instance.submitPreLoader = false;
                        instance.axiosGETorPOST(
                            {
                                url: '/bookservice',
                                postData: instance.inputFields
                            },
                            (success, responseData) => {
                                instance.submitPreLoader = true;
                                instance.isBookForm = false;
                                instance.isContinue = false;
                                instance.isCalendar = false;
                                instance.isTime = false;
                                instance.isCalendarSet = false;
                                instance.bookCalendar = false;
                                instance.continuePreLoader = false;
                                instance.calendar_inner = true;
                                instance.newCustomFieldData = {},
                                    $('#success-modal').modal('show');
                            },
                            (error) => {
                                instance.errors = error.data.errors;
                                instance.submitPreLoader = true;
                            }
                        );
                    }
                });
            },
            checkConfirm() {
                let instance = this;

                if (!instance.checkPaymentMethod(instance.paymentMethods)) {
                    instance.$validator.validateAll().then((result) => {
                        if (result) {
                            $('#payment-modal').modal('show');
                        }
                    });
                } else {
                    instance.save();
                }
            },
            eitherEmailOrPhoneValidation() {
                if ((this.email == '' && this.phone_number == "")) {

                    //show error
                    this.isEmailOrPhoneProvided = true;
                    return false;

                } else if (this.email != '' && !this.email.includes("@")) {

                    this.isEmailOrPhoneProvided = true;
                    return false;
                }
                else {
                    return true;
                }
            },
            signIn() {
                this.redirect('/login');
            },
            register() {
                this.redirect('/register');
            },
            // Custom field
            readSupportingData() {
                let instance = this;
                instance.axiosGet('/client-custom-field',
                    function (response) {
                        instance.customFields = response.data.filter(function (element) {
                            if (element.service_id == null || element.service_id == instance.service_id) return element;
                        });
                        instance.isActiveFields = true;
                    },
                    function (error) {
                    }
                );
            },

            fieldOptions(options, newDateId) {
                this.customFieldsOptions = options;
                this.newServiceDate = newDateId;
            },

            // Payment Methods
            paypal() {
                let instance = this;
                setTimeout(function () {
                    paypal.Buttons({
                        createOrder: function (data, actions) {
                            instance.paypalError = true;
                            return actions.order.create({
                                purchase_units: [{
                                    amount: {
                                        value: instance.netPrice,
                                        quantity: instance.seat,
                                    }
                                }]
                            });
                        },
                        onApprove: function (data, actions) {
                            return actions.order.capture().then(function (details) {
                                instance.setBookingLoader(false);
                                instance.hidePreLoader = false;
                                instance.enablePaypal = false;
                                instance.axiosPost('/paypal-transaction-complete', {orderID: data.orderID},
                                    function (response) {
                                        if (response.data == 'success') {
                                            instance.book(instance.netPrice, instance.selectedMethodId, data.orderID);
                                        } else {
                                            instance.bookingProcessingFailed = true;
                                            instance.bookingProcessing = false;
                                            instance.changeConfirm(1);
                                            instance.is_disabled = false;
                                        }
                                    }, function (error) {
                                        instance.bookingProcessingFailed = true;
                                        instance.bookingProcessing = false;
                                        instance.changeConfirm(1);
                                        instance.is_disabled = false;

                                    });

                            });
                        },
                        onError: function (err) {
                            if (instance.paypalError) instance.paypalError = false;

                        },
                        style: {
                            color: 'blue',
                            size: 'responsive',
                            fundingicons: false,
                            tagline: false,
                        },

                    }).render('#paypal-button-container');
                });
            },
            checkPaymentMethod(paymentMethods) {
                if (_.differenceWith(paymentMethods, [{
                    'available_to_client': 1,
                    'enable': 1
                }], _.isEqual).length == 0) {
                    return true;
                } else {
                    return false;
                }
            },
            checkPaymentTypeStripe() {
                let instance = this;
                this.paymentMethods.forEach(function (paymentMethod) {
                    if (paymentMethod.type == 'stripe') {
                        if (paymentMethod.available_to_client == 1 && paymentMethod.enable == 1) {
                            instance.paymentForm();

                        }
                    }
                })
            },
            paymentForm() {
                let instance = this;
                let publishable_key = '';
                instance.paymentMethods.forEach(function (element) {
                    if (element.type == 'stripe') {
                        publishable_key = element.option;
                    }
                });
                if (publishable_key) {
                    this.handler = StripeCheckout.configure({
                        key: publishable_key,
                        locale: 'auto',
                        image: instance.publicPath + '/images/stripe-logo.png',
                        allowRememberMe: false,
                        token: function (token) {
                            // You can access the token ID with `token.id`.
                            // Get the token ID to your server-side code for use.
                            instance.hidePreLoader = false;

                            instance.axiosPost('/paymentstripe', {
                                    email: instance.email,
                                    token: token.id,
                                    bill: instance.netPrice,
                                    method_id: instance.selectedMethodId,
                                },
                                function (response) {
                                    instance.book(instance.netPrice, instance.selectedMethodId);
                                },
                                function (error) {
                                    instance.hidePreLoader = true;
                                });
                        },
                    });
                }
            },
            pay() {
                let instance = this;
                this.$nextTick(function () {
                    document.getElementById('default').addEventListener('click', function (e) {
                        if (instance.paymentMethodType == 'paypal') {
                            instance.enablePaypal = true;
                            instance.paypal();
                        } else {
                            instance.handler.open({
                                name: instance.appName,
                                description: 'Bill: ' + instance.currencyCode + ' ' + instance.netPrice,
                                email: instance.email,
                                closed: function () {
                                }
                            });
                        }
                        e.preventDefault();
                    });
                })
            },
            book(bill, method, transaction_id = null) {
                let instance = this;
                if (instance.noOfAdult == 0 && instance.noOfChildren == 0) {
                    instance.seat = 1;
                } else {
                    instance.seat = (instance.noOfAdult + instance.noOfChildren);
                }
                instance.$validator.validateAll().then((result) => {
                    if (result) {

                        //split full name into first name and last name
                        instance.splitFullName(instance.full_name);

                        instance.inputFields = {
                            first_name: instance.first_name,
                            last_name: instance.last_name,
                            email: instance.email,
                            phone_number: instance.phone_number,
                            phone_object: instance.phoneObject,
                            booking_date: instance.startDay,
                            title: instance.selectService.title,
                            price: instance.selectService.price,
                            bill: bill,
                            method_id: method,
                            transaction_id: transaction_id,
                            slot: instance.selectTime,
                            seat: instance.seat,
                            adult: instance.noOfAdult,
                            children: instance.noOfChildren,
                            id: instance.service_id,
                            service_list: instance.service_id,
                            duration_type: instance.selectService.service_duration_type,
                            customFields: JSON.stringify(instance.customFieldsOptions)
                        };
                        instance.submitPreLoader = false;
                        instance.hidePreLoader = false;
                        instance.axiosGETorPOST(
                            {
                                url: '/bookservice',
                                postData: instance.inputFields
                            },
                            (success, responseData) => {
                                instance.submitPreLoader = true;
                                instance.hidePreLoader = true;
                                instance.isBookForm = false;
                                instance.isContinue = false;
                                instance.isCalendar = false;
                                instance.isTime = false;
                                instance.isCalendarSet = false;
                                instance.bookCalendar = false;
                                instance.continuePreLoader = false;
                                instance.calendar_inner = true;
                                instance.newCustomFieldData = {};
                                $('#payment-modal').modal('hide');
                                $('#success-modal').modal('show');
                            },
                            (error) => {
                                instance.errors = error.data.errors;
                                instance.submitPreLoader = true;
                                instance.hidePreLoader = true;
                            }
                        );
                    }
                });
            },

            setServiceIdCookie() {
                let cookieObject = {
                    serviceId: this.service_id,
                    date: this.startDay,
                    noOfAdult: this.noOfAdult,
                    noOfChildren: this.noOfChildren,
                    serviceTiming: this.serviceTiming,
                    availableSeatsForTiming: this.availableSeatsForTiming,
                    selectTime: this.selectTime,
                    full_name: this.full_name,
                    phone_number: this.phone_number,
                    email: this.email,
                    isContinue: this.isContinue,
                    isBookForm: this.isBookForm,
                    isTime: this.isTime,
                    isCalendarSet: this.isCalendarSet,
                    appVersion: this.appVersion,
                };
                setServiceIdToCookie(cookieObject);
            }
        },
    }
</script>
