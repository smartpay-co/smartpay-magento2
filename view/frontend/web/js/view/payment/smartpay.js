define([
    'uiComponent',
    'Magento_Checkout/js/model/payment/renderer-list'
], function (Component, rendererList) {
    'use strict';

    rendererList.push({
        type: 'smartpay',
        component: 'Smartpay_Smartpay/js/view/payment/method-renderer/smartpay'
    });

    return Component.extend({});
});
