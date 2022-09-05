/* global weekDayNames, monthNames, langLbl, layoutDirection, fcom */
$(function () {
    goToSearchPage = function (pageno) {
        var frm = document.frmSearchPaging;
        $(frm.pageno).val(pageno);
        search(frm);
    };
    search = function (frm) {
        fcom.ajax(fcom.makeUrl('Questions', 'search'), fcom.frmData(frm), function (res) {
            $("#listing").html(res);
        });
    };
    clearSearch = function () {
        document.frmQuesSearch.reset();
        search(document.frmQuesSearch);
    };
    remove = function (id) {
        if (!confirm(langLbl.confirmRemove)) {
            return;
        }
        fcom.updateWithAjax(fcom.makeUrl('Questions', 'remove'), {quesId: id}, function (res) {
            search(document.frmQuesSearch);
        });
    };
    getSubcategories = function (id) {
        id = (id == '') ? 0 : id;
        fcom.ajax(fcom.makeUrl('Questions', 'getSubcategories', [id]), '', function (res) {
            $("#subCategories").html(res);
        });
    };
    addForm = function () {
        fcom.ajax(fcom.makeUrl('Questions', 'addForm'), '',function (response) {
            $.facebox(response, 'facebox-medium');
        });
    };

    addOptionRow = function () {
        fcom.ajax(fcom.makeUrl('Questions', 'addOption'), '', function (res) {
            $(".more-container-js").append(res);
        });
    };
    search(document.frmQuesSearch);
});
