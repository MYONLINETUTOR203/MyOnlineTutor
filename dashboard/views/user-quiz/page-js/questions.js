$(function () {
    getQuestion = function (id) {
        fcom.updateWithAjax(fcom.makeUrl('UserQuiz', 'getQuestion'), { id }, function (response) {
            $('.quizPanelJs').html(response.html);
            $('.questionInfoJs').html(response.questionsInfo);
            $('.totalMarksJs').html(response.totalMarks);
            $('.progressJs').html(response.progressPercent);
            $('.progressBarJs').css({ 'width': response.progressPercent });
            $('.progressBarJs').hide();
            if (response.progress > 0) {
                $('.progressBarJs').show();
            }
        });
    };
    setup = function (frm) {
        if (!$(frm).validate()) {
            return;
        }
        fcom.updateWithAjax(fcom.makeUrl('UserQuiz', 'setup'), fcom.frmData(frm), function (res) {
            getQuestion(res.id);
        });
    };
    markComplete = function (id) {
        if (!confirm(langLbl.confirmQuizComplete)) {
            return;
        }
        fcom.updateWithAjax(fcom.makeUrl('UserQuiz', 'markComplete'), { id }, function (res) {
            window.location = fcom.makeUrl('UserQuiz', 'completed', [id]);
        });
    };
});