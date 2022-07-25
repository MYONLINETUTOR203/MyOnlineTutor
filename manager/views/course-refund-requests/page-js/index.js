/* global fcom, langLbl */
$(document).ready(function () {
    search(document.requestSearch);
});
(function () {
    goToSearchPage = function (page) {
        var frm = document.requestSearch;
        $(frm.page).val(page);
        search(frm);
    };
    search = function (form) {
        fcom.ajax(fcom.makeUrl('CourseRefundRequests', 'search'), fcom.frmData(form), function (response) {
            $('#listing').html(response);
        });
    };
    clearSearch = function () {
        document.requestSearch.reset();
        search(document.requestSearch);
    };
    view = function (reqId) {
        fcom.ajax(fcom.makeUrl('CourseRefundRequests', 'view', [reqId]), '', function (response) {
            $.facebox(response, 'faceboxWidth');
        });
    };
    updateStatus = function (frm) {
        if (!$(frm).validate()) {
            return;
        }
        fcom.updateWithAjax(fcom.makeUrl('CourseRefundRequests', 'updateStatus'), fcom.frmData(frm), function (res) {
            $(document).trigger('close.facebox');
            search(document.requestSearch);
        });
    };
    showHideCommentBox = function (val) {
        if (val == REFUND_DECLINED) {
            $('#remarkField').show();
        } else {
            $('#remarkField').hide();
        }
    };
    changeStatusForm = function (reqId) {
        fcom.ajax(fcom.makeUrl('CourseRefundRequests', 'form', [reqId]), '', function (response) {
            $.facebox(response, 'faceboxWidth');
            showHideCommentBox();
        });
    };
})();	