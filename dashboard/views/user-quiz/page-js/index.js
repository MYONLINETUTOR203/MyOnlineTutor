$(function () {
    start = function (id) {
        fcom.updateWithAjax(fcom.makeUrl('UserQuiz', 'start'), { id }, function (response) {
            window.location = fcom.makeUrl('UserQuiz', 'questions', [id]);
        });
    };
});
$(document).ready(function () {
    resizeIframe(50);
});