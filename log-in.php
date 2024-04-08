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
        
        <div class="form-container">
            <form>
                <div class="input-container required">
                    <input type="email" name="email" id="emailInput" required maxlength="254">
                    <label for="emailInput">Email</label>
                </div>
                <div class="input-container required">
                    <input type="password" name="password" id="passwordInput" required maxlength="50">
                    <label for="passwordInput">Password</label>
                </div>
                <div class="button-container">
                    <button type="submit">Sign In</button>
                </div>
            </form>
        </div>
    </div>
    <img class="vector-red" src="src/images/vector-red.png" alt="Red Vector">
    <img class="vector-green" src="src/images/vector-green.png" alt="Green Vector">
    <script src="src/scripts/script.js"></script>
</body>
</html>
