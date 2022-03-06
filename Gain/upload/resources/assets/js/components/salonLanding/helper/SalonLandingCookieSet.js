export const setServiceIdToCookie = function (cookieObject) {
    let name = `service-id-${cookieObject.appVersion}`;
    window.$cookies.set(name, cookieObject, "2y");
}

export const getServiceIdFromCookie = function (version) {
    let name = `service-id-${version}`,
        serviceId = window.$cookies.get(name);
    return serviceId
}

export const deleteServiceIdFromCookie = function (version) {
    let name = `service-id-${version}`;
    window.$cookies.remove(name);
}