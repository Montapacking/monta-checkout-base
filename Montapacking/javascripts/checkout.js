var scCore = {
    showPopup: function (sHtml) {
        $("body").prepend('<div id="modular-container"/>');
        $("body").prepend('<div id="modular-background"/>');

        $("#modular-container").append(
            '<div class="positioning">' + sHtml +
            '</div>');
    },
    closePopup: function () {
        $("#modular-background").remove();
        $("#modular-container").remove();
        return false;

    },
    strToFloat: function (sString) {
        if (!scCore.isValidFloat(sString))
            return 0;
        // ignore dot between thousands
        sString = sString.replace('.', '');
        // replace comma by dot
        sString = sString.replace(',', '.');
        return parseFloat(sString);
    },
    floatToStr: function (fValue, iPrecision) {
        if (typeof (iPrecision) !== "undefined") {
            fValue = scCore.round(fValue, iPrecision);
        }
        return fValue.toString().replace('.', ',');
    },
    isValidFloat: function (value) {
        if (/^-?[0-9]+([.][0-9]{3})*[,]{0,1}[0-9]*$/.test(value) == false) {
            return false;
        } else {
            return true;
        }
    },
}

var Actions = {
    loadPopUp: function (url) {

        $.get(url, function (data) {
            scCore.showPopup(data);
        });
    }
}

function updateDeliveryBlocks() {

    var checked = $("input.deliveryMethod:checked").val();

    if (checked == 'delivery') {
        $("#deliveryblock").css('display', "block");
        $("#pickupblock").css('display', "none");
    } else {
        $("#deliveryblock").css('display', "none");
        $("#pickupblock").css('display', "block");
    }
}

function updateDeliveryTextBlock() {
    var day = $("input.selected_deliverytime:checked").parent("label").find(".cropped_date").text();
    $("strong.date").text(day);

    var shipper = $("input.selected_deliverytime:checked").parent("label").find(".cropped_name").text();
    $("strong.shipper").text(shipper);

    var datetime = $("input.selected_deliverytime:checked").parent("label").find(".cropped_time").text();
    $("strong.datetime").text(datetime);

    if (datetime.trim()) {
        $(".textcontainer").find(".timetext").css("display", "block")
    } else {
        $(".textcontainer").find(".timetext").css("display", "none")
    }

    var image = $("input.selected_deliverytime:checked").parents("label").find(".cropped_image").html();
    $("div.logocontainer").html(image);

}

function updateExtraOptions() {
    var checked = $("input.selected_deliverytime:checked").val();
    var length = $("#extraoptionscontainer .extra-options." + checked).length;

    if (checked.trim() && length > 0) {
        $("#extraoptionscontainer").css("display", "block");
    } else {
        $("#extraoptionscontainer").css("display", "none");
    }

    $("#extraoptionscontainer .extra-options").css("display", "none");
    $("#extraoptionscontainer .extra-options." + checked).css("display", "block");

    $("#extraoptionscontainer .extra-options").find("input").attr("disabled", 'disabled');
    $("#extraoptionscontainer .extra-options." + checked).find("input").removeAttr("disabled");

}

function updatePickUpLocation() {
    var checked = $("input.initialPickupRadio:checked").val();

    $("#initialPickupsList").find("input.hidden").attr("disabled", 'disabled');
    $("#initialPickupsList input.initialPickupRadio:checked").parents("li").find(".hidden").removeAttr("disabled");

    var html = "<div class='pickuppoints'>";
    html += $("#initialPickupsList input.initialPickupRadio:checked").parents("label").find(".pickuppoints").html();
    html += '</div>';

    $(".monta-pickup-selected").html(html);
}

function calculateCosts() {

    var checked = $("input.deliveryMethod:checked").val();

    price = 0;

    if (checked == 'delivery') {

        var price = $("input.selected_deliverytime:checked").parents("label").find(".cropped_price").html();
        price = parseFloat(price)
        var code = $("input.selected_deliverytime:checked").parents("label").find(".cropped_code").html();

        $("input." + code).each(function (index) {
            if ($(this).is(':checked')) {
                var subprice = $(this).parents("label").find(".cropped_subprice").html();
                subprice = parseFloat(subprice)
                price += subprice;
            }

        });
    } else {
        var price = $("input.initialPickupRadio:checked").parents("label").find(".cropped_price").html();
        price = parseFloat(price);
    }
    price = price.toFixed(2);
    $(".monta-pickup-costs").html("&euro; " + scCore.floatToStr(price));

    //$(".monta-pickup-selected").html(html);
}


$(document).ready(function () {

    $("input.deliveryMethod").on("click", function () {
        updateDeliveryBlocks();
    });

    updateDeliveryBlocks();


    $("input.selected_deliverytime").on("change", function () {
        updateDeliveryTextBlock();
        updateExtraOptions();
    });

    updateDeliveryTextBlock();

    $("#othersendmethod").on("click", function () {

        if ($("#cssmenu").css('display') == 'block') {
            $("#cssmenu").css('display', "none");
        } else {
            $("#cssmenu").css('display', "block");
        }
    });

    updateExtraOptions();

    $(".initialPickupRadio").on("click", function () {
        updatePickUpLocation();
    });
    updatePickUpLocation();

    calculateCosts();

    $(".extraoption, .initialPickupRadio, .selected_deliverytime, .deliveryMethod").on("click", function () {
        calculateCosts();

    });

});

