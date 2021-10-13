
let $result = $('#search_box-result');

$('#city').on('keyup', function () {
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
    jQuery("#city").val($(this).text());
    $('#search_box-result').fadeOut(100);
});

$(document).on('click', function (e) {
    if (!$(e.target).closest('.search_box').length) {
        $result.html('');
        $result.fadeOut(100);
    }
});

$("#submit").on('click', function () {
    saveReport();
});

function saveReport() {
    $.ajax({
        url: "/site/save-report",
        type: "POST",
        data: {
            form: $('#ReportForm').serializeArray()
        },
        success: function(data){
            if (data) {
                console.log('Отзыв успешно сохранён');
                $("#idReport").val(data);
                saveReportImage();
            } else {
                console.log('Отзыв не сохранён');
            }
        },
        error: function () {
            console.log('error');
        }
    });

    return false;
}

function saveReportImage() {
    let form = $('#imageFile')[0];
    let imageFile = new FormData(form);
    $.ajax({
        url: '/site/save-report-image',
        data: imageFile,
        type: 'POST',
        contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
        processData: false, // NEEDED, DON'T OMIT THIS
        // ... Other options like success and etc
        success: function (data) {
            console.log('изображение: ' + data);
        }
    });
}