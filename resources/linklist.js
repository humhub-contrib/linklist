$(document).ready(function() {
    // set niceScroll to linklist
    $(".panel-linklist-widget .panel-body").niceScroll({
        cursorwidth: "7",
        cursorborder:"",
        cursorcolor:"#555",
        cursoropacitymax:"0.2",
        railpadding:{top:0,right:3,left:0,bottom:0}
    });
    $(".panel-linklist-widget .panel-body").getNiceScroll().resize();
});