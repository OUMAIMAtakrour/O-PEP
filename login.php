<?php
session_start();
require 'inc.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];
    $loginQuery = $conn->prepare("SELECT user_role FROM persons WHERE Email = ? AND PASS_WORD = ?");

    $loginQuery = $conn->prepare("SELECT user_role FROM persons WHERE Email = ? AND PASS_WORD = ?");
    echo $conn->error; // Check for any SQL errors
    $loginQuery->bind_param("ss", $email, $password);
    $loginQuery->execute();
    $loginQuery->bind_result($userRole);



    if ($loginQuery->fetch()) {
        // Verify the password
        if (password_verify($password, $hashedPassword)) {
            // Redirect based on user role
            if ($high === "1") { // Updated variable name
                header("Location: /OPEP/dashboard.php/");
                exit;
            } elseif ($high === "2") { // Updated variable name
                header("Location: /OPEP/clientpage.php/");
                exit;
            } else {
                // Handle other roles or show an error message
                echo "Invalid role!";
            }
        } else {
            // Invalid password
            echo "Invalid password!";
        }
    } else {
        // User not found
        echo "User not found!";
    }

    $loginQuery->close();
}
?>


<?php

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
    <div class="container">
        <div class="card">
            <form method="post" action="">

                <input type="email" placeholder="Email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                <input type="password" placeholder="Password" name="password" class="form-control" id="exampleInputPassword1">

                <input class="my-2 mx-auto" type="submit" name="login" value="Submit">

            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>