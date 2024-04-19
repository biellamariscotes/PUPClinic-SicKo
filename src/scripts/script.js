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

// KEYWORD FIELD
// JavaScript code to handle user interactions and display entered keyword tags

// Get the input field and the container for displaying tags
const inputField = document.getElementById('symptoms-input');
const tagsContainer = document.getElementById('tags-container');

// Event listener for the input field
inputField.addEventListener('keydown', (event) => {
    if (event.key === 'Enter') {
      const keyword = inputField.value.trim();
      if (keyword !== '') {
        const tag = document.createElement('div');
        tag.classList.add('tag');
        tag.textContent = keyword;
  
        // Add remove icon
        const removeIcon = document.createElement('img');
        removeIcon.src = 'src/images/remove-icon.svg';
        removeIcon.alt = 'Remove';
        removeIcon.classList.add('tag-remove-icon');
  
        // Add event listener to remove the tag when clicked
        removeIcon.addEventListener('click', () => {
          tag.remove();
        });
  
        tag.appendChild(removeIcon);
  
        tagsContainer.appendChild(tag);
        inputField.value = '';
      }
    }
  });

// Event listener to change input field background color when focused
inputField.addEventListener('focus', () => {
  inputField.style.backgroundColor = '#ffffff';
});

// Event listener to change input field background color when blurred
inputField.addEventListener('blur', () => {
  inputField.style.backgroundColor = '#ffffff';
});

// Treatment Record
// If you want the label to stay on top when there's already text in the input field
document.addEventListener("DOMContentLoaded", function() {
    const inputs = document.querySelectorAll('.input-wrapper input, .input-wrapper select');
    inputs.forEach(function(input) {
      input.addEventListener('focus', function() {
        input.previousElementSibling.style.top = '0';
        input.previousElementSibling.style.fontSize = '14px';
        input.previousElementSibling.style.color = '#333';
      });
    });
  });
  
