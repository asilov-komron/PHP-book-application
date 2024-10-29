<?php



session_start();

if (isset($_GET['logout'])) {
    session_destroy();
    header("location: login.php");
    exit();
}


$username = $_SESSION['user'];
$login_time = $_SESSION['logged_in_datetime'] = date('d M Y H:i');
// echo $username; 


$json = file_get_contents('db/reviews.json');
$data = json_decode($json, true);

$user = find_user($data, $username);
$user_name = $username;
$user_email = $user['email'];
$user_books_read = $user['books_read'];
$book_ids = [];
$book_reviews = [];

$user_reviews = $user['reviews'];
foreach ($user_reviews as $rev) {
    $exploded = explode(":", $rev);
    $book_ids[] = $exploded[0];
    $book_reviews[] = $exploded[1];
}
// var_dump($user_reviews);
// echo "<br>";
// var_dump($user);
// var_dump($book_ids);
// var_dump($book_reviews);





?>

<?php

$json_books = file_get_contents('db/books.json');
$books_data = json_decode($json_books, true);
$books_needed = [];
for ($i = 0; $i < sizeof($book_ids); $i++) {
    $books_needed[] = search_book_title_by_id($books_data, $book_ids[$i]);
}

// var_dump($books_needed)



?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User details</title>
    <link rel="stylesheet" href="styles/user.css">
    <link href='https://fonts.googleapis.com/css?family=Kanit' rel='stylesheet'>

</head>

<body>
    <div class="wrapper">

        <header>
            <div>
                <h1><a href="index.php">Book-Store</a> > User details</h1>
                <h2>Welcome <?php echo $_SESSION['user']; ?>!</h2>
            </div>
            <img class="logo" src="assets/logo_book.png" alt="">

            <div class="user-section">
                <?php if ($_SESSION['user'] == 'admin') {
                    echo "<a href=\"add_book.php\"><button><b>+Add book</b></button></a>";
                } ?>
                <a href="?logout"><button><b>Log out</b></button></a>
                <a href="index.php"><img class="icon" src="assets/home_icon.png" alt=""></a>
            </div>

        </header>


        <div class="user-container">
            <img class="profile" src="assets/user_profile.png" alt="">
            <div class="user-info">
                <h1><?php echo $user['username'] ?></h1>
                <p><b>Login time: </b><?php echo $login_time ?></p>
                <p><b>Email</b>: <?php echo $user_email ?></p>
                <p><b>Books read:</b> <?php foreach ($user_books_read as $b) {
                                            echo "<br>";
                                            echo "• " . $b;
                                        } ?></p>
                <p><b>Books reviews:</b> <?php for ($i = 0; $i < sizeof($books_needed); $i++) {
                                                echo "<br>";
                                                echo "- " . $book_reviews[$i] . " (" . "<b>" . $books_needed[$i] . "</b>" . ")";
                                            } ?></p>
            </div>
        </div>

        <footer>
            <p>IK-Library | ELTE IK Webprogramming</p>
        </footer>
    </div>
</body>

</html>


<?php
function find_user($data, $username)
{
    foreach ($data as $user) {
        if ($user['username'] == $username) {
            return $user;
        }
    }

    return null;
}


function search_book_title_by_id($books_data, $id)
{
    foreach ($books_data as $book) {
        if ($book['id'] == $id) {
            return $book['title'];
        }
    }
    return null;
}



?>