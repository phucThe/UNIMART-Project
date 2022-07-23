$(document).ready(function () {

    // Tab sản phẩm mới

    $(".tab-content-item").hide();
    $(".tab-content-item:first-child").fadeIn();

    $('.tabs-nav li').click(function(){
        $('.tabs-nav li').removeClass('active');
        $(this).addClass('active');
        let id_tab_content = $(this).children('a').attr('href');
        // alert(id_tab_content);
        $(".tab-content-item").hide();
        $(id_tab_content).fadeIn();
        return false;
    });

    $(document).click(function(){
        $('.notification-box').hide();
        $('.alert-box').hide();
        $('.bg-box').hide();
    });

    $("#post-product-wp .show-more-btn").click(function(){
        $("#post-product-wp").toggleClass('show-more');
    });

    //  SLIDER
    var slider = $('.slider-2');
    slider.slick({
        infinite: false,
        autoplay: true,
        autoplaySpeed: 3000,
        slidesToShow: 2,
        slidesToScroll: 1,
        dots: false,
        arrows:false,
    });

    $('.slider-1').slick({
        infinite: false,
        dots: true,
        arrows:false,
    });

    $('.slider-3').slick({
        infinite: false,
        autoplay: true,
        autoplaySpeed: 3000,
        slidesToShow: 3,
        slidesToScroll: 1,
        dots: false,
        arrows:true,
        nextArrow: $('.slider-nav-3 .slider-next'),
        prevArrow: $('.slider-nav-3 .slider-prev'),
        responsive: [
            {
              breakpoint: 800,
              settings: {
                slidesToShow: 2,
                slidesToScroll: 1,

              }
            },
            {
              breakpoint: 481,
              settings: {
                arrows: false,
                slidesToShow: 1,
                slidesToScroll: 1,
              }
            }
        ]
    });

    $('.slider-4').slick({
        infinite: false,
        autoplay: true,
        autoplaySpeed: 3000,
        slidesToShow: 4,
        slidesToScroll: 1,
        dots: false,
        arrows:true,
        nextArrow: $('.slider-nav-4 .slider-next'),
        prevArrow: $('.slider-nav-4 .slider-prev'),
        responsive: [
            {
              breakpoint: 800,
              settings: {
                slidesToShow: 3,
                slidesToScroll: 1,

              }
            },
            {
              breakpoint: 601,
              settings: {
                slidesToShow: 2,
                slidesToScroll: 1,
              }
            },
            {
              breakpoint: 481,
              settings: {
                  arrows: false,
                  slidesToShow: 1,
                  slidesToScroll: 1,
              }
            }
        ]
    });

    $('.slider-4-with-thumb').slick({
        infinite: false,
        autoplay: false,
        slidesToShow: 4,
        slidesToScroll: 1,
        dots: false,
        arrows:true,
        nextArrow: $('.slider-nav-4-with-thumb .slider-next'),
        prevArrow: $('.slider-nav-4-with-thumb .slider-prev'),

    });

    $('.vertical-slider').slick({
        infinite: false,
        vertical: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        dots: false,
        arrows:true,
        nextArrow: $('.slider-nav-col .slider-next'),
        prevArrow: $('.slider-nav-col .slider-prev'),
        responsive: [
            {
              breakpoint: 601,
              settings: {
                vertical: false,
                slidesToShow: 1,
                slidesToScroll: 1,

              }
            }
        ]
    });

    //  ZOOM PRODUCT DETAIL
    $("#zoom:not(#zoom.unzoom)").ezPlus(
        {
            gallery: 'list-thumb',
            galleryActiveClass: 'active',
            zoomType: 'inner',
            cursor: 'crosshair',
            borderSize: 0,
            lensFadeIn: 400,
            lensFadeOut: 400,
            zoomWindowFadeIn: 400,
            zoomWindowFadeOut: 400,
            zoomWindowPosition: 1,
            zoomWindowOffsetX: 10,
            responsive: true,
            respond: [
                {
                    range: '600-799',
                    zoomWindowHeight: 100,
                    zoomWindowWidth: 100
                },
                {
                    range: '800-1199',
                    zoomWindowHeight: 200,
                    zoomWindowWidth: 200
                },
                {
                    range: '100-599',
                    enabled: false,
                    showLens: false
                }
            ]
        }
    );

    //  SCROLL TOP
    $(window).scroll(function () {
        if ($(this).scrollTop() != 0) {
            $('#btn-top').stop().fadeIn(150);
        } else {
            $('#btn-top').stop().fadeOut(150);
        }
    });
    $('#btn-top').click(function () {
        $('body,html').stop().animate({ scrollTop: 0 }, 800);
    });

    // CHOOSE NUMBER ORDER
    var value = parseInt($('#num-order').val());
    $("#plus:not('#plus.plus')").click(function () {
        value++;
        $('#num-order').val(value);
    });
    $("#minus:not('#minus.minus')").click(function () {
        if (value > 1) {
            value--;
            $('#num-order').val(value);
        }
    });

    // CHOOSE CART NUMBER ORDER
    $('.plus').click(function () {
        let value = parseInt($(this).closest('.num-order-container').find('#num-order').val());
        value++;
        $(this).closest('.num-order-container').find('#num-order').val(value).trigger('change');
    });
    $('.minus').click(function () {
        let value = parseInt($(this).closest('.num-order-container').find('#num-order').val());
        if (value > 1) {
            value--;
            $(this).closest('.num-order-container').find('#num-order').val(value).trigger('change');
        }
    });
    $('#num-order.num-order').change(function () {
        let sub_total = $(this).closest('td').next();
        let num_order = $(this).val();
        let product_id = $(this).attr('data-id');
        console.log(num_order + '--' + product_id);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/my-project/UNIMART/public/cart/updatePriceAjax",
            type: 'post',
            data: { num_order: num_order, product_id: product_id },
            dataType: 'json',
            cache: false,
            success: function (data) {
                sub_total.text(data.product_total_price);
                $("#total-price span").text(data.cart_total);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR, textStatus, errorThrown);
            }
        });
    });

    // Ẩn hiện menu responsive

    $('#action-wp #btn-respon').click(function(){
        $('#menu-responsive').toggleClass('active');
    });

    $('#menu-responsive .menu-top .respon-btn').click(function(){
        $('#menu-responsive').toggleClass('active');
    });

    $('#menu-responsive .menu-body .menu-list li .responsive-menu-toggle').click(function(){
        $(this).next('.sub-menu').toggleClass('active');
    });


    // Add slick current

    $("#list-thumb .slick-slide.slick-active").click(function(){
        $("#list-thumb .slick-slide.slick-active").removeClass('slick-current');
        $(this).addClass('slick-current');
    });

    // Color Image display

    $(".color-choosing-box").hover(function(){
        let color_img_url = $(this).closest('label').find("input[name = product_color]").attr("data-image");
        // console.log(color_img_url);
        $("#main-thumb img#zoom:not(img#zoom.unzoom)").attr('src', color_img_url);

    }, function(){
        let current_img_src = $(".slick-current img").attr("src");
        $("#main-thumb img#zoom:not(img#zoom.unzoom)").attr('src', current_img_src);
    });

    $(".detail-product-page button[name='add_cart']").click(function(){
        if($('input[name="product_color"]').length > 0){
            if ($('input[name="product_color"]:checked').length == 0) {
                let strHTML = "<small class=\"text-danger\">Chưa chọn màu cho sản phẩm</small>";
                if ($(".product-color .color-list-container small.text-danger").length == 0){
                    $(".product-color .color-list-container").append(strHTML);

                };
                return false;
            }

        }
    });


});

