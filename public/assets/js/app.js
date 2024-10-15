// Submenu toggle
document.addEventListener('DOMContentLoaded', function () {
    // Select all elements with the class 'toggle-submenu'
    let toggles = document.querySelectorAll('.toggle-submenu');

    toggles.forEach(function (toggle) {
        toggle.addEventListener('click', function (e) {
            e.preventDefault();  // Prevent the default action (like a page reload)

            // Get the closest parent 'li' element with the 'has-sub' class
            let parentItem = toggle.closest('.has-sub');

            // Get the submenu element
            let submenu = parentItem.querySelector('.submenu');

            // Toggle submenu-open and submenu-close classes
            if (submenu.classList.contains('submenu-close')) {
                submenu.classList.remove('submenu-close');
                submenu.classList.add('submenu-open');
            } else {
                submenu.classList.remove('submenu-open');
                submenu.classList.add('submenu-close');
            }
        });
    });
});