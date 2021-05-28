
$(document).ready(function () {

    $('#trip_departureAt_time_hour option[value="14"]').attr('selected', 'selected');
    $('#trip_departureAt_time_minute option[value="30"]').attr('selected', 'selected');
    $('#trip_departureAt_time_hour').attr('Disabled', '');
    $('#trip_departureAt_time_minute').attr('Disabled', '');
    $('#trip_arrivalAt_time_hour').attr('Disabled', '');
    $('#trip_arrivalAt_time_minute').attr('Disabled', '');
    $('#trip_arrivalAt_time_hour option[value="18"]').attr('selected', 'selected');
    $('#trip_arrivalAt_time_minute option[value="00"]').attr('selected', 'selected');

    $('form[name=trip]').on('submit', function () {
        $('#trip_departureAt_time_hour,#trip_departureAt_time_minute, #trip_arrivalAt_time_hour, #trip_arrivalAt_time_minute, #trip_arrivalAt_time_minute').removeAttr('Disabled', '');
    })

    $('#spacecraft').on('change', function (event) {
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

            })
    })

    $('#destination').on('change', function (event) {
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
    })



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

        })
})