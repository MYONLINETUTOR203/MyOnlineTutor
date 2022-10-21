$(function () {
    view = function (id) {
        fcom.updateWithAjax(fcom.makeUrl('QuizReview', 'view'), { id }, function (response) {
            $('.quizPanelJs').html(response.html);
            $('.questionInfoJs').html(response.questionsInfo);
        });
    };
    getQuestion = function (id, next, quesId) {
        fcom.updateWithAjax(fcom.makeUrl('QuizReview', 'setQuestion'), { 'id': id, 'next': next, 'ques_id': quesId }, function (res) {
            view(id);
        });
    };
    next = function (id) {
        getQuestion(id, 1);
    };
    previous = function (id) {
        getQuestion(id, 0);
    };
    getByQuesId = function (id, quesId) {
        getQuestion(id, 0, quesId);
    };
    finish = function (id, type) {
        fcom.updateWithAjax(fcom.makeUrl('QuizReview', 'finish'), { 'id': id }, function (res) {
            window.location = fcom.makeUrl(type);
        });
    };
});