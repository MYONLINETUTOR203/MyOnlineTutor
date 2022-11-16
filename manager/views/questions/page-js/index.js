
/* global fcom, SITE_ROOT_FRONT_URL */
$(document).ready(function () {
    searchQuestions(document.frmQuesSearch);
    $("input[name='teacher']").autocomplete({
        'source': function (request, response) {
            fcom.updateWithAjax(fcom.makeUrl('Users', 'autoCompleteJson'), {
                keyword: request
            }, function (result) {
                response($.map(result.data, function (item) {
                    return {
                        label: escapeHtml(item['full_name'] + ' (' + item['user_email'] + ')'),
                        value: item['user_id'], name: item['full_name']
                    };
                }));
            });
        },
        'select': function (item) {
            $("input[name='teacher_id']").val(item.value);
            $("input[name='teacher']").val(item.name);
        }
    });
    $("input[name='teacher']").keyup(function () {
        $("input[name='teacher_id']").val('');
    });
    $(document).on('click', 'ul.linksvertical li a.redirect--js', function (event) {
        event.stopPropagation();
    });
});
(function () {
    searchQuestions = function (form) {
        if (!form) {
            return;
        }
        fcom.ajax(fcom.makeUrl('Questions', 'search'), fcom.frmData(form), function (res) {
            $("#questionListing").html(res);
        });
    };
    goToSearchPage = function (page) {
        var frm = document.frmPaging;
        $(frm.page).val(page);
        searchQuestions(frm);
    };
    
    clearSearch = function () {
        document.frmQuesSearch.reset();
        document.frmQuesSearch.teacher_id.value = '';
        $("select[name='ques_cate_id'], select[name='ques_subcate_id']").val('');
        getSubcategories(0);
        searchQuestions(document.frmQuesSearch);
    };

    getSubcategories = function (id, selectedId = 0) {
        id = (id == '') ? 0 : id;
        fcom.ajax(fcom.makeUrl('Questions', 'getSubcategories', [id, selectedId]), '', function (res) {
            $("#subCategories").html(res);
        }, { async : false });
    };

    view = function (quesId) {
        fcom.ajax(fcom.makeUrl('Questions', 'view', [quesId]), '', function (res) {
            $.facebox(res, 'faceboxWidth');
        });
    };

 
})();