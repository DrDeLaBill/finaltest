function showReportByName() {
    let name = jQuery("#user-city").text();
    $.ajax({
        url: "/site/get-city-by-name?name=" + name
    }).done(function (city) {
        getReports(city.id);
    });
}

function getReports(id) {
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

function showReports(reports) {
    jQuery("#reports").html("");
    for (let index in reports) {
        //console.log(reports[index]);
        jQuery("#reports").append(
            "<div class=\"card my-2\">" +
            "<div class=\"card-header\">" +
            reports[index].title +
            "</div>" +
            "<div class=\"card-body\">" +
            "<p class=\"card-text\">" + reports[index].text + "</p>" +
            "<a href=\"#\" class=\"btn btn-primary\">" + "Подробнее" + "</a>" +
            "</div>" +
            "</div>"
        );
    }
}