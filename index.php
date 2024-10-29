<?php

session_start();

if (!isset($_SESSION['user'])) {
    header("location: login.php");
    exit();
}


if (isset($_GET['logout'])) {
    session_destroy();
    header("location: login.php");
    exit();
}





$json = file_get_contents("db/books.json");
$data = json_decode($json, true);





?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IK-Library | Home</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
    <link href='https://fonts.googleapis.com/css?family=Kanit' rel='stylesheet'>

</head>

<body>
    <header>
        <div>
            <h1><a href="index.php">Book-Store</a> > Home</h1>
            <h2>Welcome <?php echo $_SESSION['user']; ?>!</h2>
        </div>

        <img class="logo" src="assets/logo_book.png" alt="">

        <div class="user-section">
            <?php if($_SESSION['user'] == 'admin'){
               echo "<a href=\"add_book.php\"><button><b>+Add book</b></button></a>";

             } ?>
            <a href="?logout"><button><b>Log out</b></button></a>
            <a href="user.php"><img class="icon" src="assets/profile_icon.png" alt=""></a>
        </div>

    </header>
    <div id="content">

        <div id="card-list">

            <!-- CARD -->
            <?php foreach ($data as $book) : ?>
                <div class="book-card-2">
                    <div class="book-title">
                        <h2><a class="book-link" href="details.php?id=<?php echo $book['id'] ?>"><?php echo $book['title'] ?></a></h2>
                    </div>

                    <div class="book-image-text">
                        <a href="details.php?id=<?php echo $book['id'] ?>"><img src="assets/<?php echo $book['image'] ?>" alt=""></a>

                        <div class="text"><?php echo $book['short_desc'] ?></div>
                    </div>

                    <div class="edit">
                        <span><a class="book-link" href="details.php?id=<?php echo $book['id'] ?>">Details</a></span>
                    </div>
                </div>
            <?php endforeach; ?>

            
        </div>
    </div>
    <footer>
        <p>IK-Library | ELTE IK Webprogramming</p>
    </footer>
</body>

</html>