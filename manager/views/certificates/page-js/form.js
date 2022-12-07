(function () {
    var preview = 0;
    setupMedia = function () {
        var frm = document.frmMedia;
        if (!$(frm).validate()) {
            return;
        }
        fcom.process();
        var data = new FormData(frm);
        fcom.ajaxMultipart(fcom.makeUrl('Certificates', 'setupMedia'), data, function (response) {
            $(frm)[0].reset();
            $('.certificateMediaJs img').attr('src', response.imgUrl);
        }, { fOutMode: 'json' });
    };
    setup = function () {
        var frm = (document.frmCertificate);
        if (!$(frm).validate()) {
            return;
        }
        var data = fcom.frmData(frm);
        data += "&heading=" + encodeURIComponent($.trim($('.contentHeadingJs').text()));
        data += "&content_part_1=" + encodeURIComponent($.trim($('.contentPart1Js').text()));
        data += "&learner=" + encodeURIComponent($.trim($('.contentLearnerJs').text()));
        data += "&content_part_2=" + encodeURIComponent($.trim($('.contentPart2Js').text()));
        data += "&trainer=" + encodeURIComponent($.trim($('.contentTrainerJs').text()));
        data += "&certificate_number=" + encodeURIComponent($.trim($('.contentCertNoJs').text()));
        fcom.updateWithAjax(fcom.makeUrl('Certificates', 'setup'), data, function (t) {
            if (preview == 1) {
                preview = 0;
                var time = (new Date()).getTime();
                window.open(fcom.makeUrl('Certificates', 'generate', [$('input[name="certpl_id"]').val()]) + '?time=' + time, '_blank');
            }
        });
        return false;
    };
    edit = function (certTplCode, langId) {
        window.location = fcom.makeUrl('Certificates', 'form', [certTplCode, langId]);
    };
    setupAndPreview = function () {
        preview = 1;
        setup();
    };
    resetToDefault = function () {
        var data = fcom.frmData(document.frmCertificate);
        fcom.ajax(fcom.makeUrl('Certificates', 'getDefaultContent'), data, function (response) {
            if (response.data) {
                $('.contentHeadingJs').html(response.data.heading);
                $('.contentPart1Js').html(response.data.content_part_1);
                $('.contentLearnerJs').html(response.data.learner);
                $('.contentPart2Js').html(response.data.content_part_2);
                $('.contentTrainerJs').html(response.data.trainer);
                $('.contentCertNoJs').html(response.data.certificate_number);
            }
        }, { fOutMode: 'json' });
    };
})();
