<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SicKo - Sign In</title>
    <link rel="icon" type="image/png" href="src/images/sicko-logo.png"> 
    <link rel="stylesheet" href="src/styles/style.css">
</head>
<body>
    <div class="container">
        <div class="logo-container">
            <img class="logo" src="src/images/sicko-logo.png" alt="Sicko Logo">
            <h2><span style="color: #058789;">Sic</span><span style="color: #E13F3D;">Ko</span> | Sign In</h2>
        </div>
        <div class="login-form">
            <form id="loginForm" method="post">
                <div class="input-group email-container">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="input-group password-container">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" id="loginBtn">Sign In</button>
            </form>
            <div id="message"></div>
        </div>
    </div>
    <img class="vector-red" src="src/images/vector-red.png" alt="Red Vector">
    <img class="vector-green" src="src/images/vector-green.png" alt="Green Vector">
</body>
</html>
