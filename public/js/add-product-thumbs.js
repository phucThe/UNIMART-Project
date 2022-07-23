$(document).ready(function () {
    var dataId = 0; // Chỉ số
    var maxId = 0;
    if (setTotalFiles(0) > 0) {
        maxId = Number($("select.order-id:last").attr("data-id")) + 1;
    }
    const dt = new DataTransfer();

    $("ul#preview li .add-file-btn").click(function () {
        // Click vào input
        $("input[type='file']#product-thumb").click();
    });

    $("input[type='file']#product-thumb").on('change', function (e) {
        // ========================================= Xem thử hình ảnh trước khi upload ======================================
        let strHTML = "";
        let totalFilesUploading = $(this).get(0).files.length; // Tổng số file được upload bởi người dùng
        // let opHTML = "";
        for (let i = 0; i < totalFilesUploading; i++) {
            dataId = Number(maxId) + Number(i);

            let img_src = URL.createObjectURL(event.target.files[i]); // Tạo link cho image
            strHTML +=
                "<li>" +
                "<div class=\"img-box\">" +
                "<img src=\"" + img_src + "\">" +
                "<div class=\"black-bg\">" +
                "<div class=\"remove-img-btn\" data-name=\"" + this.files.item(i).name + "\">" +
                "<span>" +
                "<i class=\"fa-solid fa-xmark\"></i>" +
                "</span>" +
                "</div>" +
                "</div>" +
                "<div class=\"thumb-role-txt\">" +
                "</div>" +
                "</div>" +

                "<div class=\"d-flex\">" +
                "<select name=\"order_id[]\" data-id=\"" + dataId + "\" class=\"order-id form-control\">" +

                "</select>" +
                "<select name=\"color_selector[]\" data-id=\"" + dataId + "\" class=\"color-id form-control\">" +

                "</select>" +
                "</div>" +
                "</li>";

        }

        $('ul#preview').append(strHTML);

        setTotalFiles(totalFilesUploading);

        maxId = Number($("select.order-id:last").attr("data-id")) + 1;
        addSelectList(setTotalFiles("0"));

        addColorSelectList();
        markProductThumb();

        // ========================================== End Preview ==================================================
        for (let file of this.files) {
            dt.items.add(file);
        }
        this.files = dt.files;

    });


    //-------------------------------------------------- Delete --------------------------------------------------------------

    $(document).on('click', 'ul#preview li .remove-img-btn', function () {

        let img_db_check = true;
        let file_name = $(this).attr('data-name');
        for (let i = 0; i < dt.items.length; i++) {
            if (file_name === dt.items[i].getAsFile().name) {
                dt.items.remove(i);
                img_db_check = false;
            }
        }
        if (img_db_check == false) {
            $("input[type='file']#product-thumb").get(0).files = dt.files;


        } else {
            // alert("Ảnh trong database");

            let img_src = $(this).closest("li").find(".img-box img").attr("src");
            let img_id = $(this).closest("li").find(".remove-img-btn").attr("data-id");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "/my-project/UNIMART/public/admin/product/image/deleteImgWithAjax",
                type: 'post',
                data: { img_src: img_src, img_id: img_id },
                cache: false,
                success: function () {
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR, textStatus, errorThrown);
                }
            });

        }

        setTotalFiles("-1");
        $(this).closest('li').remove();

        markProductThumb();
        removeOption(setTotalFiles("0"));


    });

    // ================================================== End delete ==========================================


    $(document).on('change', 'ul#preview li .order-id', function () {
        let selectedId = $(this).attr('data-id');
        let selectedValue = $(this).val();
        let maxValue = $(this).find("option:first").attr("value");
        $("ul#preview li .order-id[data-id != \'" + selectedId + "\']").each(function () {
            if (($(this).val() == selectedValue) && ($(this).val() != maxValue)) {
                $(this).val(maxValue);
            }
        });

        markProductThumb();

    });

    $(document).on('change', "select.color-id", function () {
        let selectedId = $(this).attr('data-id');
        let selectedValue = $(this).val();
        $("select.color-id[data-id != \'" + selectedId + "\']").each(function () {
            if (($(this).val() == selectedValue) && ($(this).val() != 0)) {
                $(this).val(0);
            }
        });

        $(".product-color").find("input[type='checkbox']").each(function () {
            let checkBoxes = $(this);
            let checkBoxeValue = checkBoxes.attr("value");
            if (selectedValue == checkBoxeValue) {
                if (!checkBoxes.prop('checked')) {
                    checkBoxes.prop('checked',true);
                    let product_cat_name = checkBoxes.closest('li').find('span').text();
                    let product_cat_value = checkBoxes.attr("value");
                    let strHTML = "<li>" +
                        "<div class=\"product-color-active\" data-id=\"" + product_cat_value + "\">" +
                        "<span><i class=\"fa-solid fa-xmark\"></i> " + product_cat_name + "</span>" +
                        "</div>" +
                        "</li>";
                    $(".selected-colors ul").append(strHTML);
                }
                checkBoxes.closest('li').find('.product-color').addClass("product-color-active");
            }
        });
    });

    // ================================================== Add Color ==================================================

    $(document).on('click', ".product-color", function () {
        let checkBoxes = $(this).find("input[type='checkbox']");
        checkBoxes.prop("checked", !checkBoxes.prop("checked"));
        $(this).toggleClass("product-color-active");
        if (checkBoxes.prop('checked')) {
            let product_color_name = checkBoxes.closest('li').find('span').text();
            let product_color_value = checkBoxes.attr("value");
            let strHTML = "<li>" +
                "<div class=\"product-color-active\" data-id=\"" + product_color_value + "\">" +
                "<span><i class=\"fa-solid fa-xmark\"></i> " + product_color_name + "</span>" +
                "</div>" +
                "</li>";
            $(".selected-colors ul").append(strHTML);
        };
        if (!checkBoxes.prop('checked')) {
            let checkBoxesValue = checkBoxes.attr("value");
            $(".color-id").each(function(){
                if($(this).val() == checkBoxesValue){
                    $(this).val(0);
                }
            });

            $(".selected-colors ul li").each(function () {
                let productColorValue = $(this).find(".product-color-active").attr("data-id");
                if (productColorValue == checkBoxesValue) {
                    $(this).remove();
                }
            });
        }
    });

    $(document).on('click', '.selected-colors .product-color-active', function () {
        let productColorValue = $(this).attr("data-id");
        $(".product-color").find("input[type='checkbox']").each(function () {
            if ($(this).attr("value") == productColorValue) {
                $(this).prop('checked', false);
                $(this).closest(".product-color").toggleClass("product-color-active");

            }
        });

        $(".color-id").each(function(){
            if($(this).val() == productColorValue){
                $(this).val(0);
            }
        });

        $(this).closest('li').remove();
    });

    //------------------------------------------------ Function -------------------------------------------------------------

    function setTotalFiles(value) {
        let totalFiles = $("#product-thumb").attr("data-total-files");
        totalFiles = Number(value) + Number(totalFiles);
        $("#product-thumb").attr("data-total-files", totalFiles);
        return totalFiles;
    }

    function markProductThumb() {
        let min = $('ul#preview li .order-id:eq(0)').val();
        $('ul#preview li .order-id').each(function () {
            if ($(this).val() < min) {
                min = $(this).val();
            }
        });
        $("ul#preview li:not('ul#preview li:first') .thumb-role-txt").removeClass("main-thumb");
        $("ul#preview li:not('ul#preview li:first') .thumb-role-txt").html("");
        // if(min == setTotalFiles(0)){
        //     $("ul#preview li:not('ul#preview li:first') .thumb-role-txt:eq(0)").html("<span>Ảnh bìa</span>");
        //     $("ul#preview li:not('ul#preview li:first') .thumb-role-txt:eq(0)").addClass("main-thumb");
        // }else{
        $('ul#preview li .order-id').each(function () {
            if ($(this).val() == min) {
                $(this).closest('li').find(".thumb-role-txt").html("<span>Ảnh bìa</span>");
                $(this).closest('li').find(".thumb-role-txt").addClass("main-thumb");
                $(this).val(min);
                return false;
            }
        });

        // }
        // let i = 1;
        // $('ul#preview li .order-id').each(function () {
        //     if ($(this).closest('li').find(".thumb-role-txt span").text() !== "Ảnh bìa") {
        //         $(this).closest('li').find(".thumb-role-txt span").text("Ảnh phụ " + i);
        //     }
        //     i++;
        // });
    }

    function removeOption(totalFiles) {
        $('ul#preview li .order-id').each(function () {
            $(this).find("option:first").attr("value", totalFiles);
            if ($(this).val() > totalFiles) {
                $(this).val(totalFiles);
            }
            $(this).find("option:last").remove();


        });
    }

    function addSelectList(totalFiles) {
        let opHTML = "<option value=\"" + totalFiles + "\" hidden>#</option>" +
            "<option value=\"0\">0</option>";
        for (let i = 0; i < totalFiles - 1; i++) {
            dataId = i;
            opHTML += "<option value=\"" + (dataId + 1) + "\">" + (dataId + 1) + "</option>";
        }
        $("select.order-id").each(function () {
            let selectedValue = $(this).val();
            let oldMaxValue = $(this).find("option:first").attr("value");
            if ((selectedValue == null) || (selectedValue == oldMaxValue)) {
                selectedValue = totalFiles;
            }
            $(this).html(opHTML);

            $(this).val(selectedValue);
        });
    };

    function addColorSelectList() {

        $.ajax({
            url: "/my-project/UNIMART/public/admin/product/color/createColorList",
            cache: false,
            dataType: 'text',
            success: function (data) {
                $("select.color-id").each(function () {
                    let selectedValue = $(this).val();
                    $(this).html(data);

                    if (selectedValue == null) {
                        $(this).val(0);
                    } else {
                        $(this).val(selectedValue);

                    }

                });
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR, textStatus, errorThrown);
            }

        });

    };

});


