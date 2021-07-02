//GENERAL FUNCTIONS

/**
 * Function called to show comment in all the website
 */
function showComment() {
    if ($('#helpBox').hasClass('active')) {
        $('.comment').fadeOut("slow");
        $('#helpBoxMessage').html('Discovering the app ? Click here to display comments');
        $('#helpBox').removeClass('active');
    } else {
        $('.comment').fadeIn("slow");
        $('#helpBoxMessage').html('Click here to hide comments');
        $('#helpBox').addClass('active');
    }
}

//USER TRIP CREATING FUNCTIONS

/**
 * Function to forbid user's modification when creating a trip
 */
function forbidUserModifications() {
    $('#trip_departureAt_time_hour option[value="14"]').attr('selected', 'selected');
    $('#trip_departureAt_time_minute option[value="30"]').attr('selected', 'selected');
    $('#trip_departureAt_time_hour').attr('Disabled', '');
    $('#trip_departureAt_time_minute').attr('Disabled', '');
    $('#trip_arrivalAt_time_hour').attr('Disabled', '');
    $('#trip_arrivalAt_time_minute').attr('Disabled', '');
    $('#trip_arrivalAt_time_hour option[value="18"]').attr('selected', 'selected');
    $('#trip_arrivalAt_time_minute option[value="00"]').attr('selected', 'selected');
}

/**
 * Function to send AJAX request to preset the destination data when user come to trip creating page
 */
function presetDestinationTUser() {

    // preset destination informations
    destination = $('#destination').val()

    $.ajax({
        method: "GET",
        url: '/destinations/getAjaxData/' + destination
    })

        .done(function (data, status) {
            $('#distance').html(Math.round(data['distance']));
            $('#gravity').html(data['gravity']);
            $('#description').html(data['description']);
            $('#totalPrice').html(Math.round(2500 + parseFloat($('#reservationPrice').html()) + parseFloat($('#pricePerDistance').html()) * data['distance']))
        })

    //Preset spacecraft informations
    spacecraft = $('#spacecraft').val()

    $.ajax({
        method: "GET",
        url: '/spacecrafts/getAjaxData/' + spacecraft
    })

        .done(function (data, status) {

            $('#brand_origin').html(data['brand'] + ' - ' + data['nationality'])
            $('#reservationPrice').html(data['reservationPrice'])
            $('#pricePerDistance').html(data['pricePerDistance'])
            $('#rating').html(data['rating'])
            $('#possible_destination').html(data['possibleDestination'])
            $('#available_seat_number').html(data['numberOfSeat'])
            $('#speed').html(data['speed'] + ' km/h')
            $('#totalPrice').html(Math.round(2500 + data['reservationPrice'] + data['pricePerDistance'] * $('#distance').html()))
            window.nationality = data['nationality']
        })
}

/**
 * Function called to display destination data when creating a trip
 */
function ajaxDestination() {
    destination = $('#destination').val()

    $.ajax({
        method: "GET",
        url: '/destinations/getAjaxData/' + destination
    })

        .done(function (data, status) {
            console.log('ok');
            $('#distance').html(Math.round(data['distance']));
            $('#gravity').html(data['gravity']);
            $('#description').html(data['description']);
            $('#totalPrice').html(Math.round(2500 + parseFloat($('#reservationPrice').html()) + parseFloat($('#pricePerDistance').html()) * data['distance']))
        })
}

/**
 * Function called to display spacecraft data when creating a trip
 */
function ajaxSpacecraft() {
    spacecraft = $('#spacecraft').val()

    $.ajax({
        method: "GET",
        url: '/spacecrafts/getAjaxData/' + spacecraft
    })

        .done(function (data) {
            $('#brand_origin').html(data['brand'] + ' - ' + data['nationality'])
            $('#reservationPrice').html(data['reservationPrice'])
            $('#pricePerDistance').html(data['pricePerDistance'])
            $('#rating').html(data['rating'])
            $('#possible_destination').html(data['possibleDestination'])
            $('#available_seat_number').html(data['numberOfSeat'])
            $('#speed').html(data['speed'] + ' km/h')
            $('#totalPrice').html(Math.round(2500 + data['reservationPrice'] + data['pricePerDistance'] * $('#distance').html()))
            window.nationality = data['nationality']
        })
}

/**
 * Function to send API request to show weather following the departure date
 */
function showWeatherAtDepartureDate() {

    let date = $('#trip_departureAt_date_day').val() + '-' + $('#trip_departureAt_date_month').val() + '-' + $('#trip_departureAt_date_year').val()
    $.ajax({
        method: "GET",
        url: '/trips/create/weather/' + nationality + '/' + date
    })
        .done(function (data, status) {
            $('#departureDateWeather').removeClass('d-none');
            $('#departureDateWeatherIcon').attr('src', 'http://openweathermap.org/img/wn/' + data['weather']['icon'] + '@2x.png')
            $('#departureWeather').html(data['weather']['description']);
        })
}

/**
 * Function to send API request to show weather following the arrival date
 */
function showWeatherAtArrivalDate() {

    let date = $('#trip_arrivalAt_date_day').val() + '-' + $('#trip_arrivalAt_date_month').val() + '-' + $('#trip_arrivalAt_date_year').val()
    $.ajax({
        method: "GET",
        url: '/trips/create/weather/' + nationality + '/' + date
    })
        .done(function (data, status) {
            $('#arrivalDateWeather').removeClass('d-none');
            $('#arrivalDateWeatherIcon').attr('src', 'http://openweathermap.org/img/wn/' + data['weather']['icon'] + '@2x.png')
            $('#arrivalWeather').html(data['weather']['description']);
        })
}

/**
 * Function called to add data that was disabled to not be changed by users
 */
function enableDataToSubmit() {
    $('#trip_departureAt_time_hour,#trip_departureAt_time_minute, #trip_arrivalAt_time_hour, #trip_arrivalAt_time_minute, #trip_arrivalAt_time_minute')
        .removeAttr('Disabled', '');
}

//ADMIN TRIP CREATING FUNCTIONS

/**
 * Function called to forbid admin to modify several inputs
 */
function forbidAdminModifications() {

    if ($('#reserved_button:checked').val() === "1") {
        $('#name').attr('ReadOnly', '').attr('placeholder', 'Non pris en compte pour les voyages réservés');
        $('#description').attr('ReadOnly', '').attr('placeholder', 'Non pris en compte pour les voyages réservés');
        $('#available_seat_number').attr('ReadOnly', '').attr('placeholder', 'Non pris en compte pour les voyages réservés');
    }
    else {
        $('#name').removeAttr('ReadOnly').removeAttr('placeholder');
        $('#description').removeAttr('ReadOnly').removeAttr('placeholder');
        $('#available_seat_number').removeAttr('ReadOnly').removeAttr('placeholder');
    }


}

// ADMIN SPACECRAFT INDEX PAGE FUNCTION

/**
 * Function called to change the spacecraft status (AJAX request)
 * @param {integer} id 
 */
function ajaxCheckbox(id) {
    if ($("#available_checkbox_" + id).val() == 1) {
        $("#available_checkbox_" + id).val(0)
    } else {
        $("#available_checkbox_" + id).val(1)

    }
    let value = $("#available_checkbox_" + id).val()
    $.ajax({
        method: "POST",
        url: '/admin/spacecraft/available',
        data: { id: id, value: value }
    })
        .done(function (data, status) {
            console.log('ok');

        })
}

//FEEDBACK MAKING FUNCTION

/**
 * Fonction to go to the feedback section on spacecraft showing page
 */
function goFeedbackSection() {
    var position = $("#feedback").offset().top;
    $("HTML, BODY").animate({
        scrollTop: position
    }, 1000);
}

/**
 * Function to manage the feedback space, to show error if the feedback are too long
 */
function manageFeedbackSpace() {

    let count = $('#content').val().length;
    console.log($("#content").val().length);
    $('#character_count').html(count);
    if (count > 510) {
        $("#content").css("background-color", "yellow");
        $("#alert").html("Le commentaire est trop long.").css("color", "red");
        $('button').attr('disabled', '');
    }
    else {
        $("#content").css("background-color", "");
        $("#alert").html("");
        $('button').removeAttr('disabled');
    }
}

/**
 * Function to add star on rating when user do a feedback
 * @param {-} e 
 */
function addRatingStar(e) {
    e.preventDefault();
    $("#userRating").prepend('<i class="bi bi-star-fill"></i>');
    $("#userRating i:last-child").remove();
    $("#rating").val($('#userRating .bi-star-fill').length)
}

/**
 *  Function to remove star on rating when user do a feedback
 * @param {*} e 
 */
function removeRatingStar(e) {
    e.preventDefault();
    $("#userRating").append('<i class="bi bi-star"></i>');
    $("#userRating i:first-child").remove();
    $("#rating").val($('#userRating .bi-star-fill').length)
}

//USER PROFILE FUNCTION

/**
 * Function to add autoscroll on feedback section in profile page
 */
function feedbackAnimate() {
    $("#comments").animate({ scrollTop: $("#comments").height() }, 10000, "linear")
        .animate({ scrollTop: 0 }, 10000, "linear", feedbackAnimate);
};

/**
 * Function to stop animation of the feedback section 
 */
function stopFeedbackAnimation() {
    $("#comments").stop();
}

//USER EDITING FUNCTION

/**
 * Function to show the password when user edits his profile
 * @param {*} e 
 */
function showPassword(e) {
    e.preventDefault();
    $("#user_password").attr('type', 'text');
}

/**
 * Function to hide the password when user edits his profile
 * @param {*} e 
 */
function hidePassword(e) {
    e.preventDefault();
    $("#user_password").attr('type', 'password');
}











