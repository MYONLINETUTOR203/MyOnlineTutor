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
    getSubcategories = function (id, target, subCategoryId = 0) {
        id = (id == '') ? 0 : id;
        fcom.ajax(fcom.makeUrl('Questions', 'getSubcategories', [id]), '', function (res) {
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
        var data = fcom.frmData(document.frmQuestion);
        fcom.ajax(fcom.makeUrl('Questions', 'optionForm'), data, function (res) {
            $(".more-container-js").html(res);
        });
    };
    showOptions = function () {
        var type = $('#ques_type').val();
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
        var data = new FormData(frm);
        var i = 0;
        var answers = new Array();
        $('.typeFieldsJs').each(function() {
            console.log($(this).find('.optAnswer'));
            if ($(this).find('.optAnswer').is(':checked')) {
                answers.push(i);
            }
            i++;
        });
        data.append('answers', answers);
        fcom.ajaxMultipart(fcom.makeUrl('Questions', 'setup'), data, function(res){
            search(document.frmQuesSearch);
            $.facebox.close();
        });

    };
    
    search(document.frmQuesSearch);
});
