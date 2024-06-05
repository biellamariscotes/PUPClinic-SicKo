<?php
require_once ('includes/session-nurse.php');
require_once ('includes/connect.php');

// Initialize variables
$errors = array();

// Fetch nurse data from the database and populate form fields
$sql = "SELECT * FROM nurse WHERE nurse_id = {$_SESSION['nurse_id']}";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $last_name_value = $row['last_name'];
    $first_name_value = $row['first_name'];
    $middle_name_value = $row['middle_name'];
    $email_value = $row['email'];
} else {
    // Handle error if nurse data is not found
    $errors[] = "Nurse data not found.";
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form data
    $last_name = ucwords(strtolower(mysqli_real_escape_string($conn, $_POST['lastName'])));
    $first_name = ucwords(strtolower(mysqli_real_escape_string($conn, $_POST['firstName'])));
    $middle_name = ucwords(strtolower(mysqli_real_escape_string($conn, $_POST['middleName'])));
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Update nurse data in the database
    if (empty($errors)) {
        $sql = "UPDATE nurse SET last_name='$last_name', first_name='$first_name', middle_name='$middle_name', email='$email' WHERE nurse_id = {$_SESSION['nurse_id']}";

        if (mysqli_query($conn, $sql)) {
            // Fetch updated nurse data and populate form fields again
            $sql = "SELECT * FROM nurse WHERE nurse_id = {$_SESSION['nurse_id']}";
            $result = mysqli_query($conn, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $last_name_value = $row['last_name'];
                $first_name_value = $row['first_name'];
                $middle_name_value = $row['middle_name'];
                $email_value = $row['email'];
            }
            // Optionally, you can redirect the user to a success page or display a success message here
        } else {
            $errors[] = "Error updating record: " . mysqli_error($conn);
        }
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Settings</title>
    <link rel="icon" type="image/png" href="images/heart-logo.png">
    <link rel="stylesheet" href="styles/dboardStyle.css">
    <link rel="stylesheet" href="styles/modals.css">
    <link rel="stylesheet" href="../vendors/bootstrap-5.0.2/dist/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

    <style>
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="loader">
        <img src="images/loader.gif">
    </div>

    <div class="overlay" id="overlay"></div>

    <div class="main-content">
    <?php
    include ('includes/sidebar/user-settings.php');
    ?>


    <!-- Save Changes Modal -->
        <div class="modal" id="saveChangesModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-middle-icon">
                        <i class="bi bi-check-circle-fill" style="color:#058789; font-size:5rem"></i>
                    </div>
                    <div class="modal-title" style="color: black;">Save Changes</div>
                    <div class="modal-subtitle" style="justify-content: center;">Are you sure you want to save your changes?</div>
                </div>
                <div class="modal-buttons">
                    <button type="button" class="btn btn-secondary" id="cancel-saveChanges-modal" data-dismiss="modal" style="background-color: #777777; 
                    font-family: 'Poppins'; font-weight: bold; padding: 0.070rem 1.25rem 0.070rem 1.25rem; margin-right: 1.25rem;">Cancel</button>
                    <button type="button" class="btn btn-secondary" id="submit-changes-modal" data-dismiss="modal" style="background-color: #058789; 
                    font-family: 'Poppins'; font-weight: bold; padding: 0.070rem 1.25rem 0.070rem 1.25rem;">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Saved Successfully Modal -->
        <div class="modal" id="saved-successfully" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="modal-middle-icon">
                            <img src="images/check.gif" style="width: 12rem; height: auto;" alt="Check Icon">
                        </div>
                        <div class="modal-title" style="color: black; margin-top: 1.25rem;">Saved Successfully</div>
                        <div class="modal-subtitle" style="justify-content: center; width: 98%;">Your changes have been successfully saved!</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Error Modal -->
            <div class="modal" id="error-modal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                    <div class="modal-body">
                        <div class="modal-middle-icon">
                            <img src="images/x-mark.gif" style="width: 9rem; height: auto;" alt="Error Icon">
                        </div>
                        <div class="modal-title">Error</div>
                        <div class="modal-subtitle" style="text-wrap: pretty; ">Oops! It looks like no changes were made. Please update the input field to proceed.</div>
                    </div>
                    <div class="modal-buttons">
                        <button type="button" class="btn btn-secondary" id="error-close-modal" data-dismiss="modal"
                            style="background-color: #E13F3D; 
                    font-family: 'Poppins'; font-weight: bold; padding: 0.070rem 1.25rem 0.070rem 1.25rem; margin-top: 1rem;">Close</button>
                    </div>
                </div>
            </div>
        </div>


    <div class="content" id="content">
        <div class="left-header">
            <p>
                <span style="color: #E13F3D;">Edit</span>
                <span style="color: #058789;">Profile</span>
            </p>
            <div class="left-header-subtitle">
                Update your information.
            </div>
        </div>

        <div id="empty-field-message">All fields must be filled.</div>

        <!-- Form Container -->
        <div class="form-container">
            <form id="edit-profile-form" method="post">
                <!-- Display errors, if any -->
                <?php if (!empty($errors)) { ?>
                    <div class="error-message">
                        <?php foreach ($errors as $error) { ?>
                            <p><?php echo $error; ?></p>
                        <?php } ?>
                    </div>
                <?php } ?>
                <div class="input-row">
                    <div class="group">
                        <div class="userProfile-input-label">Last Name</div>
                        <input type="text" id="lastName" name="lastName" value="<?php echo $last_name_value; ?>"
                            autocomplete="off" required>
                    </div>
                    <div class="group">
                        <div class="userProfile-input-label">First Name</div>
                        <input type="text" id="firstName" name="firstName" value="<?php echo $first_name_value; ?>"
                            autocomplete="off" required>
                    </div>
                    <div class="group">
                        <div class="userProfile-input-label">Middle Name</div>
                        <input type="text" id="middleName" name="middleName" value="<?php echo $middle_name_value; ?>"
                            autocomplete="off" required>
                    </div>
                </div>
                <div class="input-row">
                    <div class="group">
                        <div class="userProfile-input-label">Email</div>
                        <input type="email" id="email" name="email" value="<?php echo $email_value; ?>"
                            autocomplete="off" required>
                    </div>
                </div>
                <div class="middle-row">
                    <button type="submit" id="submit-form-button" name="record-btn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <?php
    include ('includes/footer.php');
    ?>
    </div>
    <script src="../vendors/bootstrap-5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="scripts/script.js"></script>
    <script src="scripts/loader.js"></script>

    <script>
    $(document).ready(function () {
        // Function to check if any input field is empty
        function checkEmptyInputs() {
            var isEmpty = false;
            $('input').each(function () {
                if ($(this).val() === '') {
                    isEmpty = true;
                    return false; // Exit the loop if any input field is empty
                }
            });
            return isEmpty;
        }

        // Initially disable the button if any input field is empty
        $('#submit-form-button').prop('disabled', checkEmptyInputs());

        // Initially show or hide the empty field message
        $('#empty-field-message').toggle(checkEmptyInputs());

        // Add event listener to input fields
        $('input').on('input', function () {
            // Enable or disable the button based on input field status
            $('#submit-form-button').prop('disabled', checkEmptyInputs());
            // Show or hide the empty field message
            $('#empty-field-message').toggle(checkEmptyInputs());
        });

        // Show Modal when Submit button is clicked
        $("#submit-form-button").click(function (event) {
            event.preventDefault(); // Prevent default form submission
            // Check if any changes are made
            if (!checkFormChanges()) {
                // Show error modal if no changes are made
                $("#error-modal").modal("show");
            } else {
                $("#saveChangesModal").modal("show");
            }
        });

        // Function to check if any changes are made in the form
        function checkFormChanges() {
            var changesMade = false;
            $('input').each(function () {
                if ($(this).val() !== $(this).attr('value')) {
                    changesMade = true;
                    return false; // Exit the loop if any change is detected
                }
            });
            return changesMade;
        }

        // Close the Modal with the close button
        $("#cancel-saveChanges-modal").click(function (event) {
            $("#saveChangesModal").modal("hide");
        });

        // Close the Modal with the close button
        $("#error-close-modal").click(function (event) {
            $("#error-modal").modal("hide");
        });

        // Handle form submission when user confirms in the modal
        $("#submit-changes-modal").click(function (event) {
            // Submit the form
            $("#edit-profile-form").submit();
        });

        // Handle form submission success
        $("#edit-profile-form").submit(function (event) {
            event.preventDefault(); // Prevent default form submission
            var form = $(this);

            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize(),
                success: function (response) {
                    // Hide the "Save Changes" modal
                    $("#saveChangesModal").modal("hide");
                    // Show the "Saved Successfully" modal
                    $("#saved-successfully").modal("show");
                },
                error: function (xhr, status, error) {
                    // Handle errors if any
                    console.log(xhr.responseText);
                }
            });
        });

        // Reload the page after form submission
        $("#edit-profile-form").submit(function () {
            // Reload the page after a short delay (adjust time as needed)
            setTimeout(function () {
                location.reload(true); // Reload the page
            }, 3000); // 1000 milliseconds = 1 second
        });
    });

</script>

</body>

</html>