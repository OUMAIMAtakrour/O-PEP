<?php

require 'inc.php';
// $sqlquery = "INSERT INTO persons VALUES
//     ('John', 'Doe', 'john@example.com')"
//checking
// if(isset($_POST['submit']))
// header("Location:second.php/");
// exit;
?>
<?php
// Check connection
// if ($conn === false) {
//   die("ERROR: Could not connect. "
//     . mysqli_connect_error());
// }

$first_name = isset($_REQUEST['post_first_name']) ? $_REQUEST['post_first_name'] : '';
$last_name = isset($_REQUEST['post_last_name']) ? $_REQUEST['post_last_name'] : '';
$user_name = isset($_REQUEST['post_user_name']) ? $_REQUEST['post_user_name'] : '';
$Email = isset($_REQUEST['post_Email']) ? $_REQUEST['post_Email'] : '';
$PASS_WORD = isset($_REQUEST['post_PASS_WORD']) ? $_REQUEST['post_PASS_WORD'] : '';


$sql = "INSERT INTO persons(first_name,last_name,user_name,Email,PASS_WORD)
VALUES ('$first_name','$last_name','$user_name','$Email','$PASS_WORD')";

if (mysqli_query($conn, $sql)) {
  echo "<h3>data stored in a database successfully."
    . " Please browse your localhost php my admin"
    . " to view the updated data</h3>";

  echo nl2br("\n$first_name\n $last_name\n "
    . "$user_name\n $Email\n $PASS_WORD");
} else {
  echo "ERROR: Hush! Sorry $sql. "
    . mysqli_error($conn);
}

// Close connection
mysqli_close($conn);
?>
<!DOCTYPE html>

<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title></title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="./css/style.css ?v=<?php echo time(); ?>">

</head>


<body>
  <header>
    <nav class="navbar bg-body-tertiary">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">
          <img src="./images/opep-high-resolution-logo-transparent.png" alt="Logo" width="120" height="60" class="d-inline-block align-text-top">

        </a>
      </div>
    </nav>
  </header>
  <main>

    <section class="login d-flex align-self-center">
      <div class="overlay col-4 my-3 mx-5">
        <div class="blocktext my-3 col-10 mx-2">
          <h1>LOG IN</h1>
          <p class="paragraph">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
        </div>
      </div>
      <div class="things bg-light my-4 mx-5">
        <form action="" method="POST" class="formulaire my-3 col-10 mx-auto">
          <label for="">First Name</label>
          <div class="input-group mb-3 my-2">

            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1">@</span>
            </div>
            <input type="text" class="form-control" placeholder="first name" aria-label="Username" aria-describedby="basic-addon1" name="post_first_name" required>

          </div>
          <label for="">Last Name</label>
          <div class="input-group mb-3 my-2">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1">@</span>
            </div>
            <input type="text" class="form-control" placeholder="last name" aria-label="Username" aria-describedby="basic-addon1" name="post_last_name" required>
          </div>
          <label for="">Username</label>
          <div class="input-group mb-3 my-2">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1">@</span>
            </div>
            <input type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1" name="post_user_name" required>
          </div>
          <label for="">Email</label>
          <div class="input-group mb-3 my-2">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1">@</span>
            </div>
            <input type="text" class="form-control" placeholder="example@gmail.com" aria-label="Username" aria-describedby="basic-addon1" name="post_Email" required>
          </div>
          <label for="">Password</label>
          <div class="input-group mb-3 my-2">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1">@</span>
            </div>
            <input type="password" class="form-control" placeholder="*************" aria-label="Username" aria-describedby="basic-addon1" name="post_PASS_WORD" required>
          </div>

          <input class="my-2 mx-auto" type="submit">
        </form>
      </div>



    </section>
  </main>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>