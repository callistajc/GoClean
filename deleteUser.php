<?php
// Include connection file
require_once "connection.php";

// Define variable and initialize with value
$success = false;

// Delete user record
if (isset($_GET['id']) && !empty($_GET['id']) && isset($_GET['role']) && !empty($_GET['role'])) {

    // Assign variable
    $id = trim($_GET["id"]);
    
    // Prepare a delete statement for Users
    $sql = "DELETE FROM Users WHERE Id = ?";

    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Set parameters
        $param_id = $id;
        
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            $success = true;
        } else {
            $success = false;
            echo "Oops! Something went wrong in an attempt to delete from 'Users'. Please try again later.";
        }
    }

    if ($_GET["role"] == "Worker") {

        // Assign variable
        $workerId = 0;

        // Prepare a select statement
        $sql = "SELECT Id FROM Workers WHERE UserId = $id";

        if ($result = mysqli_query($link, $sql)) {
            $worker = mysqli_fetch_array($result);
            $workerId = $worker['Id'];
        }

        // Check if previous query successfully executed or not.
        if ($success) {
            // Prepare a delete statement for Workers
            $sql = "DELETE FROM Workers WHERE UserId = ?";

            if ($stmt = mysqli_prepare($link, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "i", $param_id);
                
                // Set parameters
                $param_id = $id;

                // Attempt to execute the prepared statement
                if (mysqli_stmt_execute($stmt)) {
                    $success = true;
                } else {
                    $success = false;
                    echo "Oops! Something went wrong in an attempt to delete from 'Workers'. Please try again later.";
                }
            }
        }

        // Check if previous query successfully executed or not.
        if ($success) {
            // Prepare a delete statement for Cart
            $sql = "DELETE FROM Cart WHERE WorkerId = ?";

            if ($stmt = mysqli_prepare($link, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "i", $param_id);
                
                // Set parameters
                $param_id = $workerId;

                // Attempt to execute the prepared statement
                if (mysqli_stmt_execute($stmt)) {
                    // if all records deleted successfully. Redirect to admin page
                    header("location: admin.php");
                    exit();
                } else {
                    echo "Oops! Something went wrong in an attempt to delete from 'Cart'. Please try again later.";
                }
            }
        }
    }
     
    // Close statement
    mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($link);
}
?>