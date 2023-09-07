<?php
// Initialize the session
session_start();

// Include connection file
require_once "connection.php";
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
                            if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && $_SESSION["role"] === "Admin"){
                                echo "<li class=\"nav-item\">";
                                    echo "<a class=\"nav-link\" href=\"admin.php\">Admin Site</a>";
                                echo "</li>";
                                echo "<li class=\"nav-item\">";
                                    echo "<a class=\"nav-link\" href=\"logout.php\">Log Out</a>";
                                echo "</li>";
                            } elseif(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && $_SESSION["role"] === "Basic"){
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
                    <h1 class="col-12 mb-5 text-center">ORDER CONFIRMED!</h1>
                    <div class="col-6 mb-3">
                        <button type="button" class="btn btn-primary col-4" onclick="history.back()">
                            <i class="fa-regular fa-circle-left"></i>
                             Back
                        </button>
                    </div>
                    <!-- <div class="col-6 mb-3">
                        <button type="button" class="btn btn-primary btn-pdf col-4 offset-8">
                            <i class="fa-solid fa-file-pdf"></i>
                             Print PDF
                        </button>
                    </div> -->
                    <div class="col-12">
                        <div class="card">
                            <div class="row m-3">
                                <h2 class="col-12 fw-bold text-center">Go<span class="green-text">Clean</span>.</h2>
                                <p class="col-12 text-center gray-text">Thank you for your order!</p>
                                <hr>
                                <div class="col-md-6 col-12 lh-1">
                                    <p class="col-12 fw-bold">Order Number:</p>
                                    <p class="col-12 gray-text"><?php echo rand(10000, 99999) ?></p>
                                    <p class="col-12 fw-bold">Order Date:</p>
                                    <p id="orderDate" class="col-12 gray-text"><?php echo date("F j, Y") ?></p>
                                    <p class="col-12 fw-bold">Payment Type:</p>
                                    <p class="col-12 fw-bold gray-text mb-2">VISA</p>
                                    <p class="col-12 gray-text">XXXX XXXX XXXX <?php echo rand(1000, 9999) ?></p>
                                </div>
                                <div class="col-md-6 col-12 lh-1">
                                    <p class="col-12 fw-bold">Customer Address:</p>
                                    <p class="col-12 gray-text"><?php echo $_SESSION["firstName"] . " " . $_SESSION["lastName"]; ?></p>
                                    <p class="col-12 gray-text">D'Latour Condominium, B-19-12</p>
                                    <p class="col-12 gray-text">Jalan Taylors, Bandar Sunway, Subang Jaya, Selangor</p>
                                    <p class="col-12 gray-text">Malaysia, 46150</p>
                                </div>
                                <hr>
                                <h5 class="col-6 fw-bold mb-4">Order Summary:</h5>
                                <p class="col-6 text-end"><?php echo $_GET["count"]; ?> item(s)</p>
                                <?php
                                // Define variables and initialize with empty values
                                $count = $total = 0;

                                // Get value
                                $id = $_SESSION['id'];

                                // Prepare a select statement
                                $sql = "SELECT Cart.Id, Users.FirstName, Users.LastName, Workers.Photo, Workers.Description, Workers.Price
                                        FROM Users, Workers, Cart
                                        WHERE Cart.UserId = $id AND Cart.WorkerId = Workers.Id AND Workers.UserId = Users.Id";

                                if($result = mysqli_query($link, $sql)){
                                    if(mysqli_num_rows($result) > 0){
                                        // Number of items in cart
                                        $count = mysqli_num_rows($result);

                                        while($cart = mysqli_fetch_array($result)){
                                            $photo = base64_encode($cart['Photo']);
                                            $total += $cart['Price'];

                                            echo "<div class=\"col-md-2 col-sm-4 mb-3 text-sm-start text-center\">";
                                                echo "<img src=\"data:image/jpeg;base64,$photo\" class=\"rounded-circle\" height=\"80px\" width=\"80px\">";
                                            echo "</div>";
                                            echo "<div class=\"col-md-6 col-sm-3 text-sm-start text-center\">";
                                                echo "<p class=\"fw-bold\">$cart[FirstName] $cart[LastName]</p>";
                                                echo "<p>$cart[Description]</p>";
                                            echo "</div>";
                                            echo "<p class=\"col-md-1 col-6 text-center\">1</p>";
                                            echo "<p class=\"col-md-3 col-6 fw-bold text-md-end text-center\">RM $cart[Price]</p>";
                                            echo "<hr>";
                                        }
                                    }
                                }
                                ?>
                                <p class="col-md-3 col-6 offset-md-6 fw-bold mb-0">Hire Duration</p>
                                <p class="col-md-3 col-6 fw-bold text-end mb-0"><?php echo $_GET["duration"]; ?> day(s)</p>
                                <p class="col-md-3 col-12 offset-md-6 gray-text"><?php echo date("F j, Y", strtotime($_GET["startDate"])) . "-" . date("F j, Y", strtotime($_GET["endDate"])); ?></p>
                                <p class="col-md-3 col-6 offset-md-6 fw-bold mb-0">Discount Code</p>
                                <p class="col-md-3 col-6 fw-bold text-end mb-0">- RM <?php echo $_GET["discount"]; ?>.00</p>
                                <p class="col-md-3 col-12 offset-md-6 gray-text">(<?php echo $_GET["discountCode"]; ?>)</p>
                                <p class="col-md-3 col-6 offset-md-6 fw-bold">Item(s) Subtotal</p>
                                <p class="col-md-3 col-6 fw-bold text-end">RM <?php echo $_GET["subtotal"]; ?>.00</p>
                                <hr class="col-md-6 col-12 offset-md-6">
                                <p class="col-md-3 col-6 offset-md-6 fw-bold">ORDER TOTAL</p>
                                <p class="col-md-3 col-6 fw-bold text-end">RM <?php echo $_GET["orderTotal"]; ?>.00</p>
                            </div>
                        </div>
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

        <!-- jquery.min.js -->
        <script src="js/jquery.min.js"></script>

        <!-- bootstrap.bundle.min.js -->
        <script src="js/bootstrap5.bundle.min.js"></script>

        <!-- Font Awesome -->
        <script src="https://kit.fontawesome.com/153cf5ee17.js" crossorigin="anonymous"></script>

        <!-- script.js -->
        <script src="js/script.js"></script>

    </body>

</html>