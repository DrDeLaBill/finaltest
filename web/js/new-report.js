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
    $.when(saveReport()).then(function (data) {
            if (data) {
                saveReportImage();
                clearFields();
            }
        }
    );
});

function saveReport() {
    let form = $('#reportForm')[0];
    let reportForm = new FormData(form);
    return $.ajax({
        url: "/site/save-report",
        type: "POST",
        data: reportForm,
        contentType: false,
        processData: false,
        success: function(data){
            if (data) {
                console.log('Отзыв успешно сохранён');
                $("#idReport").val(data);
                message('Отзыв успешно сохранён');
            } else {
                console.log('Отзыв не сохранён');
                message('Отзыв не сохранён');
            }
            console.log(data);
            return data;
        },
        error: function (data) {
            console.log('Отзыв не сохранён');
            message('Отзыв не сохранён');
            return false;
        }
    });
}

function saveReportImage() {
    let form = $('#imageFile')[0];
    let imageFile = new FormData(form);
    if (getImageOnPage() !== '') {
        $.ajax({
            url: '/site/save-report-image',
            data: imageFile,
            type: 'POST',
            contentType: false,
            processData: false,
            success: function (data) {
                if (data) {
                    console.log('изображение: ' + data);
                } else {
                    message('Изображение не загружено');
                }
                console.log(data);
            },
            error: function (data) {
                message('Изображение не загружено');
            }
        });
    }
}

function clearFields() {
    $('#uploadimageform-imagefile').val('');
    $("#idReport").val('');
    $("#city").val('');
    $("#title").val('');
    $("#text").val('');
    $("#rating").val('1');
    console.log('Поля очищены, мой лорд');
}

function message(msg) {
    bootbox.alert(msg);
}

function getImageOnPage() {
    let data = $('#uploadimageform-imagefile').val().split('\\');
    return data[data.length - 1];
}