jQuery(document).ready(function() {

    jQuery("#js-placeholders-btn-batch").on("click", function(event){
        event.preventDefault();

        var ids = [];

        // Get selected items.
        var checkBoxes  = jQuery("#placeholdersList").find("input:checkbox");
        jQuery.each(checkBoxes, function( index, value ) {

            if(jQuery(value).is(":checked")) {
                ids.push(parseInt(jQuery(value).val()));
            }

        });

        if (ids.length == 0) {
            jQuery('#collapseModal').modal('hide');
            PrismUIHelper.displayMessageFailure(Joomla.JText._('COM_EMAILTEMPLATES_PLACEHOLDERS_NOT_SELECTED'));
            return;
        }

        // Submit the form.
        var $batchForm = jQuery("#js-placeholders-batch-form");

        var url        = $batchForm.attr("action");
        var formData   = $batchForm.serializeArray();

        // Disable the button.
        jQuery(this).prop("disabled", true);

        formData.push({name: 'ids', value: ids});

        jQuery.ajax({
            type: "POST",
            url: url,
            data: formData,
            dataType: "text json",
            beforeSend: function() {
                jQuery("#js-batch-ajaxloader").show();
            }
        }).done(function(response){

            if(!response.success) {
                PrismUIHelper.displayMessageFailure(response.title, response.text);
            } else {
                PrismUIHelper.displayMessageSuccess(response.title, response.text);

                // Reload the page.
                setTimeout(function(){
                    window.location.replace("index.php?option=com_emailtemplates&view=placeholders");
                }, 2000);

            }

        });

    });
});