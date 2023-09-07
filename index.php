<?php
// Initialize the session
session_start();

// Include connection file
require_once "connection.php";

// Define variables and initialize with empty values
$count = 0;
$inCart = array();

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && ($_SESSION["role"] === "Basic" || $_SESSION["role"] === "Admin")){
    // Get value
    $id = $_SESSION['id'];

    // Prepare a select statement
    $sql = "SELECT * FROM Cart WHERE UserId = $id";

    if($result = mysqli_query($link, $sql)){
        if(mysqli_num_rows($result) > 0){
            // Number of items in cart
            $count = mysqli_num_rows($result);

            while($cart = mysqli_fetch_array($result)){
                array_push($inCart, $cart['WorkerId']);
            }
        }
    }
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

        <!-- Owl Carousel -->
        <link rel="stylesheet" href="css/owl.carousel.min.css">
        <link rel="stylesheet" href="css/owl.theme.default.css">

        <!-- file-upload.css -->
        <link rel="stylesheet" href="css/file-upload.css">

        <!-- style.css -->
        <link rel="stylesheet" href="css/style.css">
    </head>

    <body id="main">
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
                                <a class="nav-link active" aria-current="page" href="#home">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#about-us">About Us</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#our-plans">Our Plans</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#workers" role="button" data-bs-toggle="dropdown" aria-expanded="false">Workers</a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#housework">Housework</a></li>
                                    <li><a class="dropdown-item" href="#gardener">Gardener</a></li>
                                    <li><a class="dropdown-item" href="#babysitter">Babysitter</a></li>
                                </ul>
                            </li>
                        </ul>
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                            <?php
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

        <section id="home">
            <div id="carouselHome" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active" data-bs-interval="5000">
                        <img src="images/carousel/1.jpg" class="d-block w-100" alt="carousel image 1">
                    </div>
                    <div class="carousel-item" data-bs-interval="5000">
                        <img src="images/carousel/2.jpg" class="d-block w-100" alt="carousel image 2">
                    </div>
                    <div class="carousel-item" data-bs-interval="5000">
                        <img src="images/carousel/3.jpg" class="d-block w-100" alt="carousel image 3">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselHome" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselHome" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </section>
        
        <section id="about-us">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12 text-center">
                        <h2>About Us</h2>
                        <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
                        Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
                        Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. 
                        Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                        </p>
                    </div>
                    <div class="col-md-12 mt-5">
                        <img src="images/banner.png" class="w-100">
                    </div>
                    <div class="col-md-4 my-5 text-center">
                        <img src="images/branch.png">
                        <h5 class="mt-3"><strong>Branches</strong></h5>
                        <p class="mb-5">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                        </p>
                    </div>
                    <div class="col-md-4 my-5 text-center">
                        <img src="images/headquarter.png">
                        <h5 class="mt-3"><strong>Headquarter</strong></h5>
                        <p class="mb-5">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                        </p>
                    </div>
                    <div class="col-md-4 my-5 text-center">
                        <img src="images/quality.png">
                        <h5 class="mt-3"><strong>Quality</strong></h5>
                        <p class="mb-5">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section id="our-plans">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6 my-5">
                        <h2 class="mb-5">Our Plans</h2>
                        <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
                        Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
                        Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. 
                        Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                        </p>
                        <a href="#footer" class="mt-5 btn btn-outline-success">Contact Us</a>
                    </div>
                    <div class="col-md-6 my-5">
                        <img src="images/goclean2.png" class="w-100" height="350px" width="350px">
                    </div>
                </div>
            </div>
        </section>

        <section id="workers">
            <div class="text-center">
                <h2>Workers</h2>
                <div id="housework" class="owl-carousel owl-theme px-5">
                    <?php
                    // Attempt select query execution
                    $sql = "SELECT * FROM Workers WHERE Status = 'Accepted' AND Type = 'Housework'";

                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            while($worker = mysqli_fetch_array($result)){
                                $photo = base64_encode($worker['Photo']);

                                echo "<div class=\"item p-5\">";
                                    echo "<div class=\"card shadow\">";
                                        echo "<a data-id=\"$worker[Id]\" class=\"profile\" href=\"#\">";
                                            echo "<div class=\"card-body text-center\">";
                                                echo "<div class=\"box\">";
                                                    echo "<img src=\"data:image/jpeg;base64,$photo\" class=\"rounded-circle mt-2 mb-5\" width=\"200px\"/>";
                                                    echo "<h4 class=\"card-title green-text mb-3\">$worker[Type]</h4>";
                                                    echo "<h6 class=\"card-subtitle mb-3\">RM $worker[Price].00</h6>";
                                                    echo "<p class=\"card-text mb-2\" style=\"overflow: hidden; white-space: nowrap; text-overflow: ellipsis;\">Description: $worker[Description].</p>";
                                                    if($_SESSION['role'] == "Basic" || $_SESSION['role'] == "Admin"){
                                                        if(in_array($worker['Id'], $inCart)){
                                                            echo "<a id=\"cart$worker[Id]\" data-added=\"true\" class=\"btn btn-success mt-3 disabled\" href=\"#\" role=\"button\"><i class=\"fas fa-shopping-cart\"></i> Added to Cart</a>";
                                                        } else{
                                                            echo "<a id=\"cart$worker[Id]\" data-id=\"$worker[Id]\" data-added=\"false\" class=\"btn btn-success btn-cart mt-3\" href=\"#\" role=\"button\"><i class=\"fas fa-shopping-cart\"></i> Add to Cart</a>";
                                                        }
                                                    }
                                                echo "</div>";
                                            echo "</div>";
                                        echo "</a>";
                                    echo "</div>";
                                echo "</div>";
                            }
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo "No records matching your query were found.";
                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                    ?>
                </div>

                <div id="gardener" class="owl-carousel owl-theme px-5">
                    <?php
                    // Attempt select query execution
                    $sql = "SELECT * FROM Workers WHERE Status = 'Accepted' AND Type = 'Gardener'";

                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            while($worker = mysqli_fetch_array($result)){
                                $photo = base64_encode($worker['Photo']);

                                echo "<div class=\"item p-5\">";
                                    echo "<div class=\"card shadow\">";
                                        echo "<a data-id=\"$worker[Id]\" class=\"profile\" href=\"#\">";
                                            echo "<div class=\"card-body text-center\">";
                                                echo "<div class=\"box\">";
                                                    echo "<img src=\"data:image/jpeg;base64,$photo\" class=\"rounded-circle mt-2 mb-5\" width=\"200px\"/>";
                                                    echo "<h4 class=\"card-title green-text mb-3\">$worker[Type]</h4>";
                                                    echo "<h6 class=\"card-subtitle mb-3\">RM $worker[Price].00</h6>";
                                                    echo "<p class=\"card-text mb-2\" style=\"overflow: hidden; white-space: nowrap; text-overflow: ellipsis;\">Description: $worker[Description].</p>";
                                                    if($_SESSION['role'] == "Basic" || $_SESSION['role'] == "Admin"){
                                                        if(in_array($worker['Id'], $inCart)){
                                                            echo "<a id=\"cart$worker[Id]\" data-added=\"true\" class=\"btn btn-success mt-3 disabled\" href=\"#\" role=\"button\"><i class=\"fas fa-shopping-cart\"></i> Added to Cart</a>";
                                                        } else{
                                                            echo "<a id=\"cart$worker[Id]\" data-id=\"$worker[Id]\" data-added=\"false\" class=\"btn btn-success btn-cart mt-3\" href=\"#\" role=\"button\"><i class=\"fas fa-shopping-cart\"></i> Add to Cart</a>";
                                                        }
                                                    }
                                                echo "</div>";
                                            echo "</div>";
                                        echo "</a>";
                                    echo "</div>";
                                echo "</div>";
                            }
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo "No records matching your query were found.";
                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                    ?>
                </div>

                <div id="babysitter" class="owl-carousel owl-theme px-5">
                    <?php
                    // Attempt select query execution
                    $sql = "SELECT * FROM Workers WHERE Status = 'Accepted' AND Type = 'Babysitter'";

                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            while($worker = mysqli_fetch_array($result)){
                                $photo = base64_encode($worker['Photo']);

                                echo "<div class=\"item p-5\">";
                                    echo "<div class=\"card shadow\">";
                                        echo "<a data-id=\"$worker[Id]\" class=\"profile\" href=\"#\">";
                                            echo "<div class=\"card-body text-center\">";
                                                echo "<div class=\"box\">";
                                                    echo "<img src=\"data:image/jpeg;base64,$photo\" class=\"rounded-circle mt-2 mb-5\" width=\"200px\"/>";
                                                    echo "<h4 class=\"card-title green-text mb-3\">$worker[Type]</h4>";
                                                    echo "<h6 class=\"card-subtitle mb-3\">RM $worker[Price].00</h6>";
                                                    echo "<p class=\"card-text mb-2\" style=\"overflow: hidden; white-space: nowrap; text-overflow: ellipsis;\">Description: $worker[Description].</p>";
                                                    if($_SESSION['role'] == "Basic" || $_SESSION['role'] == "Admin"){
                                                        if(in_array($worker['Id'], $inCart)){
                                                            echo "<a id=\"cart$worker[Id]\" data-added=\"true\" class=\"btn btn-success mt-3 disabled\" href=\"#\" role=\"button\"><i class=\"fas fa-shopping-cart\"></i> Added to Cart</a>";
                                                        } else{
                                                            echo "<a id=\"cart$worker[Id]\" data-id=\"$worker[Id]\" data-added=\"false\" class=\"btn btn-success btn-cart mt-3\" href=\"#\" role=\"button\"><i class=\"fas fa-shopping-cart\"></i> Add to Cart</a>";
                                                        }
                                                    }
                                                echo "</div>";
                                            echo "</div>";
                                        echo "</a>";
                                    echo "</div>";
                                echo "</div>";
                            }
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo "No records matching your query were found.";
                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                    ?>
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
                            <li class="mx-3"><a href="#about-us">ABOUT US</a></li>
                            <li class="mx-3"><a href="#our-plans">OUR PLANS</a></li>
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

        <!-- jquery.min.js -->
        <script src="js/jquery.min.js"></script>

        <!-- bootstrap.bundle.min.js -->
        <script src="js/bootstrap5.bundle.min.js"></script>

        <!-- Owl Carousel -->
        <script src="js/owl.carousel.min.js"></script>

        <!-- Font Awesome -->
        <script src="https://kit.fontawesome.com/153cf5ee17.js" crossorigin="anonymous"></script>

        <!-- script.js -->
        <script src="js/script.js"></script>

        <script>
            $('.owl-carousel').owlCarousel({
                loop: true,
                autoplay: true,
                autoplayTimeout: 5000,
                autoplayHoverPause: true,
                margin: -30,
                nav: true,
                dots: false,
                responsive: {
                    0:{
                        items: 1
                    },
                    600:{
                        items: 2
                    },
                    1000:{
                        items: 3
                    }
                }
            })
        </script>

        <script>
            document.getElementById("cartButton").onclick = function () {
                location.href = "cart.php";
            }
        </script>

        <script>
            // Worker Details
            $('.profile').on('click', function () {
                var id = $(this).attr('data-id');
                var added = $('#cart'+id).attr('data-added');
                var inCart = <?=$count?>;
                location.href = "workerDetails.php?id="+id+"&added="+added+"&inCart="+inCart;
            });

            // Add to Cart
            $('.btn-cart').on('click', function () {
                var id = $(this).attr('data-id');
                location.href = "addCart.php?id="+id;
            });
        </script>

    </body>

</html>