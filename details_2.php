<?php
include('functions.php');
$flag_read = false;
if (isset($_POST['read'])) {
    // echo $_POST['read'];
    $flag_read = true;
}


// data from current session
$id = $_GET['id'];
$user = $_SESSION['username'];

$json = file_get_contents("db/books.json");
$data = json_decode($json, true);

$book = find_obj($data, $id);

// print_r($book);

session_start();


// handler for loggin out
if (isset($_GET['logout'])) {
    session_destroy();
    header("location: login.php");
    exit();
}


$marked = false;

// getting json content
$json_reviews = file_get_contents('db/reviews.json');
$data_reviews = json_decode($json_reviews, true);

$username = $_SESSION['user'];

// finding current user data for later use
$user = find_user($data_reviews, $username);
if ($user != null) {
    $user_name = $username;
    $user_email = $user['email'];

    $user_books_read = $user['books_read'];

    if ($flag_read) {
        $user['books_read'][] = $book['title'];
        $flagg = false;
        foreach ($data_reviews as &$u) {
            if ($u['username'] == $user['username']) {
                $u = $user;
                $flagg = true;
            }
        }
        // if cant find in array, creates a user in db
        if ($flagg == false) {
            $user['username'] = $_SESSION['user'];
            array_push($data_reviews, $user);
        }
        $json_encoded = json_encode($data_reviews, JSON_PRETTY_PRINT);
        file_put_contents("db/reviews.json", $json_encoded);

        header("Location: details.php?id=$id");
        exit();
    }

    if (in_array($book['title'], $user_books_read)) {
        $marked = true;
        // echo $marked;
    }
}


// empty arrays for data from json
$book_stars = [];
$book_reviews = [];
$usernames_for_reviews = [];

// retriving data for reviews and ratings into new created arrays above
foreach ($data_reviews as $elem) {
    foreach ($elem['reviews'] as $rev) {

        $exploded = explode(":", $rev);
        $book_id = $exploded[0];
        if ($book_id == $id) {
            $book_reviews[] = $exploded[1];
            $book_stars[] = $exploded[2];
            $usernames_for_reviews[] = $elem['username'];
        }
    }
}

// average rating
if (sizeof($book_stars) > 0) {
    $avg = round(array_sum($book_stars) / sizeof($book_stars), 2);
} else {
    $avg = 0;
}
// echo $avg;


// var_dump($user);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $review_value = $_POST;

    if (!empty($review_value['rating'])) {
        $user['ratings'][] = $id . ":" . $review_value['rating'];
        $user['reviews'][] = $id . ":" . $review_value['review'] . ":" . $review_value['rating'];

        $flag = false;
        foreach ($data_reviews as &$u) {
            if ($u['username'] == $user['username']) {
                $u = $user;
                $flag = true;
            }
        }
        // if cant find in array, creates a user in db
        if ($flag == false) {
            $user['username'] = $_SESSION['user'];
            array_push($data_reviews, $user);
        }


        $json_encoded = json_encode($data_reviews, JSON_PRETTY_PRINT);
        file_put_contents("db/reviews.json", $json_encoded);

        header("Location: details.php?id=$id");
        exit();
    }
}


// var_dump(avg_stars($avg,$book_stars));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book details</title>
    <link rel="stylesheet" href="styles/details.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />
    <link href='https://fonts.googleapis.com/css?family=Kanit' rel='stylesheet'>
    <a href="index.php"><img class="icon-home" src="assets/home_icon.png" alt=""></a>


</head>

<body>

    <header>
        <div>
            <h1><a href="index.php">Book-Store</a> > Book details</h1>
            <h2>Welcome <?php echo "to book store !" ?></h2>
        </div>
        <img class="logo" src="assets/logo_book.png" alt="">

        <div class="user-section">
            <?php if ($_SESSION['user'] == 'admin') {
                echo "<a href=\"add_book.php\"><button class = \"logout-button\"><b>+Add book</b></button></a>";
            } ?>
            <a href="?logout"><button class="logout-button"><b>Login</b></button></a>
            <a href="?register"><button class="logout-button"><b>Register</b></button></a>
        </div>

    </header>

    <div class="book-container">

        <img src="assets/<?php echo $book['image'] ?>" alt="">
        <div class="book-info">
            <h1><?php echo $book['title'] ?>
            </h1>
            <!-- <?php if (!$marked) : ?>
                <form action="" method="post">
                    <button class="book-read-button" name="read" value="read" type="submit"><b>Mark as read ✔</b></button>
                </form>
            <?php else : ?>
                <button class="book-marked"><b>Marked as read</b></button>
            <?php endif; ?> -->
            <p><b>Author: </b> <?php echo $book['author'] ?></p>
            <p class="description"><b>Description: </b> <?php echo $book['description'] ?></p>
            <p><b>Publication year: </b><?php echo $book['year'] ?></p>
            <p><b>Source planet: </b><?php echo $book['planet'] ?></p>
            <br><br>

            <div class="review-section">
                <!-- <p><b>Rate this book</b></p>
                <form action="" method="post">
                    <div class="rating-input">
                        <input type="radio" name="rating" id="star5" value="5"><label for="star5" class="fa fa-star"></label>
                        <input type="radio" name="rating" id="star4" value="4"><label for="star4" class="fa fa-star"></label>
                        <input type="radio" name="rating" id="star3" value="3"><label for="star3" class="fa fa-star"></label>
                        <input type="radio" name="rating" id="star2" value="2"><label for="star2" class="fa fa-star"></label>
                        <input type="radio" name="rating" id="star1" value="1"><label for="star1" class="fa fa-star"></label>
                    </div>
                    <div class="description-container">
                        <input class="review-input" placeholder="Describe your experience(optional)" type="text" name="review">
                        <button class="submit-button" type="submit">Submit</button>
                    </div>
                </form> -->



                <h1><b>Reviews: </b></h1>
                <?php echo avg_stars($avg, $book_stars) ?>

                <?php for ($i = 0; $i < sizeof($book_reviews); $i++) : ?>
                    <div class="review-box" style="margin-top: 20px;">
                        <p class="review-item"><b><?php echo $usernames_for_reviews[$i] ?></b></p>
                        <p class="review-item"><?php echo print_stars($book_stars[$i]) ?></p>
                        <div class="review-item"><?php echo $book_reviews[$i] ?></div>
                    </div>

                <?php endfor; ?>
            </div>


        </div>




    </div>

    <footer>
        <p>IK-Library | ELTE IK Webprogramming</p>
    </footer>
</body>



</html>


<?php

function print_stars($n)
{
    $stars = "";
    $empty = 5 - $n;


    for ($i = 0; $i < $n; $i++) {
        $stars .= "<span class =\"fa fa-star checked\"></span>";
    }

    for ($i = 0; $i < $empty; $i++) {
        $stars .= "<span class =\"fa fa-star empty\"></span>";
    }


    return $stars;
}



function avg_stars($avg, $book_stars)
{
    // // Calculate the number of filled, half, and empty stars
    $fullStars = floor($avg); // Number of full stars
    $halfStar = $avg - $fullStars >= 0.5 ? 1 : 0; // Number of half stars
    $emptyStars = 5 - $fullStars - $halfStar; // Number of empty stars

    // // Start building the output string
    $output = "<h1>" . $avg . "<br>" .  "  <span class='star-rating'>  ";

    // Generate full stars
    for ($i = 0; $i < $fullStars; $i++) {
        $output .= "<label class='fa fa-star checked'></label>";
    }

    // Generate half star if needed
    if ($halfStar) {
        $output .= "<label class='fa fa-star-half-o checked'></label>";
    }

    // Generate empty stars
    for ($i = 0; $i < $emptyStars; $i++) {
        $output .= "<label class='fa fa-star empty'></label>";
    }

    // // Add the total number of reviews
    $output .= "(" . sizeof($book_stars) . ")</span></h1>";


    return $output;
}


?>