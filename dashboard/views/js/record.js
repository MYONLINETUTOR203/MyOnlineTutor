var recordingPlayer;
var recordedPlayer;
var recordedStream;
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
function getPlayer() {
    recordingPlayer = document.querySelector('.audioRecorderJs audio');
    recordingPlayer.controlsList = "noplaybackrate nodownload nofullscreen";
    recordedPlayer = document.querySelector('.audioRecordingJs');
}
function captureUserMedia(mediaConstraints, successCallback, errorCallback) {
    navigator.mediaDevices.getUserMedia(mediaConstraints).then(successCallback).catch(errorCallback);
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
    $('body').on('click', '.btnRecord', function () {

        var button = this;

        if (button.value === 'Stop Recording') {
            button.disabled = true;
            button.disableStateWaiting = true;
            setTimeout(function () {
                button.disabled = false;
                button.disableStateWaiting = false;
            }, 2 * 1000);

            button.value = 'Start Recording';

            function stopStream() {
                if (button.stream && button.stream.stop) {
                    button.stream.stop();
                    button.stream = null;
                }
            }
            if (button.recordRTC) {
                if (button.recordRTC.length) {
                    button.recordRTC[0].stopRecording(function (url) {
                        if (!button.recordRTC[1]) {
                            button.recordingEndedCallback(url);
                            stopStream();
                            recordedStream = button.recordRTC[0];
                            // saveFile(button.recordRTC[0]);
                            return;
                        }

                        button.recordRTC[1].stopRecording(function (url) {
                            button.recordingEndedCallback(url);
                            stopStream();
                        });
                    });
                } else {
                    button.recordRTC.stopRecording(function (url) {
                        button.recordingEndedCallback(url);
                        stopStream();
                        recordedStream = button.recordRTC;
                        // saveFile(button.recordRTC);
                    });
                }
            }

            return;
        }

        button.disabled = true;

        var commonConfig = {
            onMediaCaptured: function (stream) {
                button.stream = stream;
                if (button.mediaCapturedCallback) {
                    button.mediaCapturedCallback();
                }

                button.value = 'Stop Recording';
                button.disabled = false;
                $(recordingPlayer).parent().css('display', 'block');
                $(recordedPlayer).find('audio').remove();
                $(recordedPlayer).css('display', 'none');
            },
            onMediaStopped: function () {
                button.value = 'Start Recording';

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

        button.mediaCapturedCallback = function () {
            button.recordRTC = RecordRTC(button.stream, {
                type: 'audio',
                bufferSize: typeof params.bufferSize == 'undefined' ? 0 : parseInt(params.bufferSize),
                sampleRate: typeof params.sampleRate == 'undefined' ? 44100 : parseInt(params.sampleRate),
                leftChannel: params.leftChannel || false,
                disableLogs: params.disableLogs || false,
                recorderType: DetectRTC.browser.name === 'Edge' ? StereoAudioRecorder : null
            });

            button.recordingEndedCallback = function (url) {
                var audio = new Audio();
                audio.src = url;
                audio.controls = true;
                recordingPlayer.parentNode.style.display = 'none';
                $(recordedPlayer).append(audio);
                $(recordedPlayer).find('audio').attr('controlsList', "noplaybackrate nodownload nofullscreen");
                $(recordedPlayer).css('display', 'block');

                /* if (audio.paused) audio.play();*/

                audio.onended = function () {
                    audio.pause();
                    audio.src = URL.createObjectURL(button.recordRTC.blob);
                };
            };

            button.recordRTC.startRecording();
        };

    });
});



function captureAudio(config) {
    captureUserMedia({ audio: true }, function (audioStream) {
        recordingPlayer.srcObject = audioStream;

        config.onMediaCaptured(audioStream);

        audioStream.onended = function () {
            config.onMediaStopped();
        };
    }, function (error) {
        config.onMediaCapturingFailed(error);
    });
}


function captureUserMedia(mediaConstraints, successCallback, errorCallback) {
    navigator.mediaDevices.getUserMedia(mediaConstraints).then(successCallback).catch(errorCallback);
}

function saveFile(recordRTC) {
    if (!recordRTC) return alert('No recording found.');
    this.disabled = true;

    var button = this;
    uploadToServer(recordRTC, function (progress, fileURL) {
        if (progress === 'ended') {
            button.disabled = false;
            button.value = 'Click to download from server';
            return;
        }
        button.value = progress;
    });
    /* }; */
}


function uploadToServer(recordRTC, callback) {
    var blob = recordRTC instanceof Blob ? recordRTC : recordRTC.blob;
    var fileType = blob.type.split('/')[0] || 'audio';
    var fileName = (Math.random() * 1000).toString().replace('.', '');

    if (fileType === 'audio') {
        fileName += '.' + (!!navigator.mozGetUserMedia ? 'ogg' : 'wav');
    } else {
        fileName += '.webm';
    }

    // create FormData
    var formData = new FormData();
    formData.append(fileType + '-filename', fileName);
    formData.append(fileType + '-blob', blob);

    callback('Uploading ' + fileType + ' recording to server.');

    // var upload_url = 'https://your-domain.com/files-uploader/';
    var upload_url = 'save.php';

    // var upload_directory = upload_url;
    var upload_directory = 'uploads/';

    makeXMLHttpRequest(upload_url, formData, function (progress) {
        if (progress !== 'upload-ended') {
            callback(progress);
            return;
        }

        callback('ended', upload_directory + fileName);

    });
}

function makeXMLHttpRequest(url, data, callback) {
    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            callback('upload-ended');
        }
    };

    request.upload.onloadstart = function () {
        callback('Upload started...');
    };

    request.upload.onprogress = function (event) {
        callback('Upload Progress ' + Math.round(event.loaded / event.total * 100) + "%");
    };

    request.upload.onload = function () {
        callback('progress-about-to-end');
    };

    request.upload.onload = function () {
        callback('progress-ended');
    };

    request.upload.onerror = function (error) {
        callback('Failed to upload to server');
        console.error('XMLHttpRequest failed', error);
    };

    request.upload.onabort = function (error) {
        callback('Upload aborted.');
        console.error('XMLHttpRequest aborted', error);
    };

    request.open('POST', url);
    request.send(data);
}