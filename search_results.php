<!DOCTYPE html>
<html lang="en">
<?php
    session_start();
    if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
        header("Location: signup.php"); 
        exit();
    }
    if($_POST['work']=='search')
    {
        $stext=$_POST['stext'];
    }
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MLIB - Online Library</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
        <nav class="navbar">
            <div class="navbar-logo">
                <a href="index.html">&ensp;MLIB</a>
            </div>
            <div class="navbar-menu">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="advsearch.php">Advanced Search&nbsp;</a></li>
                    <li><a href="upload.php">Upload New&nbsp;</a></li>
                    <li><a href="delete.php">Admin Delete</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main>

        <div class="book-gallery">
            <?php
            // Establish a database connection
            include 'connection.php';

            // Initialize search conditions
            $author = $_POST['author'];
            $genre = $_POST['genre'];
            $ratings = $_POST['ratings'];

            // Build the SQL query based on search conditions
            $sql = "SELECT * FROM bookinfo WHERE 1";

            if ($author !== 'default') {
                $sql .= " AND author = '$author'";
            }

            if ($genre !== 'default') {
                $sql .= " AND genre = '$genre'";
            }

            if ($ratings !== 'default') {
                $sql .= " AND (totalrating / rno) >= $ratings";
            }

            // Execute the query
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    // Calculate the rating
                    $rating = $row['totalrating'] / $row['rno'];
                    // Generate the HTML for each book card
                    echo '<div class="book-card">';
                    ?>
                    <img src="img/<?php echo $row["image"]; ?>.jpg"
                    <?php
                    echo '<h3>' . $row['bname'] . '</h3>';
                    echo '<p>Author: ' . $row['author'] . '</p>';
                    echo '<p>Ratings: ' . $rating . '</p>';
                    echo '<br>';
                    echo '<p><a href="login.html">Click to download</a></p>';
                    echo '<br>';
                    echo '<div class="rating">';
                    echo '<form action="rate_book.php" method="post">';
                    echo '<input type="hidden" name="book_name" value="' . $row['bname'] . '"?>';
                    echo '<input type="number" name="rating" min="1" max="5" required>';
                    echo '<button type="submit">Rate</button>';
                    echo '</form>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo "No books found matching your search criteria.";
            }

            // Close the database connection
            mysqli_close($conn);
            ?>
        </div>
    </main>
</body>
</html>