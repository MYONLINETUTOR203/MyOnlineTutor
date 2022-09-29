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
            if (document.categorySearch.parent_id.value > 0) {
                document.frmCategory.cate_parent.value = document.categorySearch.parent_id.value;
            }
        });
    };
    setup = function (frm) {
        if (!$(frm).validate()) {
            return;
        }
        fcom.updateWithAjax(fcom.makeUrl('Categories', 'setup'), fcom.frmData(frm), function (res) {
            search(document.categorySearch);
            let element = $('.tabs_nav a.active').parent().next('li');
            if (element.length > 0) {
                let langId = element.find('a').attr('data-id');
                langForm(res.cateId, langId);
                return;
            }
            $(document).trigger('close.facebox');
        });
    };
    langForm = function (cateId, langId) {
        fcom.ajax(fcom.makeUrl('Categories', 'langForm', [cateId, langId]), '', function (response) {
            $.facebox(response);
        });
    };
    langSetup = function (frm) {
        if (!$(frm).validate()) {
            return;
        }
        fcom.updateWithAjax(fcom.makeUrl('Categories', 'langSetup'), fcom.frmData(frm), function (res) {
            search(document.categorySearch);
            let element = $('.tabs_nav a.active').parent().next('li');
            if (element.length > 0) {
                let langId = element.find('a').attr('data-id');
                langForm(res.cateId, langId);
                return;
            }
            $(document).trigger('close.facebox');
        });
    };
    remove = function (cateId) {
        if (confirm(langLbl.confirmRemove)) {
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
        fcom.updateWithAjax(fcom.makeUrl('Categories', 'updateOrder', [onDrag]), order, function (res) {
            search(document.categorySearch);
        });
    }
})();	