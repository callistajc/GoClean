<?php
// Include connection file
require_once "connection.php";
 
// Define variable and initialize with empty values
$status = "";
$status_err = "";

// Processing form data when form is submitted
if(isset($_GET["id"]) && !empty($_GET["id"])){
    // Get value
    $id = $_GET["id"];

    // Validate status
    $input_status = trim($_GET["status"]);
    if(empty($input_status) || !($input_status ==  "Accepted" || $input_status == "Rejected")){
        $status_err = "Please select a status.";
    } else{
        $status = $input_status;
    }
    
    // Check input errors before inserting in database
    if(empty($status_err)){
        // Prepare an update statement
        $sql = "UPDATE Workers SET Status=? WHERE UserId=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "si", $param_status, $param_id);
            
            // Set parameters
            $param_status = $status;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to previous page
                header("location: " . $_SERVER['HTTP_REFERER']);
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
