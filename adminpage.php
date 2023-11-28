<?php
session_start();
require 'inc.php';



$conn = new mysqli($serverName, $userName, $password, $dbName);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// $ad = '1';
// $cl = '2';

// if (isset($_SESSION['status'])) {
//     $Email = $_SESSION['post_Email'];
//     echo $Email;
// }

if(isset($_SESSION['status']))
{
    ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Hey!</strong> <?php echo $_SESSION['status']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php
     unset($_SESSION['status']);
}


if (isset($_POST['01'])) {
    $high = 01;
}
if (isset($_POST['02'])) {
    $high = 02;
}
  $query="INSERT INTO users ("
// if (isset($high)) {
//     $choose_role = $conn->prepare("UPDATE users SET Role=?
//     WHERE Email = ? ");
//     $choose_role->bind_param("is", $high, $Email);
//     $choose_role->execute();
//     if ($high == 01) {
//         header("location:dashboard.php");
//     } else if ($high == 02) {
//         header("location:clientpage.php");
//     }
// }

?>