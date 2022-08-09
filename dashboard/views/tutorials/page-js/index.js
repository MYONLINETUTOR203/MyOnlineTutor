var tutor = false;
var notes = false;
var lecture = false;
var reviews = false;
$(function () {
    loadLecture = function (lectureId) {
        if (lecture == false || lectureId > 0) {
            var progressId = $('#progressId').val();
            fcom.ajax(fcom.makeUrl('Tutorials', 'getLectureData', [lectureId, progressId]), '', function (res) {
                $('.lectureDetailJs').html(res);
                setCurrentLecture(lectureId);
                getVideo(lectureId);
                lecture = true;
            });
        }
        $('.lectureDetailJs, .notesJs, .reviewsJs, .tutorInfoJs').hide();
        $('.sidebarPanelJs').css({'display': ''});
        $('.tutorialTabsJs ul li').removeClass('is-active');
        $('.crsDetailTabJs').parent().addClass('is-active');
        $('.lectureDetailJs, .tabsPanelJs').show();
    };
    getVideo = function (lectureId) {
        var progressId = $('#progressId').val();
        fcom.ajax(fcom.makeUrl('Tutorials', 'getVideo', [lectureId, progressId]), '', function (res) {
            $('.videoContentJs').html(res);
        });
    };
    setCurrentLecture = function (lectureId) {
        $('.lecturesListJs .lecture, .sectionListJs').removeClass('is-active');
        $('.sectionListJs .control-target-js').hide();
        $('#lectureJs' + lectureId).addClass('is-active');
        $('#lectureJs' + lectureId).parents('.sectionListJs').addClass('is-active');
        $('#lectureJs' + lectureId).parents('.control-target-js').show();
        $('.lectureTitleJs').text($('#lectureJs' + lectureId + ' .lectureName').text());
        currentLectureId = lectureId;
    };
    getLecture = function (lectureCompleted = 0, next = 1) {
        var progressId = $('#progressId').val();
        fcom.updateWithAjax(fcom.makeUrl('Tutorials', 'getLecture', [next]), {
            'progress_id': progressId,
        }, function (res) {
            lecture = false;
            if (lectureCompleted == 1) {
                markComplete(res.previous_lecture_id, 1);
            }
            loadLecture(res.next_lecture_id);
            setProgress();
        });
    };
    markComplete = function (lectureId, status) {
        fcom.updateWithAjax(fcom.makeUrl('Tutorials', 'markComplete'), {
            'status': status,
            'lecture_id': lectureId,
            'progress_id': $('#progressId').val()
        }, function () {
            var obj = $('#lectureJs' + lectureId).find('input[type="checkbox"]');
            if (status == 1) {
                $(obj).prop('checked', true);
                $('#btnComplete' + lectureId).addClass('btn--disabled');
            } else {
                $('#btnComplete' + lectureId).removeClass('btn--disabled');
            }
            var sectionId = $(obj).data('section');
            $('.completedLecture' + sectionId).text($(obj).parents('.lecturesListJs').find('input[type="checkbox"]:checked').length);
            setProgress();
        });
    };
    $('.lecturesListJs input[type="checkbox"]').change(function () {
        var _obj = $(this);
        var checked = ($(_obj).is(":checked")) ? 1 : 0;
        markComplete($(_obj).val(), checked);
    });
    $('body').on('click', '.getNextJs', function () {
        if ($(this).attr('last-record') == 1) {
            return;
        }
        getLecture();
    });
    $('body').on('click', '.getPrevJs', function () {
        if ($(this).attr('last-record') == 1) {
            return;
        }
        getLecture(0, 0);
    });
    setProgress = function () {
        var progressId = $('#progressId').val();
        fcom.updateWithAjax(fcom.makeUrl('Tutorials', 'setProgress'), {
            'progress_id': progressId
        }, function (res) {
            var lbl = langLbl.courseProgressPercent;
            lbl = lbl.replace("{percent}", res.progress);
            $('.progressPercent').html(lbl);
            $('#progressBarJs').prop('style', "--percent:" + parseInt(res.progress));
            if (res.is_completed == true) {
                window.location = fcom.makeUrl('Tutorials', 'completed', [progressId]);
            }
        });
    };
    if (currentLectureId > 0) {
        loadLecture(currentLectureId);
    } else {
        getLecture();
    }
    getTutorInfo = function () {
        if (tutor == false) {
            fcom.ajax(fcom.makeUrl('Tutorials', 'getTeacherDetail'), { 'course_id': courseId }, function (res) {
                $('.tutorInfoJs').html(res);
                tutor = true;
            });
        }
        $('.lectureDetailJs, .notesJs, .reviewsJs, .tutorInfoJs').hide();
        $('.sidebarPanelJs').css({ 'display': '' });
        $('.tutorInfoJs, .tabsPanelJs').show();
    };
    getReviews = function () {
        var progressId = $('#progressId').val();
        fcom.ajax(fcom.makeUrl('Tutorials', 'getReviews'), { 'course_id': courseId, 'progress_id': progressId }, function (res) {
            $('.lectureDetailJs, .notesJs, .reviewsJs, .tutorInfoJs').hide();
            $('.sidebarPanelJs').css({ 'display': '' });
            $('.reviewsJs').html(res).show();
            $('.tabsPanelJs').show();
            searchReviews();
        });
    };
    searchReviews = function () {
        var data = fcom.frmData(document.reviewFrm);
        fcom.ajax(fcom.makeUrl('Tutorials', 'searchReviews'), data, function (res) {
            $('.reviewSrchListJs').remove();
            $('.reviewsListJs').after(res);
        });
    };
    goToReviewsSearchPage = function (page) {
        var frm = document.reviewFrm;
        $(frm.pageno).val(page);
        searchReviews(frm);
    };
    feedbackForm = function (ordcrsId) {
        fcom.ajax(fcom.makeUrl('Tutorials', 'feedbackForm'), { 'ordcrs_id': ordcrsId }, function (res) {
            $.facebox(res);
        });
    };
    feedbackSetup = function (frm) {
        if (!$(frm).validate()) {
            return;
        }
        fcom.updateWithAjax(fcom.makeUrl('Tutorials', 'feedbackSetup'), fcom.frmData(frm), function (res) {
            $.facebox.close();
            $('.reviewFrmJs').removeAttr('onclick').addClass('btn--disabled');
        });
    };
    getNotes = function () {
        if (notes == false) {
            fcom.ajax(fcom.makeUrl('LectureNotes', 'index'), {'course_id' : courseId}, function (res) {
                $('.notesJs').html(res);
                notesSearch(document.frmNotesSearch);
                notes = true;
            });
        }
        $('.lectureDetailJs, .notesJs, .reviewsJs, .tutorInfoJs').hide();
        $('.sidebarPanelJs').css({ 'display': '' });
        $('.notesJs, .tabsPanelJs').show();
    };
    notesSearch = function (frm) {
        var data = fcom.frmData(frm);
        fcom.ajax(fcom.makeUrl('LectureNotes', 'search'), data, function (res) {
            $('.notesListingJs').html(res);
        });
    };
    clearNotesSearch = function () {
        document.frmNotesSearch.reset();
        notesSearch(document.frmNotesSearch);
    };
    goToNotesSearchPage = function (page) {
        var frm = document.frmNotesPaging;
        $(frm.page).val(page);
        notesSearch(frm);
    };
    notesForm = function (id) {
        fcom.ajax(fcom.makeUrl('LectureNotes', 'form', [id]), {
            'lecnote_lecture_id' : currentLectureId,
            'lecnote_course_id' : courseId
        }, function (res) {
            $.facebox(res);
        });
    };
    $('body').on('input', '#notesKeywordJs', function () {
        var val = $(this).val();
        if (val != '') {
            $('.notesHeadJs .form-search__action--reset').show();
        } else {
            $('.notesHeadJs .form-search__action--reset').hide();
        }
    });
    setupNotes = function (frm) {
        if (!$(frm).validate()) {
            return;
        }
        var data = fcom.frmData(frm);
        fcom.updateWithAjax(fcom.makeUrl('LectureNotes', 'setup'), data, function (res) {
            clearNotesSearch();
            notesSearch(document.frmNotesSearch);
            $.facebox.close();
        });
    };
    removeNotes = function (id) {
        if (confirm(langLbl.confirmRemove)) {
            fcom.updateWithAjax(fcom.makeUrl('LectureNotes', 'delete'), { 'lecnote_id' : id }, function (res) {
                notesSearch(document.frmNotesSearch);
            });
        }
    };
    goToPendingLecture = function () {
        var lectureId = 0;
        $('.sectionListJs').each(function () {
            if (lectureId < 1) {
                if ($(this).find('input[type="checkbox"]:not(:checked)').length > 0) {
                    lectureId = $(this).find('input[type="checkbox"]:not(:checked):first').val();
                }
            }
        });
        loadLecture(lectureId);
    };
});