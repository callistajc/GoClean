<?php
// Include connection file
require_once "connection.php";
 
// Define variables and initialize with empty values
$firstName = $lastName = $email = $password = $confirm_password = $role = "";
$firstName_err = $lastName_err = $email_err = $password_err = $confirm_password_err = $role_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate first name
    if(empty(trim($_POST["firstName"]))){
        $firstName_err = "Please enter your first name.";     
    } elseif(strlen(trim($_POST["firstName"])) > 50){
        $firstName_err = "First name cannot exceed 50 characters.";
    } else{
        $firstName = trim($_POST["firstName"]);
    }

    // Validate last name
    if(empty(trim($_POST["lastName"]))){
        $lastName_err = "Please enter your last name.";
    } elseif(strlen(trim($_POST["lastName"])) > 50){
        $lastName_err = "Last name cannot exceed 50 characters.";
    } else{
        $lastName = trim($_POST["lastName"]);
    }
 
    // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter a email.";
    } elseif(!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)){
        $email_err = "Please enter valid email.";
    } else{
        // Prepare a select statement
        $sql = "SELECT Id FROM Users WHERE email = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // Set parameters
            $param_email = trim($_POST["email"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $email_err = "This email is already taken.";
                } else{
                    $email = trim($_POST["email"]);
                    $email = strtolower($email);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["rePassword"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["rePassword"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    // Validate role
    if(empty(trim($_POST["role"]))){
        $role_err = "Please select a role.";
    } else{
        $role = trim($_POST["role"]);
    }
    
    // Check input errors before inserting in database
    if(empty($firstName_err) && empty($lastName_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO Users (FirstName, LastName, Email, Password, Role) VALUES (?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $param_firstName, $param_lastName, $param_email, $param_password, $param_role);
            
            // Set parameters
            $param_firstName = $firstName;
            $param_lastName = $lastName;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_role = $role;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to index page
                header("location: index.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
                echo mysqli_stmt_error($stmt);
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
                                <div class="col-sm-6">
                                    <div class="card-body p-sm-5">
                                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                            <div class="row g-3">
                                                <div class="col-sm-12 my-4">
                                                    <h5>Sign up</h5>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="firstName" class="form-label">First Name</label>
                                                    <input type="text" class="form-control" placeholder="John" id="firstName" name="firstName">
                                                    <span style="color: red;"><?=$firstName_err?></span>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="lastName" class="form-label">Last Name</label>
                                                    <input type="text" class="form-control" placeholder="Smith" id="lastName" name="lastName">
                                                    <span style="color: red;"><?=$lastName_err?></span>
                                                </div>
                                                <div class="col-sm-12">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" class="form-control" placeholder="john.smith@gmail.com" id="email" name="email">
                                                    <span style="color: red;"><?=$email_err?></span>
                                                </div>
                                                <div class="col-sm-12">
                                                    <label for="password" class="form-label">Password</label>
                                                    <input type="password" class="form-control" id="password" name="password">
                                                    <span style="color: red;"><?=$password_err?></span>
                                                </div>
                                                <div class="col-sm-12">
                                                    <label for="rePassword" class="form-label">Retype Password</label>
                                                    <input type="password" class="form-control" id="rePassword" name="rePassword">
                                                    <span style="color: red;"><?=$confirm_password_err_err?></span>
                                                </div>
                                                <div class="col-sm-12">
                                                    <label class="form-label">Register as:</label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" id="basic" name="role" value="Basic">
                                                        <label class="form-check-label" for="basic"> Basic</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" id="worker" name="role" value="Worker">
                                                        <label class="form-check-label" for="worker"> Worker</label>
                                                    </div>
                                                    <span style="color: red;"><?=$role_err?></span>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="newspaper">
                                                        <label class="form-check-label" for="newspaper">
                                                            I want to receive latest news and updates
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 mt-5">
                                                    <button type="submit" id="submit" name="signup" class="btn btn-success col-12">Sign up</button>
                                                </div>
                                                <div class="col-sm-12 mt-4">
                                                    <p class="text-center">Already have an account? <a href="login.php" class="text-decoration-none">Log in</a></p>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-sm-6 d-flex align-items-center">
                                    <div class="col-12 text-black px-3 py-4 p-md-5">
                                        <h1 id="GOCLEAN" class="text-center"><a href="index.php" class="green-text text-decoration-none">GoClean.</a></h1>
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