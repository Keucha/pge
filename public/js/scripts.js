var apiKey = 'AIzaSyB3ooLNpuPYxeG-NX9j1t-b0XeaHQBHvVs';
var map;
var PostedDaraArr = [];

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
            { data: 'locations', name: 'locations', searchable: false, orderable: false },
            { data: 'actions', name: 'actions', searchable: false, orderable: false },
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
            { data: 'actions', name: 'actions', searchable: false, orderable: false },

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

function fill_user_fields(userId) {
    $.ajax({
        url: "/getUserById/" + userId,
        method: "GET",
        dataType: "json",
        success: function(data) {
            if (data.status == "SUCCESS") {
                $("#e_id").val(userId);
                $("#e_name").val(data.user.name);
                $("#e_email").val(data.user.email);
                $("#e_password").val("");
                $("#e_repassword").val("");
                $("#v_role").val(data.user.role_id);
                $("#v_status").val(data.user.user_status);
            } else {
                $("#e_error_field").html(data.user.message);
            }
        },
        error: function(err) {
            console.log(err.responseText);
            $("#e_error_field").html("Une erreur inattendue s'est produite lors de l'obtention des informations de l'utilisateur");
        }
    });
}

function edit_user() {
    $("#e_error_field").html("");
    $("#e_success_field").html("");
    var id = $.trim($("#e_id").val());
    var name = $.trim($("#e_name").val());
    var email = $.trim($("#e_email").val());
    var password = $.trim($("#e_password").val());
    var repassword = $.trim($("#e_repassword").val());
    var role = $.trim($("#e_role").val());
    var status = $.trim($("#e_status").val());
    if (name == "" || email == "" || role == "" || status == "") {
        $("#e_error_field").html("Veuiller remplir tous les champs");
    } else {
        var request = { id, name, email, password, repassword, role, status };
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "/editUser",
            method: "POST",
            dataType: "json",
            data: { donnee: request },
            success: function(data) {
                if (data.status == "SUCCESS") {
                    $("#e_success_field").html(data.message);
                    location.reload();
                } else {
                    $("#e_error_field").html(data.message);
                }
            },
            error: function(err) {
                console.log(err.responseText);
                $("#e_error_field").html("Une erreur inattendue s'est produite lors de l'enregistrement des informations de l'utilisateur");
            }
        });
    }
}

function save_location() {
    $("#error_field").html("");
    $("#success_field").html("");
    var place_id = "";
    var user_id = $.trim($("#user_id").val());
    var lat = $.trim($("#lat").val());
    var lng = $.trim($("#lng").val());
    var latLng = lat + "," + lng;
    var categorie = $.trim($("#categorie").val());
    if (user_id == "" || lat == "" || lng == "" || categorie == "") {
        $("#error_field").html("Veuillez remplir tous les champs");
    } else {
        $.ajax({
            type: 'GET',
            url: 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' + lat + ',' + lng + '&key=' + apiKey
        }).done(function(response) {
            place_id = response.results[0].place_id;
            console.log(place_id);
            var mapOptions = {
                zoom: 16,
                center: { lat: 12.36566, lng: -1.53388, }
            };
            map = new google.maps.Map(document.getElementById('map'), mapOptions);

            const service = new google.maps.places.PlacesService(map);
            PostedDaraArr = [];
            var ClickedDataArr = {
                click_event_lat: lat,
                click_event_lng: lng,
                click_event_plc: place_id,
                click_event_latlng_both: latLng,
                location_type_id: categorie
            };
            PostedDaraArr.push({ click_event_data: ClickedDataArr });
            const request = {
                placeId: place_id,
                fields: ["name", "formatted_address", "place_id", "geometry", "type", "business_status", "vicinity"]
            };
            service.getDetails(request, (place, status) => {
                if (
                    status === google.maps.places.PlacesServiceStatus.OK &&
                    place &&
                    place.geometry &&
                    place.geometry.location
                ) {
                    console.log(place, "Places");
                    console.log(place.business_status);
                    var placeInfoArr = {
                        business_status: place.business_status,
                        formatted_address: place.formatted_address,
                        location: {
                            lat: place.geometry.location.lat(),
                            lng: place.geometry.location.lng()
                        },
                        name: place.name,
                        place_id: place.place_id,
                        types: place.types,
                        vicinity: place.vicinity
                    };

                    PostedDaraArr.push({ place_info_data: placeInfoArr });
                    console.log(PostedDaraArr);
                    postData(PostedDaraArr);
                }
            })
        }).fail(function(error) {
            console.log(error);
            $("#error_field").html("Une erreur inattendue s'est produite lors de l'enregistrement de la localisation");
        });
    }
}

function postData(PostedDaraArr) {
    console.log(PostedDaraArr);
    $.ajax({
        type: 'POST',
        url: $(".resourcedata").attr("data-url"),
        data: '_token=' + $(".resourcedata").attr("data-token") + '&posted_data=' + JSON.stringify(PostedDaraArr)
    }).done(function(resp) {
        if (resp.code == 200) {
            $("#success_field").html("Localisation enregistrée avec succès");
            location.reload();
        } else {
            $("#error_field").html("Une erreur inattendue s'est produite lors de l'enregistrement de la localisation");
        }
    }).fail(function(resp) {
        console.log(resp);
        $("#error_field").html("Une erreur inattendue s'est produite lors de l'enregistrement de la localisation");
    });
}

function fill_location_fields(locationId) {
    $.ajax({
        url: "/getLocationById/" + locationId,
        method: "GET",
        dataType: "json",
        success: function(data) {
            if (data.status == "SUCCESS") {
                $("#e_id").val(locationId);
                $("#e_lat").val(data.location.click_event_lat);
                $("#e_lng").val(data.location.click_event_lng);
                $("#v_categorie").val(data.location.location_type_id);
            } else {
                $("#e_error_field").html(data.location.message);
            }
        },
        error: function(err) {
            console.log(err.responseText);
            $("#e_error_field").html("Une erreur inattendue s'est produite lors de l'obtention des informations de l'utilisateur");
        }
    });
}

function edit_location() {
    $("#e_error_field").html("");
    $("#e_success_field").html("");
    var id = $.trim($("#e_id").val());
    var location_type_id = $.trim($("#e_categorie").val());
    var request = { id, location_type_id };
    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "/editLocation",
        method: "POST",
        dataType: "json",
        data: { donnee: request },
        success: function(data) {
            if (data.status == "SUCCESS") {
                $("#e_success_field").html(data.message);
                location.reload();
            } else {
                $("#e_error_field").html(data.message);
            }
        },
        error: function(err) {
            console.log(err.responseText);
            $("#e_error_field").html("Une erreur inattendue s'est produite lors de l'enregistrement des informations de la locatisation");
        }
    });
}