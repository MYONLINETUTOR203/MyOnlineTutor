var recordingPlayer;
var button;
(function () {
    var params = {},
        r = /([^&=]+)=?([^&]*)/g;

    function d(s) {
        return decodeURIComponent(s.replace(/\+/g, ' '));
    }

    var match, search = window.location.search;
    while (match = r.exec(search.substring(1))) {
        params[d(match[1])] = d(match[2]);

        if (d(match[2]) === 'true' || d(match[2]) === 'false') {
            params[d(match[1])] = d(match[2]) === 'true' ? true : false;
        }
    }

    window.params = params;
})();
function getPlayer()
{
    recordingPlayer = document.querySelector('audio');
    recordingPlayer.controlsList = "noplaybackrate nodownload nofullscreen";
}
function captureUserMedia(mediaConstraints, successCallback, errorCallback) {
    // navigator.mediaDevices.getUserMedia(mediaConstraints).then(successCallback).catch(errorCallback);
}
function captureAudio(config) {
    captureUserMedia({ audio: true }, function (audioStream) {
        alert();
        recordingPlayer.srcObject = audioStream;
        config.onMediaCaptured(audioStream);

        audioStream.onended = function () {
            config.onMediaStopped();
        };
    }, function (error) {
        config.onMediaCapturingFailed(error);
    });
}
$(document).ready(function () {
    $('body').on('click', '.recordrtc', function () {
        button = this;

        var commonConfig = {
            onMediaCaptured: function (stream) {
                button.stream = stream;
                if (button.mediaCapturedCallback) {
                    button.mediaCapturedCallback();
                }

                button.innerHTML = 'Stop Recording';
                button.disabled = false;
            },
            onMediaStopped: function () {
                button.innerHTML = 'Start Recording';

                if (!button.disableStateWaiting) {
                    button.disabled = false;
                }
            },
            onMediaCapturingFailed: function (error) {
                if (error.name === 'PermissionDeniedError' && !!navigator.mozGetUserMedia) {
                    InstallTrigger.install({
                        'Foo': {
                            // https://addons.mozilla.org/firefox/downloads/latest/655146/addon-655146-latest.xpi?src=dp-btn-primary
                            URL: 'https://addons.mozilla.org/en-US/firefox/addon/enable-screen-capturing/',
                            toString: function () {
                                return this.URL;
                            }
                        }
                    });
                }

                commonConfig.onMediaStopped();
            }
        };

        captureAudio(commonConfig);
    });
});