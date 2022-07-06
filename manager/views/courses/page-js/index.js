/* global fcom */
$(document).ready(function () {
    search(document.frmSearch);

    $("input[name='course_tlang']").autocomplete({
        'source': function (request, response) {
            $.ajax({
                url: fcom.makeUrl('TeachLanguage', 'autoCompleteJson'),
                data: { keyword: request, fIsAjax: 1 },
                dataType: 'json',
                type: 'post',
                success: function (result) {
                    response($.map(result.data, function (item) {
                        return { label: escapeHtml(item['tlang_name']), value: item['tlang_id'], name: item['tlang_name'] };
                    }));
                },
            });
        },
        'select': function (item) {
            $("input[name='course_tlang_id']").val(item.value);
            $("input[name='course_tlang']").val(item.name);
        }
    });
    $("input[name='course_tlang']").keyup(function () {
        $("input[name='course_tlang_id']").val('');
    });
});
(function () {
    var dv = '#listing';
    goToSearchPage = function (pageno) {
        var frm = document.frmPaging;
        $(frm.pageno).val(pageno);
        search(frm);
    };
    search = function (form) {
        var data = data = fcom.frmData(form);
        fcom.ajax(fcom.makeUrl('Courses', 'search'), data, function (res) {
            $(dv).html(res);
        });
    };
    clearSearch = function () {
        document.frmSearch.reset();
        $("input[name='course_tlang_id']").val('');
        search(document.frmSearch);
    };

    view = function (courseId) {
        fcom.ajax(fcom.makeUrl('Courses', 'view', [courseId]), '', function (res) {
            $.facebox(res, 'faceboxWidth');
        });
    };
    userLogin = function (userId, courseId, action = 'edit') {
        fcom.updateWithAjax(fcom.makeUrl('Users', 'login', [userId]), '', function (res) {
            if (action == 'edit') {
                window.open(fcom.makeUrl('Courses', 'form', [courseId], SITE_ROOT_DASHBOARD_URL), "_blank");
            } else if(action == 'preview') {
                window.open(fcom.makeUrl('CoursePreview', 'index', [courseId], SITE_ROOT_DASHBOARD_URL), "_blank");
            }
        });
    };
})();
