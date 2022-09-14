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
    questions = function (id) {
        fcom.ajax(fcom.makeUrl('Quizzes', 'questions'), { id }, function (resp) {
            $('#pageContentJs').html(resp);
        });
    };
    settings = function (id) {
        fcom.ajax(fcom.makeUrl('Quizzes', 'settings'), { id }, function (resp) {
            $('#pageContentJs').html(resp);
        });
    };
});