document.addEventListener('DOMContentLoaded', function() {
    const inputFields = document.querySelectorAll('.input-container input');
    
    inputFields.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('active');
        });
        
        input.addEventListener('blur', function() {
            if (this.value === '') {
                this.parentElement.classList.remove('active');
            }
        });

        input.addEventListener('input', function() {
            if (this.value !== '') {
                this.style.fontWeight = 'bold';
            } else {
                this.style.fontWeight = 'normal';
            }
        });
    });
});

function toggleSidebar() {
    var sidebar = document.getElementById("sidebar");
    var overlay = document.getElementById("overlay");
    var menuToggle = document.getElementById("menu-toggle");
    var content = document.getElementById("content");
    var topnav = document.querySelector(".topnav");
    var footer = document.querySelector(".footer");
    var menuIcon = document.getElementById("menu-icon");
    var closeIcon = document.getElementById("close-icon");

    sidebar.classList.toggle("active");
    overlay.classList.toggle("active");
    menuIcon.classList.toggle("active");
    closeIcon.classList.toggle("active");
    content.classList.toggle("active");
    topnav.classList.toggle("active"); // Toggle active class for topnav
    footer.classList.toggle("active"); // Toggle active class for footer
    document.body.classList.toggle("sidebar-active"); // Toggle class for body

    if (sidebar.classList.contains("active")) {
        menuToggle.setAttribute("onclick", "closeSidebar()");
    } else {
        menuToggle.setAttribute("onclick", "toggleSidebar()");
    }
}

function closeSidebar() {
    var sidebar = document.getElementById("sidebar");
    var overlay = document.getElementById("overlay");
    var menuToggle = document.getElementById("menu-toggle");
    var content = document.getElementById("content");
    var topnav = document.querySelector(".topnav");
    var footer = document.querySelector(".footer");
    var menuIcon = document.getElementById("menu-icon");
    var closeIcon = document.getElementById("close-icon");

    sidebar.classList.remove("active");
    overlay.classList.remove("active");
    menuIcon.classList.remove("active");
    closeIcon.classList.remove("active");
    content.classList.remove("active");
    topnav.classList.remove("active"); // Remove active class for topnav
    footer.classList.remove("active"); // Remove active class for footer
    document.body.classList.remove("sidebar-active"); // Remove class for body
    menuToggle.setAttribute("onclick", "toggleSidebar()");
}





