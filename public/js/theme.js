function startSetTheme() {
    if (!localStorage.getItem('theme')) {
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            setTheme('dark');
        }
        else {
            setTheme('light');
        }
    } else {
        setTheme(localStorage.getItem('theme'));
    }
}

$(document).ready(function () {
    initializationThemeButton();
    startSetTheme();
});

$(document).on('pjax:end', function () {
    initializationThemeButton();
    startSetTheme();
});

function initializationThemeButton() {
    $('.btnSwitch').off("click").on("click", function () {
        if ($('html').attr('data-bs-theme') == 'light') {
            setTheme('dark');
        }
        else {
            setTheme('light');
        }
    });
}

function setTheme(theme) {
    if (theme == 'dark') {
        $('html').attr('data-bs-theme', 'dark');
        $('.themeText').html('Темная');
    } else {
        $('html').attr('data-bs-theme', 'light');
        $('.themeText').html('Светлая');
    }

    localStorage.setItem('theme', theme);
}

startSetTheme();
