
/* global fcom, SITE_ROOT_FRONT_URL */
$(document).ready(function () {
    search = function (frm) {
        fcom.ajax(fcom.makeUrl('Quizzes', 'search'), fcom.frmData(frm), function (res) {
            $("#listing").html(res);
        });
    };
    clearSearch = function () {
        document.frmSearch.reset();
        document.frmSearch.teacher_id.value = '';
        search(document.frmSearch);
    };
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
    goToSearchPage = function (page) {
        var frm = document.frmPaging;
        $(frm.pageno).val(page);
        search(frm);
    };
    view = function (id) {
        fcom.ajax(fcom.makeUrl('Quizzes', 'view', [id]), '', function (res) {
            $.facebox(res, 'fbminwidth facebox-medium');
            resizeIframe(100);
        });
    };
    questions = function (id) {
        fcom.ajax(fcom.makeUrl('Quizzes', 'questions', [id]), '', function (res) {
            $.facebox(res, 'fbminwidth facebox-medium');
        });
    };
    search(document.frmSearch);
});