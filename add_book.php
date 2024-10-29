<?php

session_start();

if (!isset($_SESSION['user']) && $_SESSION['user'] != "admin") {
    header("location: login.php");
    exit();
}




if (isset($_GET['logout'])) {
    session_destroy();
    header("location: login.php");
    exit();
}







$json = file_get_contents("db/books.json");
$books_data = json_decode($json, true);

$input = $_GET;
$data = [];
$errors = [];



$is_valid = validate($input, $errors, $data,$books_data);
// print_r($books_data);
// var_dump($data);

if ($is_valid) {
    array_push($books_data, $data);
    // print_r($books_data);

    $json_encoded = json_encode($books_data, JSON_PRETTY_PRINT);
    file_put_contents('db/books.json', $json_encoded);
}



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin panel</title>
    <!-- <link rel="stylesheet" href="styles/main.css"> -->
    <link rel="stylesheet" href="styles/add_book.css"> 
    <!-- <link rel="stylesheet" href="styles/reg_styles.css"> -->
    <link href='https://fonts.googleapis.com/css?family=Kanit' rel='stylesheet'>

   <style>
       
    </style>
</head>

<body>


    <form class="form" action="" method="get" enctype="multipart/form-data" autocomplete="off">
        <h1 class="add-book-h1">Add book</h1>

        <label for="">Id</label>
        <input type="number" name="id">

        <label for="">Title</label>
        <input type="text" name="title">

        <label for="">Author</label>
        <input type="text" name="author">



        <label for="">Description</label>
        <input type="text" name="description">

        <label for="">Short Description</label>
        <input type="text" name="short_desc">

        <label for="">Year</label>
        <input type="number" name="year">


        <label for="">Image</label>
        <input type="text" name="image">

        <label for="">Planet</label>
        <input type="text" name="planet">





        <button type="submit" name="submit">Add</button>

        <?php if (count($errors) > 0) : ?>
            <ul>
                <?php foreach ($errors as $err) : ?>
                    <li class="error"><?php echo $err ?></li>
                <?php endforeach; ?>
            <?php else : ?>
            </ul>
            <p class="success"><?php echo "Book added successfully" ?></p>
        <?php endif; ?>

        <h2>Go back to home page: <a href="index.php"> <b><u>Homepage</u></b></a></h2>

    </form>


</body>

</html>


<?php


function validate($input, &$errors, &$data,$book_data)
{

    // id validation
    if (empty($input['id'])) {
        $errors[] = "Enter valid id!";
    } else $data['id'] = $input['id'];

    foreach ($book_data as $d) {
        if ($d['id'] == $input['id']) {
            $errors[] = "This book id exists!";
        }
    }


    // title validation
    if (empty($input['title'])) {
        $errors[] = "Enter valid title!";
    } else $data['title'] = $input['title'];

    // author validation
    if (empty($input['author'])) {
        $errors[] = "Enter valid author name!";
    } else $data['author'] = $input['author'];

    // Description validation
    if (empty($input['description'])) {
        $errors[] = "Enter valid description!";
    } else $data['description'] = $input['description'];

    //  short descritpion validation
    if (empty($input['short_desc'])) {
        $errors[] = "Enter valid short description!";
    } else $data['short_desc'] = $input['short_desc'];

    // year validation
    if (empty($input['year'])) {
        $errors[] = "Enter valid year of publication!";
    } else $data['year'] = $input['year'];

    // image validation
    if (empty($input['image'])) {
        $errors[] = "Enter valid image url!";
    } else $data['image'] = $input['image'];

    // planet validation
    if (empty($input['planet'])) {
        $errors[] = "Enter valid planet name!";
    } else $data['planet'] = $input['planet'];


    return count($data) == 8;
}


?>