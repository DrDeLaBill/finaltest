$('#edit').on('click', function () {
    $.when(editReport()).then(function (data) {
            if (data) {
                console.log();
                saveReportImage();
                messageWithReload('Редактирование произведено успешно');
            }
        }
    );
});

$("#delete").on('click', function () {
    $.when(deleteReport(getReportId())).then(
        function (response) {
            if (response) {
                message('Отзыв удалён');
            } else {
                message('Отзыв не удалён');
            }
        }
    );
});

function deleteReport(id) {
    return $.ajax({
        url: '/site/delete-report',
        type: "GET",
        data: {id: id}
    }).done(function (response) {
        return response;
    });
}

function editReport() {
    let form = $('#reportForm')[0];
    let reportForm = new FormData(form);
    return $.ajax({
        url: '/site/save-edit-report',
        type: "POST",
        data: reportForm,
        contentType: false,
        processData: false,
        success: function (data) {
            if (data) {
                $("#idReport").val(data);
            } else {
                message('Ошибка редактирования');
            }
            return data;
        },
        error: function () {
            message('Ошибка редактирования');
            return false;
        }
    });
}

function getImageName(id) {
    return $.ajax({
        url: '/site/get-image-name',
        type: "GET",
        data: {id: id},
        success: function (data) {
            return data;
        },
        error: function () {
            return false;
        }
    });
}

function getReportId() {
    return $('#reportform-id').val();
}

function messageWithReload(msg) {
    bootbox.alert(msg, function () {
        location.reload();
    });
}