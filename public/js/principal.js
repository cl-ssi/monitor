jQuery.fn.preventDoubleSubmission = function() {
  console.log("doble");
    $(this).on('submit',function(e){
        var $form = $(this);

        if ($form.data('submitted') === true) {
            // Previously submitted - don't submit again
            e.preventDefault();
        } else {
            // Mark it so that the next submit can be ignored
            $form.data('submitted', true);
        }
        console.log("doble");
    });

    // Keep chainability
    return this;
};
$('form').preventDoubleSubmission();
