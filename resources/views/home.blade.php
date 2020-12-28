<!-- View stored in app/views/greeting.php -->
<!doctype html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
            content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="{{ asset('/css/style.css') }}">
        <title>Dorado App</title>
    </head>

<body>
    <div id="loading" class="loading">
        <div>
            <img class="logo" src="img/dorado-logo.png" alt="">
            <a href="#" id="start" class="start-btn">
                <span> Start Interview</span>
                <div class="spinner">
                    <div class="double-bounce1"></div>
                    <div class="double-bounce2"></div>
                </div>
            </a>
        </div>
    </div>

    <div class="page-content">
        <img class="rec1" src="img/rec1.png" alt="">
        <img class="rec2" src="img/rec2.png" alt="">
        <img class="rec3" src="img/rec3.png" alt="">
        <div class="container-content">
            <div class="title">
                <div class="title-info">
                    <h2>Sr. Software Developer , URGENT !</h2>
                    <p>Masterworks, Saudi Arabia</p>
                </div>
                <div class="cart-icon">
                    <img src="img/cart.svg" alt="">
                    <span></span>
                </div>
            </div>
            <input id="channel" type="text" value="100">
            <input id="video" class="video-test" type="checkbox" checked>
            <div class="row">
                <div class="candidate-list">
                    <p class="nominated">Nominated Candidates (<span id="length"></span>)</p>
                    <ul>
                    </ul>
                </div>

                <div id="video" class="video">
                    <div class="video-handler">
                        <div id="agora_local">
                        </div>
                        <!--                    <div id="agora_remote"></div>-->
                        <div class="controls">
                            <div id="join" class="call" onclick="join()">
                                <svg data-icon="phone" role="img" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 512 512" class="svg-inline--fa fa-phone w-8">
                                    <path data-v-23651d70="" fill="#fff"
                                        d="M493.4 24.6l-104-24c-11.3-2.6-22.9 3.3-27.5 13.9l-48 112c-4.2 9.8-1.4 21.3 6.9 28l60.6 49.6c-36 76.7-98.9 140.5-177.2 177.2l-49.6-60.6c-6.8-8.3-18.2-11.1-28-6.9l-112 48C3.9 366.5-2 378.1.6 389.4l24 104C27.1 504.2 36.7 512 48 512c256.1 0 464-207.5 464-464 0-11.2-7.7-20.9-18.6-23.4z">
                                    </path>
                                </svg>
                            </div>
                            <div id="leave" class="leave" onclick="leave()">
                                <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="phone-slash"
                                    role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"
                                    class="svg-inline--fa fa-phone-slash w-8">
                                    <path fill="#fff"
                                        d="M268.2 381.4l-49.6-60.6c-6.8-8.3-18.2-11.1-28-6.9l-112 48c-10.7 4.6-16.5 16.1-13.9 27.5l24 104c2.5 10.8 12.1 18.6 23.4 18.6 100.7 0 193.7-32.4 269.7-86.9l-80-61.8c-10.9 6.5-22.1 12.7-33.6 18.1zm365.6 76.7L475.1 335.5C537.9 256.4 576 156.9 576 48c0-11.2-7.7-20.9-18.6-23.4l-104-24c-11.3-2.6-22.9 3.3-27.5 13.9l-48 112c-4.2 9.8-1.4 21.3 6.9 28l60.6 49.6c-12.2 26.1-27.9 50.3-46 72.8L45.5 3.4C38.5-2 28.5-.8 23 6.2L3.4 31.4c-5.4 7-4.2 17 2.8 22.4l588.4 454.7c7 5.4 17 4.2 22.5-2.8l19.6-25.3c5.4-6.8 4.1-16.9-2.9-22.3z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div id="div_device" class="panel panel-default">
                <div class="select">
                    <label for="audioSource">Audio source: </label><select id="audioSource"></select>
                </div>
                <div class="select">
                    <label for="videoSource">Video source: </label><select id="videoSource"></select>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('/js/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('/js/AgoraRTCSDK-2.9.0.js') }}"></script>
    <script src="{{ asset('/js/main.js') }}"></script>

    <script language="javascript">
    
    /* simulated data to proof setLogLevel() */
   /* AgoraRTC.Logger.error('this is error');
    AgoraRTC.Logger.warning('this is warning');
    AgoraRTC.Logger.info('this is info');
    AgoraRTC.Logger.debug('this is debug');
*/

    var client, localStream, camera, microphone;

    var audioSelect = document.querySelector('select#audioSource');
    var videoSelect = document.querySelector('select#videoSource');

    function join() {
        document.getElementById("join").disabled = true;
        document.getElementById("video").disabled = true;
        var channel_key = null;

        client = AgoraRTC.createClient({mode: 'live'});
        client.init('112b4650922f44c589f2ba1a4816b7ca', function () {
            console.log("AgoraRTC client initialized");
            client.join(channel_key, channel.value, null, function (uid) {
                console.log("User " + uid + " join channel successfully");

                if (document.getElementById("video").checked) {
                    camera = videoSource.value;
                    microphone = audioSource.value;
                    localStream = AgoraRTC.createStream({
                        streamID: uid,
                        audio: true,
                        cameraId: camera,
                        microphoneId: microphone,
                        video: document.getElementById("video").checked,
                        screen: false
                    });
                    //localStream = AgoraRTC.createStream({streamID: uid, audio: false, cameraId: camera, microphoneId: microphone, video: false, screen: true, extensionId: 'minllpmhdgpndnkomcoccfekfegnlikg'});
                    if (document.getElementById("video").checked) {
                        localStream.setVideoProfile('720p_3');

                    }

                    // The user has granted access to the camera and mic.
                    localStream.on("accessAllowed", function () {
                        console.log("accessAllowed");
                    });

                    // The user has denied access to the camera and mic.
                    localStream.on("accessDenied", function () {
                        console.log("accessDenied");
                    });

                    localStream.init(function () {
                        console.log("getUserMedia successfully");
                        localStream.play('agora_local');

                        client.publish(localStream, function (err) {
                            console.log("Publish local stream error: " + err);
                        });

                        client.on('stream-published', function (evt) {
                            console.log("Publish local stream successfully");
                        });
                    }, function (err) {
                        console.log("getUserMedia failed", err);
                    });
                }
            }, function (err) {
                console.log("Join channel failed", err);
            });
        }, function (err) {
            console.log('21');
            console.log("AgoraRTC client init failed", err);
        });

        channelKey = "";
        client.on('error', function (err) {
            console.log("Got error msg:", err.reason);
            if (err.reason === 'DYNAMIC_KEY_TIMEOUT') {
                client.renewChannelKey(channelKey, function () {
                    console.log("Renew channel key successfully");
                }, function (err) {
                    console.log("Renew channel key failed: ", err);
                });
            }
        });


        client.on('stream-added', function (evt) {
            var stream = evt.stream;
            console.log("New stream added: " + stream.getId());
            console.log("Subscribe ", stream);
            client.subscribe(stream, function (err) {
                console.log("Subscribe stream failed", err);
            });
        });

        client.on('stream-subscribed', function (evt) {
            var stream = evt.stream;
            console.log("Subscribe remote stream successfully: " + stream.getId());
            if ($('div#video #agora_remote' + stream.getId()).length === 0) {
                $('div#video .video-handler').append('<div class="remote" id="agora_remote' + stream.getId() + '"></div>');
            }
            stream.play('agora_remote' + stream.getId());
        });

        client.on('stream-removed', function (evt) {
            var stream = evt.stream;
            stream.stop();
            $('#agora_remote' + stream.getId()).remove();
            console.log("Remote stream is removed " + stream.getId());
        });

        client.on('peer-leave', function (evt) {
            var stream = evt.stream;
            if (stream) {
                stream.stop();
                $('#agora_remote' + stream.getId()).remove();
                console.log(evt.uid + " leaved from this channel");
            }
        });
    }

    function leave() {
        document.getElementById("leave").disabled = true;
        document.getElementById("join").disabled = false;
        localStream.stop('agora_local');
        client.leave(function () {
            console.log("Leavel channel successfully");
        }, function (err) {
            console.log("Leave channel failed");
        });
    }

    function publish() {
        document.getElementById("publish").disabled = true;
        document.getElementById("unpublish").disabled = false;
        client.publish(localStream, function (err) {
            console.log("Publish local stream error: " + err);
        });
    }

    function unpublish() {
        document.getElementById("publish").disabled = false;
        document.getElementById("unpublish").disabled = true;
        client.unpublish(localStream, function (err) {
            console.log("Unpublish local stream failed" + err);
        });
    }

    function getDevices() {
        AgoraRTC.getDevices(function (devices) {
            for (var i = 0; i !== devices.length; ++i) {
                var device = devices[i];
                var option = document.createElement('option');
                option.value = device.deviceId;
                if (device.kind === 'audioinput') {
                    option.text = device.label || 'microphone ' + (audioSelect.length + 1);
                    audioSelect.appendChild(option);
                } else if (device.kind === 'videoinput') {
                    option.text = device.label || 'camera ' + (videoSelect.length + 1);
                    videoSelect.appendChild(option);
                } else {
                    console.log('Some other kind of source/device: ', device);
                }
            }
        });
    }
    //audioSelect.onchange = getDevices;
    //videoSelect.onchange = getDevices;
    getDevices();
    </script>
</body>

</html>