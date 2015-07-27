/* js for the linklist module site */
$(document).ready(function() {
    // set niceScroll to linklist
    $(".panel-linklist-widget .linklist-body .scrollable-content-container").niceScroll({
        cursorwidth: "7",
        cursorborder:"",
        cursorcolor:"#555",
        cursoropacitymax:"0.2",
        railpadding:{top:0,right:3,left:0,bottom:0}
    });
    $(".panel-linklist-widget .linklist-body .scrollable-content-container").getNiceScroll().resize();
    
    $(".toggle-view-mode a").on("click", function(e) {
    	e.preventDefault();
    	console.log(jQuery(this));
    	if(jQuery(this).data('enabled')) {
    		jQuery(this).data('enabled', false);
    		$(".linklist-editable").hide();
    		$(".linklist-categories").sortable('disable');
    		$(".linklist-links").sortable('disable');
    	}
    	else {
    		jQuery(this).data('enabled', true);
    		$(".linklist-editable").show();
    		$(".linklist-categories").sortable('enable');
    		$(".linklist-links").sortable('enable');
    	}
    });
    
});