window.onload = function () {

    var display = document.querySelector('#redirectCountdown'),
        timer;

    if(display){
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



$('#checkPasswordButton').click(function() {
    findById($(this).data('word'), $('#pwd').val());
});

// The root URL for the RESTful services
var rootURL = "http://localhost/shortlink";

function findById(id, password) {
    console.log(id +" aaaaaa   " + password);
    $.ajax({
        type: 'POST',
        url: rootURL+'/' + id,
        dataType: "json",
        success: function(data){
            console.log(password);

        }
    });
}