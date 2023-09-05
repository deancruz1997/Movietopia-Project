<?php
include "dbFunctions.php";
session_start();

// sets default search query
$searchInput = "";
$searchMessage = "";

// sets default sorting order
$sortOrder = "movieId";
$sortMessage = "";

// checks if user has searched
if (isset($_POST['searchInput'])) {
    $searchInput = $_POST['searchInput'];

    $searchMessage = "Showing results for '<b>$searchInput</b>':";
}

// checks if user has chosen a sort option
if (isset($_POST['sortOrder'])) {
    $sortOrder = $_POST['sortOrder'];
}

// sets message to be displayed if user sorts movies
if ($sortOrder == "movieId") {
    $sortMessage = "Sorted by - Default";
} else if ($sortOrder == "movieTitle") {
    $sortMessage = "Sorted by - Title";
} else if ($sortOrder == "movieGenre") {
    $sortMessage = "Sorted by - Genre";
} else if ($sortOrder == "runningTime") {
    $sortMessage = "Sorted by - Total Duration";
}


// build SQL query
$query = "SELECT movieId, movieTitle, movieGenre, picture FROM movies WHERE movieTitle LIKE '%$searchInput%' ORDER by $sortOrder, 2";

// execute SQL query
$result = mysqli_query($link, $query) or die(mysqli_error($link));

// process the result
while ($row = mysqli_fetch_assoc($result)) {
    $arrResult[] = $row;
}

$welcomeMessage = "";
if (isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] === true) {
    $welcomeMessage = "Welcome, " . $_SESSION['name'];
}
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>Movietopia || Movies</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./stylesheet/styles.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
    </head>
    <body>
        <div class="header">
            <div class="headerContainer">
                <nav class="navbar-expand-sm navbar-dark">
                    <div class="container-fluid">
                        <ul class="headerList">
                            <li><a href="./viewMovies.php">Movies</a>
                            <li><a href="./viewReviews.php">Reviews</a>
                                <?php if (isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] === true) { ?>
                                <li><a href="./accountInformation.php">Account Information</a>    
                                <li><a href="./logout.php">Logout</a>
                                <?php } else { ?>
                                <li><a href="./login.php">Register/Login</a>
                                <?php } ?>
                        </ul>
                    </div>
                </nav>
                <div class="searchContainer">
                    <form class="searchBar" method="POST" action="viewMovies.php">
                        <input name="searchInput" type="text" size ="20" maxlength="100" placeholder="Search for movies" class="searchInput" required/>
                        <input class="searchBtn" type="submit" value="Search"/>
                    </form>
                </div>
            </div>
        </div>
        <div class="welcomeMessage"><?php echo $welcomeMessage ?></div>
        <div class="mainContent">
            <div class="mainMovieContainer">
                <div class="moviesListHeader">Movies List</div>
                <div class="searchText"><?php echo $searchMessage; ?></div>
                <?php if (isset($_POST['sortOrder'])) { ?>
                    <div class="sortText"><?php echo $sortMessage; ?></div>
                <?php } ?>
                <form method="POST" action="doMovie.php" class="movieSelection">
                    <!-- For loop to iterate over the movies and display each movie in the list. If statement just to check if after search input, there are rows so as to not return error -->
                    <?php
                    if (isset($arrResult)) {
                        for ($i = 0; $i < count($arrResult); $i++) {
                            ?>
                            <div class="individualMovieContainer">
                                <!-- The name is the movieId for each element. Used in POST later to display individual movie -->
                                <button type="submit" class="movieBtn" name="movie<?php echo $arrResult[$i]['movieId'] ?>">
                                    <img src="/GA/Images/<?php echo $arrResult[$i]['picture'] ?>" class="movieListImg" alt="<?php echo $arrResult[$i]['picture'] ?>">
                                </button>
                                <div class="movieName"><?php echo $arrResult[$i]['movieTitle'] ?></div>
                                <div class="movieGenre"><?php echo $arrResult[$i]['movieGenre'] ?></div>
                            </div>
                            <?php
                        }
                    };
                    ?>
                </form>
                <!-- reloads PHP with new sorted order -->
                <form method="POST" action="viewMovies.php" class="sortForm">
                    <div class="sortMessage">Sort by:</div>
                    <select name="sortOrder" class="sortSelect">
                        <option value="movieId">Default</option>
                        <option value="movieTitle" >Title</option>
                        <option value="movieGenre" >Genre</option>
                        <option value="runningTime" >Total Duration</option>
                    </select>
                    <input type="submit" value="Sort" class="sortBtn">
                </form>
            </div>
        </div>

        <div class="footer">
            <a href="https://deancruz1997.github.io/" class="footerText">C203 Graded Assignment - Dean Cruz</a>
        </div>
        <?php
// close connection
        mysqli_close($link);
        ?>
    </body>
</html>