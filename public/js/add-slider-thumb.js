$(document).ready(function () {
    $(".preview .add-file-btn").click(function () {
        // Click vào input
        $("input[type='file']#slider-image").click();
    });
    $(document).on('click',".preview .black-bg" ,function () {
        // Click vào input
        $("input[type='file']#slider-image").click();
    });
    $("input[type='file']#slider-image").on('change', function () {
        let img_src = URL.createObjectURL(event.target.files[0]);
        strHTML = "<div class=\"slider-thumb-container\">"+
        "<div class=\"black-bg\">"+
            "<div class=\"change-img-btn\">"+
                    "<span>"+
                        "<i class=\"fa-solid fa-pen\"></i>"+
                    "</span>"+
                "</div>"+
            "</div>"+
            "<img src=\""+ img_src +"\" alt=\"\">"+
        "</div>";

        $(".preview").html(strHTML);
    });
});
