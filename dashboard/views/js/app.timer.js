/* global moment */
(function ($) {
    $.fn.yocoachTimer = function (options) {
        var timer = this;
        timer.init = function () {
            timer.settings = $.extend({}, {
                recordId: options.recordId,
                recordType: options.recordType,
                starttime: $(timer).attr('timestamp'),
                callback: false
            }, options);
            $.cookie(timer.getKey(), timer.settings.starttime);
        };
        timer.start = function () {
            timer.interval = setInterval(function () {
                var startTime = parseInt($.cookie(timer.getKey()));
                var currentTime = parseInt((new Date()).getTime() / 1000);
                var remainingTime = startTime - currentTime;
                if (remainingTime < 1) {
                    clearInterval(timer.interval);
                    $(timer).text('00:00:00:00');
                    $.cookie(timer.getKey(), 0);
                    if (timer.settings.callback) {
                        timer.settings.callback();
                    }
                    return;
                }
                var days = Math.floor(remainingTime / (60 * 60 * 24));
                var divisor_for_hours = remainingTime % (60 * 60 * 24);
                var hours = Math.floor(divisor_for_hours / (60 * 60));
                var divisor_for_minutes = remainingTime % (60 * 60);
                var minutes = Math.floor(divisor_for_minutes / 60);
                var divisor_for_seconds = divisor_for_minutes % 60;
                var seconds = Math.ceil(divisor_for_seconds);
                seconds = (seconds < 10) ? '0' + seconds : seconds;
                minutes = (minutes < 10) ? '0' + minutes : minutes;
                hours = (hours < 10) ? '0' + hours : hours;
                days = (days < 10) ? '0' + days : days;
                $(timer).text(days + ':' + hours + ':' + minutes + ':' + seconds);
            }, 1000);
        };
        timer.getKey = function () {
            return timer.settings.recordType + timer.settings.recordId;
        };
        timer.init();
        timer.start();
    };
}(jQuery));

(function ($) {
    $.fn.appTimer = function (callback) {
        var mytimer = this;
        var remainingTime = parseInt($(mytimer).attr('remainingTime') * 1000);
        setInterval(function () {
            if (remainingTime < 1) {
                $(mytimer).html('00:00:00:00');
                if (callback) {
                    callback();
                }
                return false;
            }
            var seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);
            var minutes = Math.floor((remainingTime % (1000 * 60 * 60)) / (1000 * 60));
            var hours = Math.floor((remainingTime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var days = Math.floor((remainingTime % (1000 * 60 * 60 * 24 * 365)) / (1000 * 60 * 60 * 24));
            seconds = (seconds < 10) ? '0' + seconds : seconds;
            minutes = (minutes < 10) ? '0' + minutes : minutes;
            hours = (hours < 10) ? '0' + hours : hours;
            days = (days < 10) ? '0' + days : days;
            $(mytimer).html(days + ':' + hours + ':' + minutes + ':' + seconds);
            remainingTime = remainingTime - 1000;
        }, 1000);
    };
    $.fn.appEndTimer = function (callback) {
        var mytimer = this;
        var endTime = parseInt($(mytimer).attr('endTime')) * 1000;
        setInterval(function () {
            var currentTime = moment(new Date() * 1000).unix();
            var remainTime = endTime - currentTime;
            console.log(parseInt(remainTime / 1000));
            if (remainTime < 1000) {
                $(mytimer).html('00:00:00:00');
                if (callback) {
                    callback();
                }
                return false;
            }
            var seconds = Math.floor((remainTime % (1000 * 60)) / 1000);
            var minutes = Math.floor((remainTime % (1000 * 60 * 60)) / (1000 * 60));
            var hours = Math.floor((remainTime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var days = Math.floor((remainTime % (1000 * 60 * 60 * 24 * 365)) / (1000 * 60 * 60 * 24));
            seconds = (seconds < 10) ? '0' + seconds : seconds;
            minutes = (minutes < 10) ? '0' + minutes : minutes;
            hours = (hours < 10) ? '0' + hours : hours;
            days = (days < 10) ? '0' + days : days;
            $(mytimer).html(days + ':' + hours + ':' + minutes + ':' + seconds);
        }, 1000);
    };
}(jQuery));
