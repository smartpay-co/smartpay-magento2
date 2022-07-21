define([
  "Magento_Checkout/js/view/payment/default",
  "ko",
  "mage/url",
  "Magento_Checkout/js/model/quote",
  "Magento_Catalog/js/price-utils",
], function (Component, ko, url, quote, priceUtils) {
  "use strict";
  const config = window.checkoutConfig.payment.smartpay;

  const getAmountForTheRest = function (amount, numberOfInstallments) {
    return (amount - (amount % numberOfInstallments)) / numberOfInstallments;
  };

  const getAmountForTheFirst = function (amount, numberOfInstallments) {
    return (
      getAmountForTheRest(amount, numberOfInstallments) +
      (amount % numberOfInstallments)
    );
  };

  const currencySymbol = function () {
    return window.checkoutConfig.totalsData.base_currency_code &&
      window.checkoutConfig.totalsData.base_currency_code === "JPY"
      ? "¥"
      : "";
  };

  return Component.extend({
    redirectAfterPlaceOrder: false,
    defaults: {
      template: "Smartpay_Smartpay/payment/smartpay",
    },

    title: ko.observable(""),
    total: ko.observable(NaN),

    updateTitle: function () {
      this.title(this.getSmartpayTitle());
    },

    updateTotal: function () {
      this.total(this.getGrandTotal());
    },

    initialize: function () {
      this._super();

      quote.totals.subscribe(this.updateTitle.bind(this));
      quote.totals.subscribe(this.updateTotal.bind(this));
      this.updateTitle();
      this.updateTotal();
    },

    afterPlaceOrder: function () {
      window.location.replace(url.build("smartpay/redirect/index"));
    },

    getSmartpayTitle: function () {
      const config = window.checkoutConfig.payment.smartpay;
      const grandTotal = quote.totals().base_grand_total;
      const installments = config.number_of_payments;
      const price =
        currencySymbol() +
        priceUtils.formatPrice(getAmountForTheFirst(grandTotal, installments));
      const logo = config.logo;
      const str = config.title;

      return str
        .replace("[logo]", logo)
        .replace("[price]", price)
        .replace("[installments]", installments);
    },

    getGrandTotal: function () {
      return quote.totals().base_grand_total;
    },

    getInstructions: function () {
      const config = window.checkoutConfig.payment.smartpay;
      const str = config.instructions;

      return str;
    },

    renderOsm: function () {
      if (window.smartpay && window.smartpay.messaging) {
        window.smartpay.messaging.render();
      } else {
        const config = window.checkoutConfig.payment.smartpay;
        const s = document.createElement("script");

        s.setAttribute("data-merchant", config.public_key);
        s.src = "https://js.smartpay.co/messaging.js";

        document.head.appendChild(s);
      }
    },
  });
});
