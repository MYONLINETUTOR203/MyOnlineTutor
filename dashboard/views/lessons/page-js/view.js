/* global fcom, langLbl, COMPLETED, ACTIVE_MEETING_TOOL, ZOOM_APP, joinFromApp, LESSON_SPACE, COMET_CHAT_APP, COMETCHAT_APP_ID, chat_width, testTool, chat_height, addEmbedIframe */
(function () {
    joinLesson = function (lessonId, joinFromApp) {
        var data = { lessonId: lessonId, joinFromApp: joinFromApp };
        fcom.ajax(fcom.makeUrl('Lessons', 'joinMeeting'), data, function (response) {
            var res = JSON.parse(response);
            var meToolCode = res.meeting.metool_code;
            var meet = JSON.parse(res.meeting.meet_details);
            $('#endL').removeClass('d-none');
            if (meToolCode != ATOM_CHAT) {
                if (joinFromApp) {
                    window.open(meet.appUrl, "_blank");
                } else {
                    loadIframe(meet.joinUrl);
                }
            } else {
                createCometChatBox(meet, "#lessonBox");
            }
        });
    };
    endLesson = function (lessonId) {
        if (confirm(endLessonConfirmMsg)) {
            fcom.ajax(fcom.makeUrl('Lessons', 'endMeeting'), { lessonId: lessonId }, function (response) {
                reloadPage(3000);
            });
        }
    };
    checkLessonStatus = function (lessonId, currentStatus) {
        if (typeof checkLessonStatusVar != "undefined") {
            return;
        }
        checkLessonStatusVar = setInterval(function () {
            fcom.ajax(fcom.makeUrl('Lessons', 'checkLessonStatus', [lessonId]), '', function (response) {
                var res = JSON.parse(response);
                if (currentStatus == SCHEDULED && res.ordles_status == COMPLETED) {
                    clearInterval(checkLessonStatusVar);
                    reloadPage(5000);
                }
            },{process:false});
        }, 15000);
    };
    loadIframe = function (url) {
        $('.lessonBox').removeClass('sesson-window__content').addClass('session-window__frame').show();
        let html = '<div id="chat_box_div" style="width:100%;height:100%;max-width:100%;border:1px solid #CCCCCC;border-radius:5px;overflow:hidden;">';
        html += '<iframe  style="width:100%;height:100%;" src="' + url + '" allow="camera; microphone; fullscreen;display-capture" frameborder="0"></iframe>';
        html += '</div>';
        $("#lessonBox").html(html);
    };
})();