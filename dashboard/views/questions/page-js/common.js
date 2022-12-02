/* global weekDayNames, monthNames, langLbl, layoutDirection, fcom */
var quizReq = 0;
$(function () {
    questionForm = function (id, type = 0) {
        quizReq = type;
        fcom.ajax(fcom.makeUrl('Questions', 'form', [id, type]), '', function (response) {
            $.facebox(response, 'facebox-medium');
        });
    };
    showOptions = function (type) {
        if (type == TYPE_SINGLE || type == TYPE_MULTIPLE) {
            $('.options-container').show();
            $('.more-container-js').empty();
            $('.recorderJs').hide().find('input[name="audio_filename"]').val('');
        } else {
            $('.options-container').hide();
            $('.more-container-js').empty();
            if (type == TYPE_AUDIO) {
                $('.recorderJs').show();
                getPlayer();
            }
        }
    };
    addOptions = function () {
        var type = document.frmQuestion.ques_type.value;
        var count = document.frmQuestion.ques_options_count.value;
        var quesId = document.frmQuestion.ques_id.value;
        if (count < 1) {
            return;
        }
        var opts = $('.sortableLearningJs .optionsRowJs').length;
        if (count != opts) {
            fcom.ajax(fcom.makeUrl('Questions', 'optionForm'), { type, count, quesId }, function (res) {
                $(".more-container-js").html(res);
            });
        }
    };
    setupQuestion = function (frm) {
        if (!$(frm).validate()) {
            return;
        }
        var data = new FormData(frm);
        if ($('.recordrtc').length > 0) {
            data = appendRecordedFile(data);
        }
        fcom.ajaxMultipart(fcom.makeUrl('Questions', 'setup'), data, function (res) {
            if (quizReq > 0) {
                $('.addQuesJs').click();
            } else {
                search(document.frmQuesSearch);
                $.facebox.close();
            }
        }, { fOutMode: 'json' });
    };
    getSubcategories = function (id, target, subCategoryId = 0) {
        id = (id == '') ? 0 : id;
        subCategoryId = (subCategoryId == '') ? 0 : subCategoryId;
        fcom.ajax(fcom.makeUrl('Questions', 'getSubcategories', [id, subCategoryId]), '', function (res) {
            $(target).html(res);
            if (subCategoryId > 0) {
                $(target).val(subCategoryId);
            }
        }, { process: false });
    };
    removeRecording = function (id) {
        var hasFile = $('.recordrtc').hasClass('hasFile');
        var recorderDiv = $('.audioRecorderJs audio');
        var recordingDiv = $('.audioRecordingJs audio');
        if (!hasFile && recordingDiv.length < 1) {
            return;
        }
        if (!confirm(langLbl.confirmRemove)) {
            return;
        }
        if (hasFile) {
            fcom.updateWithAjax(fcom.makeUrl('Questions', 'removeRecording'), { id }, function (res) {
                $(recorderDiv).attr('src', '').parent().show();
                recorderDiv[0].pause();
                recorderDiv[0].load();
                recorderDiv[0].controlsList = "noplaybackrate nodownload nofullscreen autoplay";
                $('.recordrtc').removeClass('hasFile');
                $(recordingDiv).parent().hide();
                $(recordingDiv).remove();
                removeRecordedFile()
            });
        } else {
            $(recorderDiv).attr('src', '').parent().show();
            recorderDiv[0].pause();
            recorderDiv[0].load();
            $(recordingDiv).parent().hide();
            $(recordingDiv).remove();
            removeRecordedFile()
        }
    };
});
