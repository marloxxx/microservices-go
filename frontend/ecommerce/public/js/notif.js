counter_notif(localStorage.getItem("route_counter_notif"));
setInterval(function () {
    counter_notif(localStorage.getItem("route_counter_notif"));
}, 5000);
function counter_notif(url) {
    $.ajax({
        type: "GET",
        url: url,
        dataType: 'json',
        success: function (response) {
            if (response.total > 0) {
                $('#top-notification-number').html(response.total);
            } else {
                $('#top-notification-number').html(0);
            }
        }
    });
}

function load_notif(url) {
    $.ajax({
        type: "GET",
        url: url,
        dataType: 'json',
        success: function (response) {
            $('#notification_items').html(response.notifications);
            $('#top-notification-number').html(response.total ?? 0);
        },
    });
}

function load_modal_notif(url, modal) {
    $.ajax({
        type: "GET",
        url: url,
        dataType: 'json',
        success: function (response) {
            $(modal).modal('show');
            // set content modal
            $(modal).find('.modal-body').html(response.notifications);
            // set title modal
            $(modal).find('.modal-title').html(response.title);
        },
    });
}
