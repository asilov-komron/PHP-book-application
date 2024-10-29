<?php require("php_classes/register.class.php");


if(isset($_POST['submit'])){
    $user = new RegisterUser($_POST['username'], $_POST['password'], $_POST['email']);
    // var_dump($_POST);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration page</title>
    <link rel="stylesheet" href="styles/reg_styles.css">
    <link href='https://fonts.googleapis.com/css?family=Kanit' rel='stylesheet'>


</head>
<body>
    <form class="form" action="" method="post" enctype="multipart/form-data" autocomplete="off" novalidate>
        <h1>Registration</h1>

        <label for="">Username</label>
        <input type="text" name= "username">

        <label for="">Email</label>
        <input type="email" name = "email">

        <label for="">Password</label>
        <input type="password"  name = "password">

        <button type="submit" name="submit">Register</button>

        <p class="error"><?php echo @$user->error ?></p>
        <p class="success"><?php echo @$user->success ?></p>

        <h2>Already have an account?<a href="login.php"><b> <u>Login</u></b></a></h2>
        <h2><a href="index_2.php"><b><u>Continue without registration</u></b></a></h2>

    </form>
    

</body>
</html>