<?php
require 'inc.php';
session_start();



if ($_SESSION) {
    $ID = $_SESSION['ID'];
    echo $ID;
}

if (isset($_POST['01'])) {
    $high = 01;
} else if (isset($_POST['02'])) {
    $high = 02;
}
if (isset($high)) {
    $chooserole = $conn->prepare("UPDATE users SET Role = ? WHERE ID= ? ");
    $chooserole->bind_param("is", $high, $ID);
    $chooserole->execute();
    if ($high == 01) {
        header("location:dashboard.php/");
    } else if ($high == 02) {
        header("location: clientpage.php/");
    }
}
$conn = new mysqli($serverName, $userName, $password, $dbName);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}












?>
<!DOCTYPE html>

<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./css/second.css?v=<?php echo time(); ?>">

</head>

<body>
    <label for="">First</label>
    <!-- <form action="" method="post">
        <input type="hidden" name="hidden_value" value="client">
        <button type="submit" value="client" name="what">CLIENT</button>

    </form>
    <form action="" method="post">
        <!-- <input type="hidden" name="hidden_value" value=" -->
    <!-- <button type="submit" value="admin" name="what">ADMIN</button>
    </form> -->
    <form action="" method="post">
       
        <button type="submit" name="01">ADMIN</button>
        <button type="submit" name="02">CLIENT</button>
    </form>

    <?php





    ?>
    <script src="" async defer></script>
</body>

</html>