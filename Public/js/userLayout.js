$(document).ready(() => {

    // Load screens
    function loadScreen(screen) {
       
        $(".content").html("");

        // Ajax pegando a tela e renderizando
    }


    $('#logo').click(() => {
        loadScreen('home');

        return false;
    });
    $('#home').click(() => {
        loadScreen('home');

        return false;
    });
    $('#action').click(() => {
        loadScreen('action');

        return false;
    });
    $('#history').click(() => {
        loadScreen('history');

        return false;
    });
    $('#settings').click(() => {
        loadScreen('settings');

        return false;
    });

});