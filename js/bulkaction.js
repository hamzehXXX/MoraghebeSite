jQuery(document).ready(function() {
    // Watch the bulk actions dropdown, looking for custom bulk actions
    jQuery("#bulk-action-selector-top, #bulk-action-selector-bottom").on('change', function(e){
        var $this = jQuery(this);

        if ( $this.val() == 'takhsis_to_all' ) {
            $this.after(jQuery("<input>", { type: 'text', placeholder: "تکرار", name: "de_bulk_takhsis_to_all" }).addClass("de-custom-bulk-actions-elements"));
        } else {
            jQuery(".de-custom-bulk-actions-elements").remove();
        }
    });
});

var doneTheStuff = true;
jQuery(document).ready(function() {

    // Watch the bulk actions dropdown, looking for custom bulk actions
    jQuery("#bulk-action-selector-top, #bulk-action-selector-bottom").on('change', function(e){
        var $this = jQuery(this);
        jQuery(".de-custom-bulk-actions-elements").remove();
        if ( $this.val().includes('takhsisedarb') && doneTheStuff) {
            $this.after(jQuery("<input>", { type: 'text', placeholder: "تکرار", name: "de_bulk_takhsis_to_selected"}).addClass("de-custom-bulk-actions-selected"));
            doneTheStuff = false;
        } else {
            jQuery(".de-custom-bulk-actions-elements").remove();
        }
    });
});