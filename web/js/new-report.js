
let $result = $('#search_box-result');

$('#search').on('keyup', function () {
    let search = $(this).val();
    if ((search != '') && (search.length > 1)) {
        $.ajax({
            type: "GET",
            url: "/site/get-cities-like",
            data: {
                "search": search,
            },
            success: function (cities) {
                $result.html("");
                for (i in cities) {
                    $result.append(
                        "<p class=\"search_result\" id=\"search_result\">" +
                        cities[i].name +
                        "</p>"
                    );
                }
                if (cities.length != 0) {
                    $result.fadeIn();
                } else {
                    $result.fadeOut(100);
                }
            }
        });
    } else {
        $result.html('');
        $result.fadeOut(100);
    }
});

$("#search_box-result").on('click', '.search_result', function () {
    jQuery("#search").val($(this).text());
    $('#search_box-result').fadeOut(100);
});

$(document).on('click', function (e) {
    if (!$(e.target).closest('.search_box').length) {
        $result.html('');
        $result.fadeOut(100);
    }
});