require(["jquery","mage/url"], function ($,url) {
    $(document).ready(function () {
        if ((window.location.href.indexOf("#payment") > -1 || window.location.href.indexOf("#smartpay") > -1) && window.location.href.indexOf("cart") > -1) {

            $("body").hide();
            var url= BASE_URL+"smartpay/index/cancel";
            window.location = url;
        }
    });
});
