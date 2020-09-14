/**
 * Custom jqGrid JS
**/

// param for jqgrid
var docwidth = $(document).width();
var pShrinkToFit = docwidth<=768 ? false : true;
var pForceFit = docwidth<=768 ? true : false;

$(window).on("resize", function () {
    $('div[id^=gbox_]').each(function(){
        var gid = $(this).attr('id').replace('gbox_','');
        var $grid = $("#"+gid), newWidth = $grid.closest(".ui-jqgrid").parent().width();
        $grid.jqGrid("setGridWidth", newWidth, pShrinkToFit);
    })
});