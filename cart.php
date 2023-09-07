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
                    <h2 class="col-12 mb-5">Shopping Cart</h2>
                    <div class="col-md-8 col-12">
                        <div class="card">
                            <div class="row m-3">
                                <p class="col-12 fw-bold">YOUR ORDER</p>
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

                                            echo "<hr>";
                                            echo "<div class=\"col-lg-2 col-md-3 col-sm-4 mb-3 text-sm-start text-center\">";
                                                echo "<img src=\"data:image/jpeg;base64,$photo\" class=\"rounded-circle\" height=\"80px\" width=\"80px\">";
                                            echo "</div>";
                                            echo "<div class=\"col-lg-5 col-md-4 col-sm-3 text-sm-start text-center\">";
                                                echo "<p class=\"fw-bold\">$cart[FirstName] $cart[LastName]</p>";
                                                echo "<p>$cart[Description]</p>";
                                            echo "</div>";
                                            echo "<p class=\"col-md-1 col-4 text-center\">1</p>";
                                            echo "<p class=\"col-md-3 col-4 fw-bold text-center\">RM $cart[Price]</p>";
                                            echo "<a data-id=\"$cart[Id]\" class=\"btn-delete col-md-1 col-4 text-end\"><i class=\"fa-solid fa-trash-can\"></i></a>";
                                        }
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-12 mt-md-0 mt-4">
                        <div class="card">
                            <div class="row m-3">
                                <p class="col-6 fw-bold">ORDER SUMMARY</p>
                                <p class="col-6 text-end"><?=$count?> item(s)</p>
                                <hr>
                                <form>
                                    <label for="startDate" class="col-12 mb-2">Start Date</label>
                                    <input id="startDate" class="form-control col-12 mb-3" type="date" />
                                    <label for="endDate" class="col-12 mb-2">End Date</label>
                                    <input id="endDate" class="form-control col-12 mb-3" type="date" />
                                    <label for="discountCode" class="col-12 mb-2">Discount Code</label>
                                    <input id="discountCode" class="form-control col-12" type="text" placeholder="Enter your code">
                                    <label id="discountCodeValidation" class="mb-3" style="color: red;"></label>
                                </form>
                                <p class="col-6">Hire Duration</p>
                                <p class="col-6 text-end"><span id="duration">1</span> Day(s)</p>
                                <p class="col-6">Discount Code</p>
                                <p class="col-6 text-end">- RM <span id="discount">0</span>.00</p>
                                <p class="col-6">Item(s) Subtotal</p>
                                <p class="col-6 text-end">RM <?=$total?>.00</p>
                                <hr>
                                <p class="col-6 fw-bold">ORDER TOTAL</p>
                                <p class="col-6 fw-bold text-end">RM <span id="total"><?=$total?></span>.00</p>
                                <a class="btn btn-success btn-checkout col-12 mb-3" role="button">CHECKOUT</a>
                                <a class="btn btn-outline-success col-12" href="index.php" role="button">CONTINUE SHOPPING</a>
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

        <script>
            // Global variable
            var grandTotal = <?=$total?>;
            var discount = false;

            // Delete from Cart
            $('.btn-delete').on('click', function () {
                var id = $(this).attr('data-id');
                location.href = "deleteCart.php?id="+id;
            });

            // Set start date and end date to today date
            $(document).ready(function() {
                var fullDate = new Date();
                var formatMonth = (fullDate.getMonth()+1) < 10 ? "0" + (fullDate.getMonth()+1) : (fullDate.getMonth()+1);
                var formatDate = fullDate.getDate() < 10 ? "0" + fullDate.getDate() : fullDate.getDate();
                var today = fullDate.getFullYear() + "-" + formatMonth + "-" + formatDate;
                $('#startDate').val(today);
                $('#endDate').val(today);
            });

            // Calculate duration and total when start date changed
            $('#startDate').change(function () {
                // Assign values
                var total = <?=$total?>;
                var start = document.getElementById("startDate").value;
                var end = document.getElementById("endDate").value;

                // Calculate duration
                var timeDiff = new Date(end).getTime() - new Date(start).getTime();
                var daysDiff = timeDiff / (1000 * 3600 * 24) + 1;

                // Change values
                $('#duration').text(daysDiff);

                if (discount) {
                    $('#total').text(total * daysDiff - 20);
                    grandTotal = total * daysDiff;
                } else {
                    $('#total').text(total * daysDiff);
                    grandTotal = total * daysDiff;
                }
            });

            // Calculate duration and total when end date changed
            $('#endDate').change(function () {
                // Assign values
                var total = <?=$total?>;
                var start = document.getElementById("startDate").value;
                var end = document.getElementById("endDate").value;

                // Calculate duration
                var timeDiff = new Date(end).getTime() - new Date(start).getTime();
                var daysDiff = timeDiff / (1000 * 3600 * 24) + 1;

                // Change values
                $('#duration').text(daysDiff);

                if (discount) {
                    $('#total').text(total * daysDiff - 20);
                    grandTotal = total * daysDiff;
                } else {
                    $('#total').text(total * daysDiff);
                    grandTotal = total * daysDiff;
                }
            });

            // Calculate discount and total when discount code entered
            $('#discountCode').change(function () {
                // Assign values
                var total = $('#total').text();
                var code = "GOCLEAN20";
                var inputCode = $(this).val();
                
                // Validate discount
                if (inputCode == code) {
                    $('#discount').text(20);
                    $('#total').text(total - 20);
                    $('#discountCodeValidation').empty();
                    discount = true;
                } else if (inputCode != code) {
                    $('#discount').text(0);
                    $('#total').text(grandTotal);
                    $('#discountCodeValidation').text("Invalid code");
                    discount = false;
                } else if (!inputCode) {
                    $('#discount').text(0);
                    $('#total').text(grandTotal);
                    $('#discountCodeValidation').empty();
                }
            });

            // Checkout
            $('.btn-checkout').on('click', function () {
                var count = <?=$count?>;
                var startDate = $('#startDate').val();
                var endDate = $('#endDate').val();
                var duration = $('#duration').text();
                var subtotal = <?=$total?>;
                var orderTotal = $('#total').text();

                if ($('#discount').text() != 0) {
                    var discount = $('#discount').text();
                    var discountCode = $('#discountCode').val();
                    location.href = "checkout.php?count="+count+"&startDate="+startDate+"&endDate="+endDate+"&duration="+duration+"&discount="+discount+"&discountCode="+discountCode+"&subtotal="+subtotal+"&orderTotal="+orderTotal;
                } else {
                    location.href = "checkout.php?count="+count+"&startDate="+startDate+"&endDate="+endDate+"&duration="+duration+"&discount=0"+"&discountCode=Unapplied"+"&subtotal="+subtotal+"&orderTotal="+orderTotal;
                }                
            });
        </script>
    </body>

</html>