$(function () {
    getQuestion = function (id) {
        fcom.updateWithAjax(fcom.makeUrl('UserQuiz', 'getQuestion'), { id }, function (response) {
            
        });
    };
});