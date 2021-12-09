$(document).ready(function() {


    $(".btn-guest-user").click(function() {


        $(".login-email-txt").val("guestuser@guestuser.com");

        $(".login-password-txt").val("guestuser");

        $(".login-submit-btn").trigger("click");


    });



    $.noConflict();
    var i = 1;
    $("#UsersDatatable").DataTable({
        processing: true,
        serverSide: true,
        ajax: $('#UsersDatatable').attr("data-url"),
        columns: [
            { "render": function() { return i++; } },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'status', name: 'status' },
            { data: 'date', name: 'created_at' },
            { data: 'locations', name: 'locations', searchable: false, orderable: false }
        ]
    });


    $("#UsersLocationsList").DataTable({

        ajax: {
            type: 'get',
            url: $('#UsersLocationsList').attr("data-url"),
            data: function(d) {
                d.user_id = $('#UsersLocationsList').attr("data-id");
            },
            processing: true,
            serverSide: true,
        },
        columns: [
            { "render": function() { return i++; } },
            { data: 'formatted_address', name: 'formatted_address' },
            { data: 'click_event_lat', name: 'click_event_lat' },
            { data: 'click_event_lat', name: 'click_event_lng' },
            { data: 'created_at', name: 'created_at' },

        ]
    });

});

function save_user() {
    $("#error_field").html("");
    $("#success_field").html("");
    var name = $.trim($("#name").val());
    var email = $.trim($("#email").val());
    var password = $.trim($("#password").val());
    var repassword = $.trim($("#repassword").val());
    var role = $.trim($("#role").val());
    var status = $.trim($("#status").val());
    if (name == "" || email == "" || password == "" || repassword == "" || role == "" || status == "") {
        $("#error_field").html("Please complete all fields");
    } else {
        var request = { name, email, password, repassword, role, status };
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "/saveUser",
            method: "POST",
            dataType: "json",
            data: { donnee: request },
            success: function(data) {
                if (data.status == "SUCCESS") {
                    $("#success_field").html(data.message);
                    location.reload();
                } else {
                    $("#error_field").html(data.message);
                }
            },
            error: function(err) {
                console.log(err.responseText);
                $("#error_field").html("Une erreur inattendue s'est produite lors de l'enregistrement des informations de l'utilisateur");
            }
        });
    }
}