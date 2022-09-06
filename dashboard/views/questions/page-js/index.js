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
    getSubcategories = function (id, target) {
        id = (id == '') ? 0 : id;
        fcom.ajax(fcom.makeUrl('Questions', 'getSubcategories', [id]), '', function (res) {
            $(target).html(res);
        });
    };
    addForm = function () {
        fcom.ajax(fcom.makeUrl('Questions', 'addForm'), '',function (response) {
            $.facebox(response, 'facebox-medium');
        });
    };

    addOptionRow = function () {
        var type = $('#ques_type').val();
        fcom.ajax(fcom.makeUrl('Questions', 'addOption'), {type: type}, function (res) {
            $(".more-container-js").append(res);
        });
    };
    setupQuesType = function () {
        var type = $('#ques_type').val();
        if (type == TYPE_SINGLE || type == TYPE_MULTIPLE) {
            $('.options-container').show();
            $('.more-container-js').empty();    
        } else {
            $('.options-container').hide();
            $('.more-container-js').empty();
        }   
    };
    removeOptionRow = function (obj) {
        $(obj).parents('.typeFieldsJs').remove();
    };
    setupQuestion = function (frm) {
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
        console.log(answers);
        data.append('answers', answers);
        fcom.ajaxMultipart(fcom.makeUrl('Questions', 'setupQuestion'), data, function(res){
            console.log(res);
        });

    };
    
    search(document.frmQuesSearch);
});
