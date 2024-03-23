<?php

ob_start();
session_start();


require 'connect.php';

// Initialize a variable to store potential error messages
$login_error = '';

// check
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
  //sanitize
    $username = filter_var(trim($_POST["username"]), FILTER_SANITIZE_STRING);
    $password = trim($_POST["password"]);

   
    $stmt = $conn->prepare("SELECT userID, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    
   
    $stmt->store_result();
    
    if ($stmt->num_rows == 1) {
      
        $stmt->bind_result($id, $username, $hashed_password);
        $stmt->fetch();
        
       
        if (password_verify($password, $hashed_password)) {
           
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $username;
            $_SESSION['id'] = $id;

           
            header('Location: home.php'); 
            exit;
        } else {
            
            $login_error = 'The password you entered was not valid.';
        }
    } else {
        
        $login_error = 'No account found with that username.';
    }
    
   
    $stmt->close();
}


$conn->close();
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="login-container">
<h1>Flight Status Checker</h1>
<?php if ($login_error != ''): ?>
    <div class="error-message">
        <?php echo $login_error; ?>
    </div>
    <?php endif; ?>
    <form class="login-form" action="index.php" method="post">
        <h2>Login</h2>
        <div class="input-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="input-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" name="login">Log In</button>
        
        <a href="#" class="forgot-password">Forgot password?</a>
    </form>
    <a href="create.php"><button class="create-account-btn">Create Account</button></a>
</div>
<br><br>


<script>
/*document.addEventListener('DOMContentLoaded', (event) => {
  fetch('get.php')
    .then(response => response.json())
    .then(data => {
      console.log(data); // Process your data here
      // You might redirect the user or update the DOM based on the response
    })
    .catch(error => console.error('Error fetching data:', error));
});*/

document.addEventListener('DOMContentLoaded', (event) => {
  const loginForm = document.querySelector('.login-form');
  loginForm.addEventListener('submit', function(event) {
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    
    if (username === '' || password === '') {
      // Prevent the form from submitting
      event.preventDefault();
      // Show an error message (you can replace this with a more user-friendly approach)
      alert('Please enter both a username and a password.');
    }
  });
});

</script>
</body>
</html>
