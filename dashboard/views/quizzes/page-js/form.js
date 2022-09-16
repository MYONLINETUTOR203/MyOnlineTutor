/* global weekDayNames, monthNames, langLbl, layoutDirection, fcom */
$(function () {
    form = function (id) {
        fcom.ajax(fcom.makeUrl('Quizzes', 'basic'), {id}, function (resp) {
            $('#pageContentJs').html(resp);
            var id = $('textarea[name="quiz_detail"]').attr('id');
            window["oEdit_" + id].disableFocusOnLoad = true;
        });
    };
    setup = function () {
        var frm = document.frmQuiz;
        if (!$(frm).validate()) {
            return;
        }
        var data = fcom.frmData(frm);
        fcom.updateWithAjax(fcom.makeUrl('Quizzes', 'setup'), data, function (res) {
            questions(res.quizId);
            window.history.pushState('page', document.title, fcom.makeUrl('Quizzes', 'form', [res.quizId]));
        });
    };
    /* Questions [ */
    questions = function (id) {
        fcom.ajax(fcom.makeUrl('Quizzes', 'questions'), { id }, function (resp) {
            $('#pageContentJs').html(resp);
        });
    };
    addQuestions = function (id) {
        fcom.ajax(fcom.makeUrl('QuizQuestions', 'index'), { id }, function (resp) {
            $.facebox(resp, 'facebox-large padding-0');
            searchQuestions(document.frmQuesSearch);
        });
    };
    searchQuestions = function (frm, page = 1) {
        document.frmQuesSearch.pageno.value = page;
        fcom.updateWithAjax(fcom.makeUrl('QuizQuestions', 'search'), fcom.frmData(frm), function (res) {
            if (page > 1) {
                $('#listingJs').append(res.html);
            } else {
                $('#listingJs').html(res.html);
            }
            if (res.loadMore == 1) {
                $('.loadMoreJs a').data('page', res.nextPage);
                $('.loadMoreJs').show();
            } else {
                $('.loadMoreJs').hide();
            }
        });
    };
    goToPage = function (_obj) {
        searchQuestions(document.frmQuesSearch, $(_obj).data('page'));
    }
    clearSearch = function() {
        document.frmQuesSearch.reset();
        getSubcategories(0);
        searchQuestions(document.frmQuesSearch);
    };
    getSubcategories = function (id) {
        id = (id == '') ? 0 : id;
        fcom.ajax(fcom.makeUrl('Questions', 'getSubcategories', [id]), '', function (res) {
            $('#quesSubCateJs').html(res);
        });
    };
    attachQuestions = function () {
        var frm = document.frmQuestions;
        fcom.updateWithAjax(fcom.makeUrl('QuizQuestions', 'setup'), fcom.frmData(frm), function (res) {
            questions(res.quizId);
            $.facebox.close();
        });
    };
    remove = function(quizId, quesId) {
        if (!confirm(langLbl.confirmRemove)) {
            return;
        }
        fcom.updateWithAjax(fcom.makeUrl('QuizQuestions', 'delete'), { quizId, quesId }, function (res) {
            questions(quizId);
        });
    };
    /* ] */
    /* Settings [ */
    settings = function (id) {
        fcom.ajax(fcom.makeUrl('Quizzes', 'settings'), { id }, function (resp) {
            $('#pageContentJs').html(resp);
        });
    };
    setupSettings = function (frm) {
        if (!$(frm).validate()) {
            return;
        }
        fcom.updateWithAjax(fcom.makeUrl('Quizzes', 'setupSettings'), fcom.frmData(frm), function (res) {
            
        });
    }
    /* ] */
});