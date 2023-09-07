<?php
// Initialize the session
session_start();

// Include connection file
require_once "connection.php";

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && ($_SESSION["role"] === "Basic" || $_SESSION["role"] === "Admin")){
    if(isset($_GET['id']) && !empty($_GET['id'])){
        // Prepare an insert statement
        $sql = "INSERT INTO Cart (UserId, WorkerId) VALUES (?, ?)";
    
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ii", $param_userId, $param_workerId);
                
            // Set parameters
            $param_userId = $_SESSION['id'];
            $param_workerId = $_GET['id'];
    
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