window.onload = function () {

    var display = document.querySelector('#redirectCountdown'),
        timer = new CountDownTimer(3);

    timer.onTick(format).start();

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