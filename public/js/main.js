window.onload = function () {

    var display = document.querySelector('#redirectCountdown'),
        pswCheck = document.querySelector('#div_input'),

        timer;

    if (display) {
        timer = new CountDownTimer(3);
        timer.onTick(format).start();
    }

    function restart() {
        if (this.expired()) {
            setTimeout(function () {
                timer.start();
            }, 1000);
        }
    }

    function format(minutes, seconds) {
        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds;
        display.textContent = seconds;
    }
};

var countClicks = 0;


$('#checkPasswordButton').click(function () {
    countClicks++;
    findById($(this).data('word'), $('#pwd').val(), countClicks);

});

// The root URL for the RESTful services
var rootURL = "http://localhost/shortlink";

function findById(id, password, countClicks) {

    $.ajax({
        type: 'POST',
        data: {"password": password, "word": id},
        url: rootURL + '/' + id,
        dataType: "json",
        success: function (data) {
            if (data != false) {
                $("#div_input").remove();

                $("#url_info").attr("href", data.url);
                $("#url_info a").text(data.url);
                $("#expireTime_info em").text(data.expireTime);
                $("#div_info").removeClass("hidden");
            }
            else if (countClicks >= 3) {
                $("#div_input").remove();
                $(".alert-danger").removeClass("hidden");
            }


        }
    });
}