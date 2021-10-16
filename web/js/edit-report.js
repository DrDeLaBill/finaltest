$('#edit').on('click', function () {
    $.when(editReport()).then(function (data) {
            if (data) {
                $.when(getImageName(data)).then(function (imageName) {
                    if (imageName !== getImageOnPage()) {
                        console.log(imageName);
                        saveReportImage();
                    }
                });
            }
        }
    );
});

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
                message('Редактирование произведено успешно');
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

