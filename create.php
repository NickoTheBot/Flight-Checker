<?php
require 'connect.php';

// Check 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize & validate email
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die ("Invalid email format");
    }

    $errors = [];
    // Sanitize & validate username
    $username = trim($_POST["username"]);
    if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        $errors['username'] = "Incorrect Format";
    }


    // Check if username is available
    $stmt = $conn->prepare("SELECT userID FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $errors['username'] = "Username is already taken ";
    }

    // Sanitize and validate passwords
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    //length requirement
    if (strlen($password) < 10) {
        if (strlen($password) < 10) {
            $errors['password'] = "Password must be at least 10 characters";
        }
    }

    // Check for at least one capital letter
    if (!preg_match('/[A-Z]/', $password)) {
        if (!preg_match('/[A-Z]/', $password)) {
            $errors['password_capital'] = "Password must include at least 1 capital letter";
        }
    }

    // Check for at least one number or special character
    if (!preg_match('/[0-9]/', $password) && !preg_match('/[\W_]/', $password)) {
        if (!preg_match('/[0-9]/', $password) && !preg_match('/[\W_]/', $password)) {
            $errors['password_number_special'] = "Password must include a number or special character";
        }
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        $errors['password_match'] = "Passwords do not match";
    }

    // Hash the password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user into the database
    if (empty ($errors)) {
        $stmt = $conn->prepare("INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $username, $password_hash);
        if ($stmt->execute()) {
            echo "New account created successfully";
        } else {
            echo "Error: " . $conn->error;
        }

        // Close the statement
        $stmt->close();
    }
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="signup-container">
        <form class="signup-form" action="create.php" method="post">
            <h2>Create Account</h2>

            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>

            </div>
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <small>Password must be at least 10 characters and include at least 1 capital letter and a number or
                    special character.</small>
            </div>
            <div class="input-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
                <?php if (!empty ($errors)): ?>
                    <div class="error-messages">
                        <?php foreach ($errors as $error): ?>
                            <p class="error-message">
                                <?php echo $error; ?>
                            </p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <button type="submit">Create Account</button>
                <a href="index.php" class="back-to-login">Back to Log In</a>
        </form>
    </div>
</body>

</html>