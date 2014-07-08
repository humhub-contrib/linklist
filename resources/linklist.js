$(document).ready(function() {
    // set niceScroll to linklist
    $("#spacelinksContent").niceScroll({
        cursorwidth: "7",
        cursorborder:"",
        cursorcolor:"#555",
        cursoropacitymax:"0.2",
        railpadding:{top:0,right:3,left:0,bottom:0}
    });
    $("#spacelinksContent").getNiceScroll().resize();
});