$(function () {
    view = function (id) {
        fcom.updateWithAjax(fcom.makeUrl('UserQuiz', 'view'), { id }, function (response) {
            if (response.expired == 1) {
                setTimeout(function () {
                    window.location = fcom.makeUrl('UserQuiz', 'completed', [id]);
                }, 2000);
            } else {
                $('.quizPanelJs').html(response.html);
                $('.questionInfoJs').html(response.questionsInfo);
                $('.totalMarksJs').html(response.totalMarks);
                $('.progressJs').html(response.progressPercent);
                $('.progressBarJs').css({ 'width': response.progressPercent });
                $('.progressBarJs').hide();
                if (response.progress > 0) {
                    $('.progressBarJs').show();
                }
            }
        });
    };
    save = function (frm) {
        saveAndNext(frm, 0);
    };
    saveAndNext = function (frm, next = 1) {
        if (!$(frm).validate()) {
            return;
        }
        $('.btnNextJs').attr('disabled', 'disabled');
        fcom.updateWithAjax(fcom.makeUrl('UserQuiz', 'saveAndNext', [next]), fcom.frmData(frm), function (res) {
            view(res.id);
        });
    };
    skipAndNext = function (id) {
        fcom.updateWithAjax(fcom.makeUrl('UserQuiz', 'setQuestion'), { 'id': id, 'next': 1 }, function (res) {
            view(id);
        });
    };
    previous = function (id) {
        $('.btnPrevJs').attr('disabled', 'disabled');
        fcom.updateWithAjax(fcom.makeUrl('UserQuiz', 'setQuestion'), { 'id' : id, 'next' : 0 }, function (res) {
            view(id);
        });
    };
    getByQuesId = function (id, quesId) {
        fcom.updateWithAjax(fcom.makeUrl('UserQuiz', 'setQuestion'), { 'id': id, 'next': 0, 'ques_id': quesId }, function (res) {
            view(id);
        });
    };
    saveAndFinish = function () {
        if (!confirm(langLbl.confirmQuizComplete)) {
            return;
        }
        var frm = document.frmQuiz;
        fcom.updateWithAjax(fcom.makeUrl('UserQuiz', 'saveAndFinish'), fcom.frmData(frm), function (res) {
            window.location = fcom.makeUrl('UserQuiz', 'completed', [res.id]);
        });
    };
});