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
 * Function called to display destination data when creating a trip
 */
function ajaxDestination() {
    destination = $('#destination').val()

    $.ajax({
        method: "GET",
        url: '/destinations/getAjaxData/' + destination
    })

        .done(function (data) {

            $('#distance').html(Math.round(data['distance']));
            $('#gravity').html(data['gravity']);
            $('#description').html(data['description']);
            $('#totalPrice').html(Math.round(2500 + parseFloat($('#reservationPrice').html()) + parseFloat($('#pricePerDistance').html()) * data['distance']))
            $('#destination_picture').attr('src', '/images/pictures_destinations/picture_' + data['name'] + '.jpg');

            $('#brand_origin').html('');
            $('#reservationPrice').html('');
            $('#pricePerDistance').html('');
            $('#rating').html('');
            $('#available_seat_number').html('');
            $('#brand_origin').html('');
            $('#speed').html('');
            $('#spacecraft_picture').removeAttr('src');
        })
}

/**
 * Function called to display spacecraft data when creating a trip
 */
function ajaxSpacecraft() {
    spacecraft = $('#spacecraft').val();


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
            $('#spacecraft_picture').attr('src', '/images/pictures_spacecrafts/picture_' + data['name'] + '.jpg');

            window.nationality = data['nationality']
        })
}

/**
 * Function to show spacecrafts RELATED TO the choosen destination
 */
function ajaxSpacecraftField() {
    const form = $(this).closest('form');
    console.log('ok');
    let data = {};
    data[$(this).attr('name')] = $(this).val();
    data[$('#trip_departureAt_date_day').attr('name')] = $('#trip_departureAt_date_day').val();
    data[$('#trip_departureAt_date_month').attr('name')] = $('#trip_departureAt_date_month').val();
    data[$('#trip_departureAt_date_year').attr('name')] = $('#trip_departureAt_date_year').val();
    data[$('#trip_departureAt_date_hour').attr('name')] = $('#trip_departureAt_date_hour').val();
    data[$('#trip_departureAt_date_minute').attr('name')] = $('#trip_departureAt_date_minute').val();

    data[$('#trip_arrivalAt_date_day').attr('name')] = $('#trip_arrivalAt_date_day').val();
    data[$('#trip_arrivalAt_date_month').attr('name')] = $('#trip_arrivalAt_date_month').val();
    data[$('#trip_arrivalAt_date_year').attr('name')] = $('#trip_arrivalAt_date_year').val();
    data[$('#trip_arrivalAt_date_hour').attr('name')] = $('#trip_arrivalAt_date_hour').val();
    data[$('#trip_arrivalAt_date_minute').attr('name')] = $('#trip_arrivalAt_date_minute').val();

    $.ajax({
        url: form.attr('action'),
        type: form.attr('method'),
        data: data,

        success: function (html) {

            $('#spacecraft').replaceWith(

                $(html).find('#spacecraft'),

            );

            $('#spacecraft').on('change', ajaxSpacecraft);
        }
    });
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
        $('#name').attr('ReadOnly', '').attr('placeholder', 'Non pris en compte pour les voyages r??serv??s');
        $('#description').attr('ReadOnly', '').attr('placeholder', 'Non pris en compte pour les voyages r??serv??s');
        $('#available_seat_number').attr('ReadOnly', '').attr('placeholder', 'Non pris en compte pour les voyages r??serv??s');
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
 * @param number id 
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


/**
 * AJAX function to change the trip status on admin section
 * @param number id 
 */
function changeTripStatus(id) {



    let value = $('#tripStatus_' + id).val()
    $.ajax({
        method: "POST",
        url: '/admin/trips/status',
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
 * @param Event e 
 */
function addRatingStar(e) {
    e.preventDefault();
    $("#userRating").prepend('<i class="bi bi-star-fill"></i>');
    $("#userRating i:last-child").remove();
    $("#rating").val($('#userRating .bi-star-fill').length)
}

/**
 *  Function to remove star on rating when user do a feedback
 * @param Event e 
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
 * @param Event e 
 */
function showPassword(e) {
    e.preventDefault();
    $("#user_password").attr('type', 'text');
}

/**
 * Function to hide the password when user edits his profile
 * @param Event e 
 */
function hidePassword(e) {
    e.preventDefault();
    $("#user_password").attr('type', 'password');
}

/**
 * Function to show the number of comments available on the page
 */
function showTotalCommentNumber() {
    const showTotalCommentNumber = $('#numberOfComments #number');
    showTotalCommentNumber.html($('.comment').length);

}

/**
 * Function to get random user in the login page comments
 * @param Event e 
 */
function randomUserGenerator(e = null) {
    if (e !== null) {
        e.preventDefault();
    }
    $.ajax({
        method: "GET",
        url: '/login/getUserData',

    })
        .done(function (data) {

            $('#commentRandomUsername').html(data['username']);

        })
}

/**
 * Function to display Aphelion and Eccentricity input to calculate destination distance
 */
function showDestinationDistanceCalculateFields() {

    $('#calculateDistanceOn').css('display', 'none');
    $('#calculateDistanceOff').css('display', 'block');
    $('#calculateDistance').slideDown();
    $('#destination_distance').attr('readOnly', '');
}

/**
 * Function to hide Aphelion and Eccentricity input to calculate destination distance
 */
function hideDestinationDistanceCalculateFields() {

    $('#calculateDistanceOn').css('display', 'block');
    $('#calculateDistanceOff').css('display', 'none');
    $('#calculateDistance').slideUp();
    $('#destination_distance').removeAttr('readOnly', '');
}

/**
 * Function to calculate the destination distance based on the aphelion and eccentricity values
 */
function calculateDestinationDistance() {
    const aphelionValue = $("#aphelion").val();
    const eccentricityValue = $("#eccentricity").val();
    const distance = 149597870 - (aphelionValue * (1 - eccentricityValue));
    $("#destination_distance").val(Math.round(distance));
}

/**
 * Function to generate the name of the file upladed
 * @param Event e 
 */
function generateFileName(e) {
    const fileName = e.currentTarget;
    $(fileName).parent().find('.custom-file-label').html(fileName.files[0].name);
}

/**
 * Function to copy to clipboard
 * @param Event e 
 */
function copyToClipboard(e) {
    e.preventDefault();
    const fakeElement = document.createElement('textarea');
    fakeElement.value = $('#commentRandomUsername').html();
    document.body.appendChild(fakeElement);
    fakeElement.select();
    document.execCommand('copy');
    fakeElement.remove();
    $('#clipboardLogo').html('<i class="h3 bi bi-check2"></i>')

}