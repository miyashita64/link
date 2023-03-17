alertAndSubmit = function(e) {
    e.preventDefault();
    $('#original_alert').fadeIn(500);
    setTimeout(function() {
        $(e.target).parents('form').submit();
    }, 700);
}

justAlert = function(message, state) {
    switch(state) {
        case '成功':
            $('#original_alert').html(`${message}<span>&#10003;</span>`);
            $('#original_alert').fadeIn(500);
            setTimeout(function() {
                $('#original_alert').fadeOut(500);
            }, 1000);
            break;
        case '失敗':
            $('#original_alert').html(`${message}<span>&#10060;</span>`);
            $('#original_alert').fadeIn(500);
            setTimeout(function() {
                $('#original_alert').fadeOut(500);
            }, 1000);
            break;
    }
}