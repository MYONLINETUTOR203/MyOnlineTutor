/* global fcom, langLbl */
$(document).ready(function () {
    search(document.categorySearch);
});
(function () {
    goToSearchPage = function (page) {
        var frm = document.frmCategoryPaging;
        $(frm.page).val(page);
        search(frm);
    };
    search = function (form) {
        fcom.ajax(fcom.makeUrl('Categories', 'search'), fcom.frmData(form), function (response) {
            $('#listing').html(response);
        });
    };
    clearSearch = function () {
        document.categorySearch.reset();
        search(document.categorySearch);
    };
    categoryForm = function (categoryId, langId) {
        fcom.ajax(fcom.makeUrl('Categories', 'form', [categoryId, langId]), '', function (response) {
            $.facebox(response, 'faceboxWidth');
            document.frmCategory.cate_parent.value = document.categorySearch.parent_id.value;
        });
    };
    setup = function (frm) {
        if (!$(frm).validate()) {
            return;
        }
        fcom.updateWithAjax(fcom.makeUrl('Categories', 'setup'), fcom.frmData(frm), function (res) {
            $(document).trigger('close.facebox');
            search(document.categorySearch);
            updateOrder(0);
        });
    };
    remove = function (cateId) {
        if (confirm(langLbl.confirmRemoveCategory)) {
            fcom.updateWithAjax(fcom.makeUrl('Categories', 'delete', [cateId]), '', function (response) {
                search(document.categorySearch);
            });
        }
    };
    updateStatus = function (cateId, status) {
        if (confirm(langLbl.confirmUpdateStatus)) {
            fcom.updateWithAjax(fcom.makeUrl('Categories', 'updateStatus', [cateId, status]), '', function (res) {
                search(document.categorySearch);
            });
        }
    };

    updateOrder = function (onDrag = 1) {
        var order = $("#categoriesList").tableDnDSerialize();
        fcom.ajax(fcom.makeUrl('Categories', 'updateOrder', [onDrag]), order, function (res) { });
    }
})();	