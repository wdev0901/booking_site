require('./bootstrap');
require('./jquery-3.5.0.min');
require('./hammer.min');

const Vue = require('vue');
const _ = require('lodash');
window.moment = require('moment');
window.VeeValidate = require('vee-validate');
window.$cookies = require('vue-cookies');

import Vuelidate from 'vuelidate';

Vue.use(VeeValidate);
Vue.use(Vuelidate);

//Vue Toasted
import Toasted from 'vue-toasted';

Vue.use(Toasted);

//For success messages
let success = {
    theme: "toasted-primary",
    position: "top-right",
    duration: 4000,
};

//Register the toast with the custom message
Vue.toasted.register('success',
    (payload) => {
        // if there is a message show it with the message
        return payload.message;
    },
    success
);

//Error messages example
let errors = {
    type: 'error',
    theme: "toasted-primary",
    position: "top-right",
    duration: 4000
};

//Register the toast with the custom message
Vue.toasted.register('error',
    (payload) => {
        // if there is no message passed show default message
        if (!payload.message) {
            return "Something Went Wrong.."
        }

        // if there is a message show it with the message
        return payload.message;

    },
    errors
);

// for warning message example
let offlineToast = {
    theme: "toasted-primary",
    position: "top-right",
    className: "offline-toast-padding",
    action: {
        text: 'X',
        onClick: (e, toastObject) => {
            toastObject.goAway(0);
        }
    },
};

// register the toast with the custom message
Vue.toasted.register('offlineToast',
    (payload) => {
        // if there is a message show it with the message
        return payload.message;
    },
    offlineToast
);

//vue-clipboard2
import VueClipboard from 'vue-clipboard2';

Vue.use(VueClipboard);

// Vue Time Picker
import VueTimepicker from 'vue2-timepicker';

// Vue SelectPage
import vSelectPage from 'v-selectpage';
Vue.use(vSelectPage, {
    language: 'en',
});

Vue.component('vue-timepicker', VueTimepicker);

import { VueTelInput } from 'vue-tel-input';

//date picker
import Datepicker from 'vuejs-datepicker';

Vue.component('datepicker', Datepicker);

// an EventHub to share events between components
Vue.prototype.$hub = new Vue();

//basic app data
Vue.prototype.publicPath = window.appConfig.publicPath;
Vue.prototype.appUrl = window.appConfig.appUrl;
Vue.prototype.dateTimeFormat = window.appConfig.dateTimeFormat;
Vue.prototype.dateFormat = window.appConfig.dateFormat;
Vue.prototype.rowLimit = window.appConfig.knowDefaultRowSettings;
Vue.prototype.currentUserId = window.appConfig.currentUserId;
Vue.prototype.currencySymbol = window.appConfig.currencySymbol;
Vue.prototype.currencyFormat = window.appConfig.currencyFormat;
Vue.prototype.thousandSeparator = window.appConfig.thousandSeparator;
Vue.prototype.decimalSeparator = window.appConfig.decimalSeparator;
Vue.prototype.numberOfDecimal = window.appConfig.numberOfDecimal;
Vue.prototype.appLogo = window.appConfig.appLogo;
Vue.prototype.appName = window.appConfig.appName;
Vue.prototype.currencyCode = window.appConfig.currencyCode;
Vue.prototype.timeFormat = window.appConfig.timeFormat;
Vue.prototype.businessType = window.appConfig.businessType;
Vue.prototype.appVersion = window.appConfig.appVersion;

// Define a function to use existing laravel language
Vue.prototype.trans = (string, args) => {
    let value = _.get(window.i18n, string);

    _.eachRight(args, (paramVal, paramKey) => {
        value = _.replace(value, `:${paramKey}`, paramVal);
    });
    return value;
};

Vue.component('example-component', require('./components/ExampleComponent.vue'));

//Calendar
Vue.component('choose-demo-component', require('./components/salonLanding/DemoChoose'));
Vue.component('calendar', require('./components/calendar/calendar.vue'));
Vue.component('calendar-seven-days', require('./components/salonLanding/GeneralIndex.vue'));

//Salon
Vue.component('salon_component', require('./components/salonLanding/SalonIndex.vue'));
Vue.component('service_booking_landing', require('./components/salonLanding/ServiceBooking.vue'));

//Login
Vue.component('login-form', require('./components/auth/Login.vue'));

//Register
Vue.component('register-form', require('./components/auth/Register.vue'));
Vue.component('sms-code-verification-modal', require('./components/auth/SmsCodeVerificationModal.vue'));

//Reset Password
Vue.component('email-reset', require('./components/auth/Email.vue'));
Vue.component('reset-password', require('./components/auth/Reset.vue'));

//Sidebar
Vue.component('side-bar', require('./components/include/Sidebar.vue'));

//Navbar
Vue.component('nav-bar', require('./components/include/Navbar.vue'));

//Profile
Vue.component('profile-index', require('./components/users/ProfileIndex'));
Vue.component('profile-form', require('./components/users/ProfileForm'));
Vue.component('change-password', require('./components/users/Account'));
Vue.component('google-calendar', require('./components/users/GoogleCalendar'));

//Dashboard
Vue.component('dashboard', require('./components/Dashboard/Dashboard.vue'));

//Services
Vue.component('service-index', require('./components/service/indexAll.vue'));
Vue.component('createservice-form', require('./components/service/ServiceAddEditModal.vue'));
Vue.component('service-setting', require('./components/service/ServiceSetting.vue'));
Vue.component('custom-setting-modal', require('./components/service/CustomSettingModal.vue'));

//Bookings
Vue.component('booking-index', require('./components/booking/indexAll.vue'));
Vue.component('bookservie-form', require('./components/booking/BookingForm.vue'));
Vue.component('adminbooking-form', require('./components/booking/AdminBooking.vue'));

//Settings
Vue.component('setting-index', require('./components/setting/Index.vue')); //Settings Index
Vue.component('application-setting', require('./components/setting/application/AppSetting.vue')); //Application
Vue.component('email-form', require('./components/setting/email/EmailSettings.vue')); //Email
Vue.component('email-template-list', require('./components/setting/emailTemplate/Index.vue')); //Email Template List
Vue.component('email-template-list-details', require('./components/setting/emailTemplate/EmailTemplateShow.vue')); //Email Template Details
Vue.component('offday-setting', require('./components/setting/offday/OffdaySetting.vue')); //Off day
Vue.component('holiday-index', require('./components/setting/holiday/Index.vue')); //Holiday Index
Vue.component('holiday-modal', require('./components/setting/holiday/HolidayAddEditModal.vue')); //Holiday Details
Vue.component('client-setting', require('./components/setting/client/ClientSetting.vue')); //Clients Index
Vue.component('roles', require('./components/setting/role/Index.vue')); //Roles index
Vue.component('role_add_edit_modal', require('./components/setting/role/RolesAddEditModal.vue')); //Roles Details
Vue.component('user-list', require('./components/setting/user/Index.vue')); //User List
Vue.component('invite_user', require('./components/setting/user/UserAddEditModal.vue')); //Clients Invite
Vue.component('user-list-role', require('./components/datatable/UserList.vue')); //User role check;
Vue.component('service-policy', require('./components/setting/service-policy/index.vue'));
Vue.component('custom-fields', require('./components/setting/customFields/index.vue'));
Vue.component('service-custom-field', require('./components/setting/customFields/serviceIndex.vue'));
Vue.component('custom_input_filed_service', require('./components/setting/customFields/customInputFiledService.vue'));
Vue.component('custom_input_filed_booking', require('./components/setting/customFields/customInputFiledooking.vue'));
Vue.component('booking-custom-field', require('./components/setting/customFields/bookingIndex.vue'));
Vue.component('social-link', require('./components/setting/social/socialLink.vue'));
Vue.component('break-time', require('./components/setting/breakTime/Index.vue'));
Vue.component('break-time-add-edit-modal', require('./components/setting/breakTime/AddEditModal.vue'));

//Contact Information
Vue.component('contact-information', require('./components/setting/contact-information/index'));

//Loaders
Vue.component('pre-loader', require('./components/preloader/preLoader.vue'));
Vue.component('bar-pre-loader', require('./components/preloader/barPreLoader.vue'));
Vue.component('circle-loader', require('./components/preloader/circleLoader.vue'));

//Filters
Vue.component('date-filter', require('./components/filter/dateFilter.vue'));
Vue.component('all-notification-view', require('./components/notification/AllNotification.vue'));

//Clients components
Vue.component('client-dashboard', require('./components/Dashboard/ClientDashboard.vue'));
Vue.component('client-sidebar', require('./components/include/ClientSidebar.vue'));
Vue.component('clients-index', require('./components/Clients/Index.vue'));
Vue.component('client-details', require('./components/Clients/ClientBookingHistory.vue'));
Vue.component('client_edit', require('./components/Clients/Edit.vue'));
Vue.component('client-navbar', require('./components/include/ClientNavbar.vue'));
Vue.component('client-booking-list', require('./components/Clients/ClientBookingList.vue'));

//Reports components
Vue.component('report-details', require('./components/reports/Reports.vue'));

// Datatable components
Vue.component('datatable-component', require('./components/datatable/Datatable.vue'));
Vue.component('service-action-component', require('./components/datatable/ServiceTableActions.vue'));
Vue.component('service-copy-component', require('./components/datatable/ServiceTableCopy.vue'));
Vue.component('booking-action-component', require('./components/datatable/BookingTableActions.vue'));
Vue.component('booking-invoice-component', require('./components/datatable/BookingTableInvoice.vue'));
Vue.component('userlist-action-component', require('./components/datatable/UserListActions.vue'));
Vue.component('client-action-component', require('./components/datatable/ClientActionComponent.vue'));
Vue.component('roles-action-component', require('./components/datatable/RolesActionComponent.vue'));
Vue.component('email-template-action-component', require('./components/datatable/EmailTemplateTableActions.vue'));
Vue.component('custom-field-action-component', require('./components/datatable/customFieldActions.vue'));
Vue.component('custom_user_action', require('./components/datatable/CustomUserAction.vue'));
Vue.component('disable_enable_user_action', require('./components/confirmation-modal/ConfirmationUserEnableDisable'));
Vue.component('load-more', require('./components/CommonComponents/loadMoreButton.vue'));
Vue.component('multi-select', require('./components/CommonComponents/Multiselect'));

// Charts
Vue.component('report-bar-chart', require('./components/charts/reportBarChart.vue'));
Vue.component('line-chart', require('./components/charts/lineChart.vue'));
Vue.component('doughnut-chart', require('./components/charts/doughnutChart.vue'));
Vue.component('booking-details', require('./components/booking/View.vue'));
Vue.component('user-details', require('./components/users/UserBookingHistory.vue'));

// LoginRegisterModal
Vue.component('login-register-notice', require('./components/auth/LoginRegisterModal.vue'));

// Payment Setting
Vue.component('payment-index', require('./components/setting/paymentMethod/Index.vue'));
Vue.component('payment-methods-add-edit-modal', require('./components/setting/paymentMethod/PaymentMethodsAddEditModal.vue'));
Vue.component('payment-action-component', require('./components/datatable/PaymentActions.vue'));
Vue.component('due-payment', require('./components/booking/duePayment.vue'));

// Custom Input Setting
Vue.component('custom-input', require('./components/custom-input/CustomInput.vue'));
Vue.component('custom-input-field', require('./components/custom-input/input.vue'));
Vue.component('custom-input-details', require('./components/custom-input/CustomInputDetails.vue'));
Vue.component('custom-service', require('./components/custom-input/CustomService.vue'));
Vue.component('custom-booking', require('./components/custom-input/CustomBooking.vue'));

//Updates
Vue.component('updates', require('./components/setting/update/updates.vue'));

//confirmation-modal
Vue.component('confirmation-modal', require('./components/confirmation-modal/confirmationModal.vue'));

//booking payment details
Vue.component('booking-payment-modal', require('./components/booking/BookingPaymentDetails.vue'));

// Custom Components
Vue.component('roller-loader', require('./components/preloader/rollerLoader.vue'));
Vue.component('common-submit-button', require('./components/CommonComponents/submitButton.vue'));
Vue.component('action_component', require('./components/CommonComponents/CommonActionComponent.vue'));

//Loaders
Vue.component('pre-loader', require('./components/preLoaders/preLoader.vue'));
Vue.component('update-loader', require('./components/preLoaders/updateLoader.vue'));
Vue.component('circle-loader', require('./components/preLoaders/circleLoader.vue'));
Vue.component('button-loader', require('./components/preLoaders/buttonLoader.vue'));
Vue.component('roller-loader', require('./components/preLoaders/rollerloader.vue'));
Vue.component('export-data', require('./components/datatable/exportData.vue'));


// Sms Settings
Vue.component('sms-setting', require('./components/setting/sms-setting/smsSetting'));
Vue.component('sms-template-list', require('./components/setting/smsTemplate/Index.vue'));
Vue.component('sms-template-list-details', require('./components/setting/smsTemplate/SmsTemplateShow.vue')); //Sms Template Details
Vue.component('sms-template-action-component', require('./components/datatable/SmsTemplateTableActions.vue'));

//Install
Vue.component('app-install-wizard', require('./components/installer/Installer'));

Vue.component('app-database-wizard', require('./components/installer/DatabaseWizard'));

Vue.config.strict = true;

Window.app = new Vue({
    el: '#app',
    data: {
        msg: 'Hello Vue JS'
    }
});
