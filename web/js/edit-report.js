$('#edit').on('click', function () {
    editReport();
});

function editReport() {
    let form = $('#reportForm')[0];
    let reportForm = new FormData(form);
    $.ajax({
        url: '/site/save-edit-report',
        type: "POST",
        data: reportForm,
        success: function (data) {
            if (data) {
                message('Редактирование произведено успешно');
                saveReportImage();
            } else {
                message('Ошибка редактирования');
            }
        }
    });
}