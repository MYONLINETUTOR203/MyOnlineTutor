/* global weekDayNames, monthNames, langLbl, layoutDirection, fcom */
$(function () {
    searchQuizzes = function (frm, page = 1) {
        document.frmSearch.pageno.value = page;
        fcom.updateWithAjax(fcom.makeUrl('AttachQuizzes', 'search', [1]), fcom.frmData(frm), function (res) {
            if (page > 1) {
                $('#quiz-listing tbody').append(res.html);
            } else {
                $('#quiz-listing tbody').html(res.html);
            }
            if (res.loadMore == 1) {
                $('.loadMoreJs a').data('page', res.nextPage);
                $('.loadMoreJs').show();
            } else {
                $('.loadMoreJs').hide();
            }
        });
    };
    clearQuizSearch = function () {
        document.frmSearch.reset();
        searchQuizzes(document.frmSearch);
    };
    attachQuizzes = function () {

    };
    goToQuizPage = function (_obj) {
        searchQuizzes(document.frmSearch, $(_obj).data('page'));
    }
    quizListing = function (recordId, recordType) {
        fcom.ajax(fcom.makeUrl('AttachQuizzes', 'index'), { recordId, recordType }, function (response) {
            $.facebox(response, 'facebox-medium');
            searchQuizzes(document.frmSearch);
        });
    };
});
