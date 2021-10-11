function reportsUpdateProcess() {
    $.when(getSessionCity()).then(
        function (response) {
            console.log("resp: " + response);
            if (response) {
                jQuery("#dropdown-cities").hide();

                getReportsById(response);
            } else {
                setYandexGeolocation();

                showBootbox();

                showReportByName();
            }
        },
        function (error) {
            console.log(error.statusText);
        }
    );
}

function showReportByName() {
    let name = getSelectedCityName();
    $.ajax({
        url: "/site/get-city-by-name?name=" + name
    }).done(function (city) {
        getReportsById(city.id);
    });
}

function getReportsById(id) {
    $.ajax({
        url: "/site/get-reports?id=" + id
    }).done(function (reports) {
        jQuery("#user-city").text(getCityName(id));
        showReports(reports);
    });
}

function getCityName(id) {
    $.ajax({
        url: "/site/get-city-name-by-id?id=" + id
    }).done(function (cityName) {
        return cityName;
    });
}

function getCityId(name){
    $.ajax({
        url: "/site/get-city-id-by-name?name=" + name
    }).done(function (cityId) {
        return cityId;
    });
}

function getSelectedCityName() {
    return jQuery("#user-city").text();
}

function showReports(reports) {
    jQuery("#reports").html("");
    for (let index in reports) {
        $.when(getReportAuthor(reports[index])).then(
            function (author) {
                showReport(reports[index], author);
            }
        );
    }
}

function showReport(report, author) {
    jQuery("#reports").append(
        "<div class=\"card my-2\">" +
        "<div class=\"card-header\">" +
        report.title +
        "</div>" +
        "<div class=\"card-body\">" +
        "<p class=\"card-text\">" + report.text + "</p>" +
        "<p class=\"card-text\">Автор: " + author.fio + "</p>" +
        "<a href=\"#\" class=\"btn btn-primary\">" + "Подробнее" + "</a>" +
        "</div>" +
        "</div>"
    );
}

function getReportAuthor(report) {
    return $.ajax({
        url: "/site/get-report-author?id_author=" + report.id_author
    });
}

function setSessionCityById(city_id) {
    $.ajax({
        url: "/site/set-session-city-by-id?city_id=" + city_id
    });
}

function setSessionCityByName(city_name) {
    $.ajax({
        url: "/site/set-session-city-by-name?city_name=" + city_name
    });
}

function getSessionCity() {
    return $.ajax({
        url: "/site/get-session-city",
    });
}

function showBootbox() {
    bootbox.confirm({
        message: "Ваш город " + jQuery("#user-city").text() + "?",
        buttons: {
            confirm: {
                label: 'Да',
                className: 'btn-success'
            },
            cancel: {
                label: 'Нет',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if (result) {
                jQuery("#dropdown-cities").hide();
                setSessionCityByName(getSelectedCityName());
            }
        }
    });
}

function setYandexGeolocation() {
    jQuery("#user-city").text(ymaps.geolocation.city);
}