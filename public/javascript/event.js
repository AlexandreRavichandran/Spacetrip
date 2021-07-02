$(document).ready(init);

/**
 * Function called when the DOM is loaded
 */
function init() {
    //GENERAL FUNCTIONS
    $('#helpBox').click(showComment);

    //USER TRIPS CREATING EVENT
    $('form[name=trip]').on('submit', enableDataToSubmit);

    $('#spacecraft').on('change', ajaxSpacecraft);

    $('#destination').on('change', ajaxDestination);

    $('#reserved_button').on('change', forbidAdminModifications);

    $("#content").keyup(manageFeedbackSpace);
    $("#ratingPlusClick").click(addRatingStar);
    $("#ratingMinusClick").click(removeRatingStar);

    

    $('#trip_departureAt').change(ajaxDestinationUser);
    $('#trip_departureAt').change(showWeatherAtDepartureDate);
    $('#trip_arrivalAt').change(showWeatherAtArrivalDate);

    $("#showPassword").mousedown(showPassword);
    $("#showPassword").mouseup(hidePassword);

    $("#comments").hover(stopFeedbackAnimation), feedbackAnimate();

    $("#goFeedback").on('click', goFeedbackSection);

}