
/* global fcom, grecaptcha */
(function () {
    forgotPassword = function () {
        fcom.ajax(fcom.makeUrl('GuestUser', 'forgotPassword'), '', function (res) {
            $.facebox(res, 'facebox-medium');
        });
    };
    forgotPasswordSetup = function (frm) {
        if (!$(frm).validate()) {
            return;
        }
        fcom.updateWithAjax(fcom.makeUrl('GuestUser', 'forgotPasswordSetup'), fcom.frmData(frm), function () {
            grecaptcha.reset();
            frm.reset();
        }, {failed: true});
    };
})();