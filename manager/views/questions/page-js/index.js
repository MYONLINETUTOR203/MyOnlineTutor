
/* global fcom, SITE_ROOT_FRONT_URL */
$(document).ready(function () {
    searchQuestions(document.frmQuesSearch);
    $("input[name='quesTeacher']").autocomplete({
        'source': function (request, response) {
            fcom.updateWithAjax(fcom.makeUrl('Questions', 'teacherAutoCompleteJson'), {
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
            $("input[name='quesTeacher']").val(item.name);
        }
    });
    $("input[name='quesTeacher']").keyup(function () {
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
    
    clearQuestionSearch = function () {
        document.frmQuesSearch.reset();
        document.frmQuesSearch.teacher_id.value = '';
        searchQuestions(document.frmQuesSearch);
    };

    getSubcategories = function (id) {
        id = (id == '') ? 0 : id;
        fcom.ajax(fcom.makeUrl('Questions', 'getSubcategories', [id]), '', function (res) {
            $("#subCategories").html(res);
        });
    };

    view = function (quesId) {
        fcom.ajax(fcom.makeUrl('Questions', 'view', [quesId]), '', function (res) {
            $.facebox(res, 'faceboxWidth');
        });
    };

 
})();
