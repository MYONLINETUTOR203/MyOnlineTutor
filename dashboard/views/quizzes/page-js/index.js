/* global weekDayNames, monthNames, langLbl, layoutDirection, fcom */
$(function () {
    goToSearchPage = function (pageno) {
        var frm = document.frmPaging;
        $(frm.pageno).val(pageno);
        search(frm);
    };
    search = function (frm) {
        fcom.ajax(fcom.makeUrl('Quizzes', 'search'), fcom.frmData(frm), function (res) {
            $("#quiz-listing").html(res);
        });
    };
    updateStatus = function (id, obj) {
        var status = $(obj).val();
        var checked = $(obj).is(':checked');
        fcom.updateWithAjax(fcom.makeUrl('Quizzes', 'updateStatus'), { id, status }, function (res) {
            search(document.frmSearch);
            return;
        });
        $(obj).prop('checked', (checked == false) ? true : false);
    }
    remove = function (id) {
        if (!confirm(langLbl.confirmRemove)) {
            return;
        }
        fcom.updateWithAjax(fcom.makeUrl('Quizzes', 'delete'), { id }, function (res) {
            search(document.frmSearch);
        });
    };
    clearSearch = function () {
        document.frmSearch.reset();
        search(document.frmSearch);
    };
    search(document.frmSearch);
});
