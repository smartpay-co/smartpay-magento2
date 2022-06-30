define([
    'Magento_Checkout/js/view/payment/default',
    'ko',
    'mage/url',
    'Magento_Checkout/js/model/quote',
    'Magento_Catalog/js/price-utils'
], function (Component, ko, url, quote, priceUtils) {
    'use strict';

    return Component.extend({
        redirectAfterPlaceOrder: false,
        defaults: {
            template: 'Smartpay_Smartpay/payment/smartpay'
        },

        title: ko.observable(""),

        updateTitle: function () {
            this.title(this.getSmartpayTitle());
        },

        initialize: function () {
            this._super();

            quote.totals.subscribe(this.updateTitle.bind(this));
            this.updateTitle(this);
        },

        afterPlaceOrder: function () {
            window.location.replace(url.build('smartpay/redirect/index'));
        },

        getSmartpayTitle: function () {
            const config = window.checkoutConfig.payment.smartpay;
            const grandTotal = quote.totals().base_grand_total;
            const installments = config.number_of_payments;
            const price = priceUtils.formatPrice(grandTotal / installments);
            const logo = config.logo;
            const str = config.title;

            return str
                .replace("[logo]", logo)
                .replace("[price]", price)
                .replace("[installments]", installments);
        },

        getInstructions: function () {
            const config = window.checkoutConfig.payment.smartpay;
            const str = config.instructions;

            return str;
        },
    });
});
