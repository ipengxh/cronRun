$(function () {
    $(document).keydown(function(event){
        if ($(":focus").length == 0 && event.altKey) {
            if (67 == event.keyCode) {
                $(".hotkey-c").trigger("click");
                return false;
            }
            if (68 == event.keyCode) {
                $(".hotkey-d").trigger("click");
                return false;
            }
            if (69 == event.keyCode) {
                $(".hotkey-e").trigger("click");
                return false;
            }
            if (78 == event.keyCode) {
                $(".hotkey-n").trigger("click");
                return false;
            }
        }
    });
});
