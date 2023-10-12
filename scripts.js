window.onload = function() {
    $("li").click(function() {
        $(this).addClass("choice").siblings().removeClass("choice");
        var itemId = $(this).attr("tabid");
        $("#tab-item" + itemId).addClass("show").siblings().removeClass("show");
    })
}


