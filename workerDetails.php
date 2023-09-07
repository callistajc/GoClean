<?php
// Initialize the session
session_start();

// Include connection file
require_once "connection.php";

// Define variables and initialize with empty values
$id = $name = $price = $type = $description = $photo = "";

// Show worker details
if(isset($_GET['id']) && !empty($_GET['id'])){
    // Get value
    $id = $_GET['id'];

    // Prepare a select statement
    $sql = "SELECT Users.FirstName, Users.LastName, Workers.Photo, Workers.Type, Workers.Price, Workers.Description
            FROM Users, Workers
            WHERE Workers.Id = $id AND Workers.UserId = Users.Id";
    
    if($result = mysqli_query($link, $sql)){
        if(mysqli_num_rows($result) > 0){
            $worker = mysqli_fetch_array($result);

            // Assign values to variables
            $id = $_GET['id'];
            $name = $worker['FirstName'] . " " . $worker['LastName'];
            $price = $worker['Price'];
            $type = $worker['Type'];
            $description = $worker['Description'];
            $photo = base64_encode($worker['Photo']);

            // Free result set
            mysqli_free_result($result);
        } else{
            echo "No records matching your query were found.";
        }
    } else{
        echo "Oops! Something went wrong. Please try again later.";
    }
} else{
    echo "Oops! Something went wrong. Please try again later.";
}
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <!-- meta data -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- title of site -->
        <title>GoClean</title>

        <!-- Poppins Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900">

        <!-- bootstrap.min.css -->
        <link rel="stylesheet" href="css/bootstrap.min.css">

        <!-- style.css -->
        <link rel="stylesheet" href="css/style.css">
    </head>

    <body>
        <header class="sticky-top">
            <nav class="navbar navbar-dark navbar-expand-lg bg-dark">
                <div class="container-fluid mx-5 my-2">
                    <a class="navbar-brand" href="index.php">Go<span class="green-text">Clean</span>.</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNavDropdown">
                        <ul class="navbar-nav mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link" aria-current="page" href="index.php#home">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php#about-us">About Us</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php#our-plans">Our Plans</a>
                            </li>
                            <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="index.php#workers" role="button" data-bs-toggle="dropdown" aria-expanded="false">Workers</a>
                                <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="index.php#housework">Housework</a></li>
                                    <li><a class="dropdown-item" href="index.php#gardener">Gardener</a></li>
                                    <li><a class="dropdown-item" href="index.php#babysitter">Babysitter</a></li>
                                </ul>
                            </li>
                        </ul>
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                            <?php
                            $count = $_GET['inCart'];

                            if(!isset($_SESSION["loggedin"])){
                                echo "<li class=\"nav-item\">";
                                    echo "<a id=\"login\" class=\"nav-link\" href=\"login.php\">Log In</a>";
                                echo "</li>";
                                echo "<li class=\"nav-item\">";
                                    echo "<a id=\"signup\" class=\"nav-link\" href=\"signup.php\">Sign Up</a>";
                                echo "</li>";
                            } elseif(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && $_SESSION["role"] === "Admin"){
                                echo "<li class=\"nav-item cart-button\">";
                                    echo "<button id=\"cartButton\" type=\"button\" class=\"btn btn-success bg-dark position-relative\">";
                                        echo "<i class=\"fa-solid fa-cart-shopping\"></i>";
                                        if($count !== 0){
                                            echo "<span class=\"position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger\">";
                                                echo $count;
                                                echo "<span class=\"visually-hidden\">items in cart</span>";
                                            echo "</span>";
                                        }
                                    echo "</button>";
                                echo "</li>";
                                echo "<li class=\"nav-item\">";
                                    echo "<a class=\"nav-link\" href=\"admin.php\">Admin Site</a>";
                                echo "</li>";
                                echo "<li class=\"nav-item\">";
                                    echo "<a class=\"nav-link\" href=\"logout.php\">Log Out</a>";
                                echo "</li>";
                            } elseif(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && $_SESSION["role"] === "Basic"){
                                echo "<li class=\"nav-item cart-button\">";
                                    echo "<button id=\"cartButton\" type=\"button\" class=\"btn btn-success bg-dark position-relative\">";
                                        echo "<i class=\"fa-solid fa-cart-shopping\"></i>";
                                        if($count !== 0){
                                            echo "<span class=\"position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger\">";
                                                echo $count;
                                                echo "<span class=\"visually-hidden\">items in cart</span>";
                                            echo "</span>";
                                        }
                                    echo "</button>";
                                echo "</li>";
                                echo "<li class=\"nav-item\">";
                                    echo "<a class=\"nav-link\" href=\"logout.php\">Log Out</a>";
                                echo "</li>";
                            } elseif(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && $_SESSION["role"] === "Worker"){
                                echo "<li class=\"nav-item\">";
                                    echo "<a class=\"nav-link\" href=\"worker.php\">Worker Site</a>";
                                echo "</li>";
                                echo "<li class=\"nav-item\">";
                                    echo "<a class=\"nav-link\" href=\"logout.php\">Log Out</a>";
                                echo "</li>";
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <section id="shopping-cart">
            <div class="container mt-5">
                <div class="row">
                    <div class="col-5">
                        <div class="card shadow">
                            <div class="card-body text-center">
                                <div class="box">
                                    <img src="data:image/jpeg;base64,<?=$photo?>" class="rounded-circle mt-2 mb-5" height="250px" width="250px"/>
                                    <h4 class="card-title green-text mb-3"><?=$type?></h4>
                                    <?php
                                    if($_SESSION['role'] == "Admin" || $_SESSION['role'] == "Basic"){
                                        if($_GET['added'] == "true"){
                                            echo "<a class=\"btn btn-success mt-3 disabled\" href=\"#\" role=\"button\"><i class=\"fas fa-shopping-cart\"></i> Added to Cart</a>";
                                        } else{
                                            echo "<a data-id=\"$_GET[id]\" class=\"btn btn-success btn-cart mt-3\" href=\"#\" role=\"button\"><i class=\"fas fa-shopping-cart\"></i> Add to Cart</a>";
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-7 ml-3">
                        <h2><?=$name?></h2>
                        <h3>RM <?=$price?>.00 per Day</h3>
                        <p><span class="fw-bold">Category:</span> <?=$type?></p>
                        <p class="fw-bold">Description:</p>
                        <p><?=$description?></p>
                    </div>
                </div>
            </div>
        </section>

        <footer id="footer" class="bg-dark white-text">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12 text-center">
                        <h1 class="mt-5">Go<span class="green-text">Clean</span>.</h2>
                        <ul class="sections mt-5 list-group list-group-horizontal justify-content-center">
                            <li class="mx-3"><a href="#home">HOME</a></li>
                            <li class="mx-3"><a href="#our-plans">OUR PLANS</a></li>
                            <li class="mx-3"><a href="#about-us">ABOUT US</a></li>
                            <li class="mx-3"><a href="#category">CATEGORY</a></li>
                        </ul>
                        <ul class="socials mt-5 list-group list-group-horizontal justify-content-center">
                            <li class="mx-3"><a href="#"><i class="fa-brands fa-instagram fa-xl"></i></a></li>
                            <li class="mx-3"><a href="#"><i class="fa-brands fa-facebook fa-xl"></i></a></li>
                            <li class="mx-3"><a href="#"><i class="fa-brands fa-twitter fa-xl"></i></a></li>
                        </ul>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-md-12 text-center">
                        <p class="copyright mt-5">Copyright &copy;2022 All rights reserved.</p>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Login Modal -->
        <div id="loginModal" class="modal fade">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                </div>
            </div>
        </div>

        <!-- Signup Modal -->
        <div id="signupModal" class="modal fade">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                </div>
            </div>
        </div>

        <!-- jquery.min.js -->
        <script src="js/jquery.min.js"></script>

        <!-- bootstrap.bundle.min.js -->
        <script src="js/bootstrap5.bundle.min.js"></script>

        <!-- Font Awesome -->
        <script src="https://kit.fontawesome.com/153cf5ee17.js" crossorigin="anonymous"></script>

        <!-- script.js -->
        <script src="js/script.js"></script>

        <script>
            // Login Modal
            $('#login').on('click', function(e){
                e.preventDefault();
                $('#loginModal').modal('show').find('.modal-content').load($(this).attr('href'));
            });

            // Signup Modal
            $('#signup').on('click', function(e){
                e.preventDefault();
                $('#signupModal').modal('show').find('.modal-content').load($(this).attr('href'));
            });

            // Add to Cart
            $('.btn-cart').on('click', function () {
                var id = $(this).attr('data-id');
                location.href = "addCart.php?id="+id;
            });

        </script>

    </body>

</html>