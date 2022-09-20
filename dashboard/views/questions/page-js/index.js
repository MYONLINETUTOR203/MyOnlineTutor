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
        getSubcategories(0, '#subCategories');
    };
    remove = function (id, isBinded = false) {
        var msg = langLbl.confirmRemove;
        if (isBinded == true) {
            msg = langLbl.confirmBindedQuesRemoval;
        }
        if (!confirm(msg)) {
            return;
        }
        fcom.updateWithAjax(fcom.makeUrl('Questions', 'remove'), {quesId: id}, function (res) {
            search(document.frmQuesSearch);
        });
    };
    getSubcategories = function (id, target, subCategoryId = 0) {
        id = (id == '') ? 0 : id;
        subCategoryId = (subCategoryId == '') ? 0 : subCategoryId;
        fcom.ajax(fcom.makeUrl('Questions', 'getSubcategories', [id, subCategoryId]), '', function (res) {
            $(target).html(res);
            if(subCategoryId > 0){
                $(target).val(subCategoryId);
            }
        });
    };
    form = function (id) {
        fcom.ajax(fcom.makeUrl('Questions', 'form', [id]), '',function (response) {
            $.facebox(response, 'facebox-medium');
        });
    };

    addOptions = function () {
        var type = document.frmQuestion.ques_type.value;
        var count = document.frmQuestion.ques_options_count.value;
        var opts = $('.sortableLearningJs .optionsRowJs').length;
        if (count != opts) {
            fcom.ajax(fcom.makeUrl('Questions', 'optionForm'), { type, count }, function (res) {
                $(".more-container-js").html(res);
            });
        }
    };
    showOptions = function (type) {
        if (type == TYPE_SINGLE || type == TYPE_MULTIPLE) {
            $('.options-container').show();
            $('.more-container-js').empty();    
        } else {
            $('.options-container').hide();
            $('.more-container-js').empty();
        }   
    };
    setup = function (frm) {
        if (!$(frm).validate()) {
            return;
        }
        var data = fcom.frmData(frm);
        fcom.updateWithAjax(fcom.makeUrl('Questions', 'setup'), data, function(res){
            search(document.frmQuesSearch);
            $.facebox.close();
        });

    };
    updateStatus = function (id, obj) {
        var status = $(obj).val();
        var checked = $(obj).is(':checked');
        fcom.updateWithAjax(fcom.makeUrl('Questions', 'updateStatus'), { id, status }, function (res) {
            search(document.frmQuesSearch);
            return;
        });
        $(obj).prop('checked', (checked == false) ? true : false);
    }
    search(document.frmQuesSearch);
});
