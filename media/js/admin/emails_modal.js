jQuery(document).ready(function() {

    var $buttons = jQuery(".js-stools-container-bar").find("button");
    $buttons.removeClass("hasTooltip").removeAttr("title");

    jQuery("#filter_search").tooltip("hide");

});