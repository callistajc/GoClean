<?php
// Initialize the session
session_start();
 
// Include connection file
require_once "connection.php";
 
// Define variables and initialize with empty values
$email = $password = "";
$email_err = $password_err = $login_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if email is empty
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter email.";
    } else{
        $email = trim($_POST["email"]);
        $email = strtolower($email);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($email_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT Id, FirstName, LastName, Email, Password, Role FROM Users WHERE email = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // Set parameters
            $param_email = $email;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if email exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $firstName, $lastName, $email, $hashed_password, $role);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["firstName"] = $firstName;
                            $_SESSION["lastName"] = $lastName;
                            $_SESSION["email"] = $email;
                            $_SESSION["role"] = $role;
                            
                            // Redirect user to index page
                            header("location: index.php");
                            exit;
                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid email or password.";

                        }
                    }
                } else{
                    // Email doesn't exist, display a generic error message
                    $login_err = "Invalid email or password.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
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
    <body>
        <section class="min-vh-100" style="background-color: rgb(244, 246, 249);">
            <div class="container py-5 min-vh-100">
                <div class="row d-flex justify-content-center align-items-center min-vh-100">
                    <div class="col-xl-10">
                        <div class="card rounded-3 text-black border-0">
                            <div class="row g-0">
                                <div class="col-sm-6 d-flex align-items-center">
                                    <div class="col-12 text-black px-3 py-4 p-md-5">
                                        <h1 id="GOCLEAN" class="text-center"><a href="index.php" class="green-text text-decoration-none">GoClean.</a></h1>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card-body p-sm-5">
                                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                            <div class="row">
                                                <div class="col-sm-12 mt-3">
                                                    <h5>Log in</h5>
                                                </div>
                                                <div class="col-sm-12 mt-3">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" class="form-control" placeholder="john.smith@gmail.com" id="email" name="email">
                                                    <span style="color: red;"><?=$email_err?></span>
                                                </div>
                                                <div class="col-sm-12 mt-3">
                                                    <label for="password" class="form-label">Password</label>
                                                    <input type="password" class="form-control" id="password" name="password">
                                                    <span style="color: red;"><?=$password_err?></span>
                                                </div>
                                                <div class="col-sm-12 mt-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="remember-me">
                                                        <label class="form-check-label" for="remember-me">
                                                            Remember me
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 mt-5">
                                                    <button id="login" type="submit" class="btn btn-success col-12">Log in</button>
                                                    <span style="color: red;"><?=$login_err?></span>
                                                </div>
                                                <div class="col-sm-12 mt-4">
                                                    <p class="text-center">Need an account? <a href="signup.php" class="text-decoration-none">Sign up</a></p>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

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
    </body>

</html>