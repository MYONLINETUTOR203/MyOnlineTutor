$(function () {
    view = function (id) {
        fcom.updateWithAjax(fcom.makeUrl('UserQuiz', 'view'), { id }, function (response) {
            $('.quizPanelJs').html(response.html);
        });
    };
});