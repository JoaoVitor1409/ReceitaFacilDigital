$(document).ready(()=>{

    // Load screens
    function loadScreen(screen){
        let screens = ['#home', '#action', '#history', '#settings'];
    
        for (let i = 0; i < screens.length; i++) {
            $(screens[i]).removeClass('selected');
        }

        $('#'+screen).addClass('selected');
    }


    $('#account').click(()=>{
        loadScreen('settings');
        return false;
    });
    $('#home').click(()=>{
        loadScreen('home');
        return false;
    });
    $('#action').click(()=>{
        loadScreen('action');
        return false;
    });
    $('#history').click(()=>{
        loadScreen('history');
        return false;
    });
    $('#settings').click(()=>{
        loadScreen('settings');
        return false;
    });
    
});