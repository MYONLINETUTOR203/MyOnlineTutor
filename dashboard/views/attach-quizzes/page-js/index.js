$(function () {
    searchQuizzes = function (frm, page = 1) {
        document.frmSearch.pageno.value = page;
        fcom.updateWithAjax(fcom.makeUrl('AttachQuizzes', 'search'), fcom.frmData(frm), function (res) {
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
        var frm = document.frmQuizLink;
        if (!$(frm).validate()) {
            return;
        }
        fcom.updateWithAjax(fcom.makeUrl('AttachQuizzes', 'setup'), fcom.frmData(frm), function (res) {
            $.facebox.close();
            if (document.frmSearchPaging) {
                search(document.frmSearchPaging);
                return;
            }
            window.location.reload();
        });
    };
    goToQuizPage = function (_obj) {
        searchQuizzes(document.frmSearch, $(_obj).data('page'));
    }
    quizListing = function (recordId, recordType) {
        fcom.ajax(fcom.makeUrl('AttachQuizzes', 'index'), { recordId, recordType }, function (response) {
            $.facebox(response, 'facebox-large');
            searchQuizzes(document.frmSearch);
        });
    };
    viewQuizzes = function (recordId, recordType) {
        $.facebox.close();
        fcom.ajax(fcom.makeUrl('AttachQuizzes', 'view'), { recordId, recordType }, function (response) {
            $.facebox(response, 'facebox-large');
        });
    };
    removeQuiz = function (id, obj) {
        if (!confirm(langLbl.confirmRemove)) {
            return;
        }
        fcom.updateWithAjax(fcom.makeUrl('AttachQuizzes', 'delete'), { id }, function (response) {
            viewQuizzes($(obj).data('record-id'), $(obj).data('record-type'));
            if (document.frmSearchPaging) {
                search(document.frmSearchPaging);
                return;
            }
        });
    };
    view = function (id) {
        if ($('.userListJs' + id).hasClass('is-active')) {
            $('.userListJs' + id).hide().removeClass('is-active').removeClass('is-expanded');
            return;
        } else {
            $('.userListJs').removeClass('is-active').removeClass('is-expanded').hide();
            $('.userListJs' + id).addClass('is-active').addClass('is-expanded').show();
        }
    };
});
