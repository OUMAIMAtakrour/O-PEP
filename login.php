<?php


session_start();
require 'inc.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $loginQuery = $conn->prepare("SELECT user_role FROM persons WHERE Email = ? AND PASS_WORD = ?");
    $loginQuery->bind_param("ss", $email, $password);
    $loginQuery->execute();
    $loginQuery->bind_result($userRole);

    if ($loginQuery->fetch()) {
        // Redirect based on user role
        if ($userRole === "1") {
            header("Location: /OPEP/dashboard.php/");
            exit;
        } elseif ($userRole === "2") {
            header("Location: /OPEP/clientpage.php/");
            exit;
        } else {
            // Handle other roles or show an error message
            echo "Invalid role!";
        }
    } else {
        // Login failed, handle accordingly (show error message, redirect back to login page, etc.)
        echo "Invalid credentials!";
    }

    $loginQuery->close();
}
?>

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/login.css">

</head>

<body>
    <div class="container login-container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="login-form">
                    <h2 class="text-center mb-4">Login</h2>
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password:</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="text-center">
                            <button type="submit" name="login" class="btn btn-primary">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>