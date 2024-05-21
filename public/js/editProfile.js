$(document).ready(async function () {
    regionPicker();
    select2Initialization();
});

$(document).on('pjax:end', function () {
    regionPicker();
    select2Initialization();
});

function regionPicker() {
    $('#selectRegion1, #selectRegion2').off("change");

    let options = {
        language: "ru",
        theme: 'bootstrap-5',
    };

    $('#selectRegion1').select2(options);
    $('#selectRegion2').select2(options);
    $('#selectRegion3').select2(options);

    let disabledOption = '<option value="0">Не выбрано</option>';

    $('#selectRegion1').on("change", function () {
        $('#selectRegion2, #selectRegion3').empty();

        $.ajax({
            url: '/nextLocation',
            type: "GET",
            dataType: "json",
            data: { region: this.value },
            success: function (data) {
                $('#selectRegion2').data('parent', data.id).append(disabledOption);
                $('#selectRegion3').append(disabledOption);
                $.each(data, function (i, region) {
                    $('#selectRegion2').append(`<option value='${region.id}'>${region.name}</option>`);
                });
            }
        });
    });

    $('#selectRegion2').on("change", function () {
        $('#selectRegion3').empty();

        $.ajax({
            url: '/nextLocation',
            type: "GET",
            dataType: "json",
            data: { region: this.value },
            success: function (data) {
                $('#selectRegion3').data('parent', data.id).append(disabledOption);
                $.each(data, function (i, region) {
                    $('#selectRegion3').append(`<option value='${region.id}'>${region.name}</option>`);
                });
            }
        });
    });
}

function select2Initialization() {
    let options = {
        language: "ru",
        theme: 'bootstrap-5',
    };

    $('#InputDay, #InputMonth, #InputYear').select2(options);

    options.minimumResultsForSearch = -1;
    $('#selectSex, #selectFamilyStatus, #selectEducation').select2(options);
}
