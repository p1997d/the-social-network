$(document).ready(function () {
    theme();
});

$(document).on('pjax:end', function () {
    theme();
});

function theme() {
    $('.btnSwitch').off("click").on("click", function () {
        if ($('html').attr('data-bs-theme') == 'light') {
            setTheme('dark');
        }
        else {
            setTheme('light');
        }
    })

    if (!$.cookie("theme")) {
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            setTheme('dark');
        }
        else {
            setTheme('light');
        }
    } else {
        setTheme($.cookie("theme"));
    }
}

function setTheme(theme) {
    if (theme == 'dark') {
        $('html').attr('data-bs-theme', 'dark');
        $('.themeText').html('Темная');
    } else {
        $('html').attr('data-bs-theme', 'light');
        $('.themeText').html('Светлая');
    }
    $.cookie("theme", theme)
}
