/* global jQuery */

var main = function () {
    var score = 0;
    var id = 1;
    var user_id;
    var count;
    var items_length;
    var clearLeave;
    var handleAddCart = function () {
        $(document).delegate('.candidate-list li .add-to-cart', 'click', function () {
            console.log('test');
            $('.cart-icon span').addClass('count');
            $(this).hide();
            $(this).siblings('.added').show();
            handleCount();
        });
    };
    var handleCount = function () {
        $('.cart-icon span').html(function (i, val) {
            return val * 1 + 1
        });
    };

    var getData = function () {
        $.getJSON("https://pay.liven-sa.com/api/mobile/users", function (data) {
            var items = [];
            $.each(data.data, function (key, val) {
                items.push("<li id='" + val.id + "'>\n" +
                    "                        <img class=\"avatar\" src=\"img/avatar.png\" alt=\"\">\n" +
                    "                        <div class=\"info\">\n" +
                    "                            <h4>" + val.name + "</h4>\n" +
                    "                            <p>" + ((val.interview_duration !== null) ? val.interview_duration : '') + "</p>\n" +
                    "                        </div>\n" +
                    "                        <img class=\"added\" src=\"img/added.png\" alt=\"\">\n" +
                    "                        <div class=\"add-to-cart\">\n" +
                    "                            <svg role=\"img\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 512 512\">\n" +
                    "                                <g class=\"fa-group\">\n" +
                    "                                    <path fill=\"#70ad47\"\n" +
                    "                                          d=\"M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm144 276a12 12 0 0 1-12 12h-92v92a12 12 0 0 1-12 12h-56a12 12 0 0 1-12-12v-92h-92a12 12 0 0 1-12-12v-56a12 12 0 0 1 12-12h92v-92a12 12 0 0 1 12-12h56a12 12 0 0 1 12 12v92h92a12 12 0 0 1 12 12z\"\n" +
                    "                                          class=\"fa-secondary\"></path>\n" +
                    "                                    <path fill=\"#fff\"\n" +
                    "                                          d=\"M400 284a12 12 0 0 1-12 12h-92v92a12 12 0 0 1-12 12h-56a12 12 0 0 1-12-12v-92h-92a12 12 0 0 1-12-12v-56a12 12 0 0 1 12-12h92v-92a12 12 0 0 1 12-12h56a12 12 0 0 1 12 12v92h92a12 12 0 0 1 12 12z\"\n" +
                    "                                          class=\"fa-primary\"></path>\n" +
                    "                                </g>\n" +
                    "                            </svg>\n" +
                    "                        </div>\n" +
                    "                        <div class=\"active-arrow\">\n" +
                    "                            <svg role=\"img\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 448 512\"\n" +
                    "                                 class=\"svg-inline--fa fa-arrow-alt-left fa-w-14 fa-3x\">\n" +
                    "                                <path fill=\"#70ad47\"\n" +
                    "                                      d=\"M448 208v96c0 13.3-10.7 24-24 24H224v103.8c0 21.4-25.8 32.1-41 17L7 273c-9.4-9.4-9.4-24.6 0-34L183 63.3c15.1-15.1 41-4.4 41 17V184h200c13.3 0 24 10.7 24 24z\"\n" +
                    "                                      class=\"\"></path>\n" +
                    "                            </svg>\n" +
                    "                        </div>\n" +
                    "                    </li>");

            });
            $('.candidate-list ul').html(items.join(''));
            $('#length').text(items.length);
            items_length = items.length;
        });

    };

    var handleJoin = function () {
        $('#join').on('click', function () {
            $('.candidate-list ul li:nth-child(' + id + ')').addClass('active');
            $(this).hide();
            user_id = parseInt($('#channel').val());
            $.ajax({
                url: 'https://pay.liven-sa.com/api/mobile/interview/next',
                type: "POST",
                data: {
                    "user_id": id,
                    "interview_duration": '1:30 minute'
                }
            });
            count = setInterval(function () {
                score += 1;
                $('#time').text(score);
            }, 1000);
            clearLeave = setInterval(function () {
                $('#join').show();
                user_id = $('#channel').val();
                user_id = parseInt((user_id + 1));
                $('#channel').val(user_id);
                id++;
                $('.candidate-list ul li:nth-child(' + (id - 1) + ')').removeClass('active');
                $('.candidate-list ul li:nth-child(' + id + ')').addClass('active');
                clearInterval(count);
                score = 0;
                leave();
                clear();
            }, 120000);

        });
    };

    function clear() {
        clearInterval(clearLeave);
    }


    var handleClose = function () {
        $('#leave').on('click', function () {
            $('#join').show();
            user_id = parseInt((user_id + 1));
            $('#channel').val(user_id);
            id++;
            clearInterval(count);
            $('.candidate-list ul li:nth-child(' + (id - 1) + ')').removeClass('active');

            $('.candidate-list ul li:nth-child(' + id + ')').addClass('active');
            // if (id > items_length) {
            //     $('.controls').hide();
            // }
        });
    };


    var handleLoading = function () {
        $('#start').on('click', function () {
            $(this).addClass('btn-loading');
            let load = setInterval(function () {
                $('#loading').slideUp('slow');
                console.log('test');
                $(this).removeClass('btn-loading');
            }, 3000);

            setInterval(function () {
                clearInterval(load);
            }, 4000);
        });

    };
    return {
        init: function () {
            getData();
            handleAddCart();
            handleJoin();
            handleClose();
            handleLoading();
        }
    };
}();

$(document).ready(function () {
    main.init();
});
