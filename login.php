<?php require("php_classes/login.class.php");

    if(isset($_POST['submit'])){
        $new_user = new LoginUser($_POST['username'],$_POST['password']);
    }

    // if(isset($_POST['submit'])){
    //     $user = new LoginUser($_POST['username'], $_POST['password']);
        
    // }

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login page</title>
    <link rel="stylesheet" href="styles/reg_styles.css">
    <link href='https://fonts.googleapis.com/css?family=Kanit' rel='stylesheet'>

</head>

<body>

    <form class="form" action="" method="post" enctype="multipart/form-data" autocomplete="off">
        <h1>Login</h1>

        <label for="">Username</label>
        <input type="text" name="username">

        <label for="">Password</label>
        <input type="password" name="password">

        <button type="submit" name="submit">Login</button>

        <p class="error"><?php echo @$new_user->error ?></p>
        <p class="success"><?php echo @$new_user->success ?></p>

        <h2>Don't have account yet? <a href="register.php"> <b><u>Register</u></b></a></h2>
        <h2><a href="index_2.php"><b><u>Continue without registration</u></b></a></h2>


    </form>



</body>

</html>