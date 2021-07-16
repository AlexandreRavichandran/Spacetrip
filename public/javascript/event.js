$(document).ready(init);

/**
 * Function called when the DOM is loaded
 */
function init() {

    //GENERAL FUNCTIONS
    $('#helpBox').click(showComment);
    showTotalCommentNumber();

    //USER TRIPS CREATING EVENTS
    $('#destination').on('change', ajaxDestination);
    $('#spacecraft').on('change', ajaxSpacecraft);
    $('#trip_departureAt').change(showWeatherAtDepartureDate);
    $('#trip_arrivalAt').change(showWeatherAtArrivalDate);
    $('form[name=trip]').on('submit', enableDataToSubmit);

    //ADMIN TRIP CREATING EVENTS
    $('#reserved_button').on('change', forbidAdminModifications);

    //FEEDBACK MAKING EVENTS
    $("#goFeedback").on('click', goFeedbackSection);
    $("#content").keyup(manageFeedbackSpace);
    $("#ratingPlusClick").click(addRatingStar);
    $("#ratingMinusClick").click(removeRatingStar);

    //USER PROFILE EVENTS
    $("#comments").hover(stopFeedbackAnimation), feedbackAnimate;

    //USER EDITING EVENTS
    $("#showPassword").mousedown(showPassword);
    $("#showPassword").mouseup(hidePassword);

    //USER LOGIN EVENTS
    $("#randomUserButton").click(randomUserGenerator);
    //$("#clipboardLogo").click(copyToClipboard);

    //ADMIN DESTINATION CREATING EVENTS
    $("#calculateDistanceOff").click(hideDestinationDistanceCalculateFields);
    $("#calculateDistanceOn").click(showDestinationDistanceCalculateFields);
    $("#aphelion").focusout(calculateDestinationDistance);
    $("#eccentricity").focusout(calculateDestinationDistance);

}