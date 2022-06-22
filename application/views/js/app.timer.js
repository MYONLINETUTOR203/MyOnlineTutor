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
}(jQuery));