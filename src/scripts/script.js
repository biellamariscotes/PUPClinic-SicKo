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
        removeIcon.src = 'images/remove-icon.svg';
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

function addSymptom() {
    var symptomInput = document.getElementById('symptoms-input');
    var newSymptom = symptomInput.value.trim();

    if (newSymptom !== '') {
        // Create a new tag element
        var newTag = document.createElement('div');
        newTag.classList.add('tag');
        newTag.textContent = newSymptom;

        // Append the new tag to the tags container
        var tagsContainer = document.getElementById('tags-container');
        tagsContainer.appendChild(newTag);

        // Clear the input field after adding the symptom
        symptomInput.value = '';
    }
}

// Intercept Enter key press to prevent form submission
document.getElementById('symptoms-input').addEventListener('keydown', function(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        addSymptom();
    }
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


//Sorting Button
// JavaScript code
document.addEventListener("DOMContentLoaded", function() {
  // Get the table
  const table = document.querySelector(".dashboard-table");

  // Get the sorting button and select element
  const sortingButtonBox = document.getElementById("sortingButtonBox");
  const sortCriteriaSelect = document.getElementById("sortCriteria");

  // Add event listener to the sorting button
  sortingButtonBox.addEventListener("change", function() {
      // Get the selected criteria
      const selectedCriteria = sortCriteriaSelect.value;
      
      // Get all rows except the header row
      const rows = Array.from(table.querySelectorAll("tr:not(:first-child)"));

      // Sort the rows based on the selected criteria
      rows.sort((a, b) => {
          const textA = a.cells[getIndex(selectedCriteria)].textContent.trim().toLowerCase();
          const textB = b.cells[getIndex(selectedCriteria)].textContent.trim().toLowerCase();
          return textA.localeCompare(textB);
      });

      // Re-append sorted rows to the table
      rows.forEach(row => {
          table.appendChild(row);
      });
  });

// Function to get the index of the selected criteria
  function getIndex(criteria) {
      const headerRow = table.querySelector("tr:first-child");
      const headers = Array.from(headerRow.querySelectorAll("th"));
      return headers.findIndex(header => header.textContent.trim().toLowerCase() === criteria);
  }
});

//Function when the user click the name from the patient table
function redirectToInfoPage() {
  // Get the name of the patient from the clicked table cell
  var nameCell = event.target;
  var name = nameCell.textContent;

  // Redirect to the information page with the name as a query parameter
  window.location.href = 'info_page.html?name=' + encodeURIComponent(name);
}

// MED-REPORTS COLLAPSIBLE

// Hide quarterly report alter initially
document.addEventListener('DOMContentLoaded', function () {
  var allQuarters = document.querySelectorAll('.quarterly-report-row');
  allQuarters.forEach(function (quarter) {
      var alterBox = quarter.querySelector('.quarterly-report-alter');
      // Initially hide the alter box by adding the 'collapsed' class
      alterBox.classList.add('collapsed');
  });
});

function toggleQuarter(quarterId) {
  var quarter = document.getElementById(quarterId);
  var rowBox = quarter.querySelector('.quarterly-report-row-box');
  var alterBox = quarter.querySelector('.quarterly-report-alter');

  // Check if the alter box is currently hidden
  var isCollapsed = alterBox.classList.contains('collapsed');

  // Toggle the visibility of the alter box and the row box based on current state
  if (isCollapsed) {
      rowBox.classList.add('collapsed'); // Hide the row box
      alterBox.classList.remove('collapsed'); // Show the alter box
  } else {
      rowBox.classList.remove('collapsed'); // Show the row box
      alterBox.classList.add('collapsed'); // Hide the alter box
  }
}
function simulateContentLoading() {
  console.log("???");
  showLoader();

  // Simulate loading time (3 seconds in this example)
  setTimeout(function() {
      console.log("Loading complete. Hiding loader.");
      // After loading is complete, hide the loader
      hideLoader();
  }, 3000); // Adjust time as needed
}

// Show the loader
function showLoader() {
  console.log("Showing loader.");
  document.querySelector('.loader').style.display = 'block';
}

// Hide the loader
function hideLoader() {
  console.log("Hiding loader.");
  document.querySelector('.loader').style.display = 'none';
}

// Call function to simulate content loading
simulateContentLoading();






