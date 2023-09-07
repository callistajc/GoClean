<?php
// Include connection file
require_once "connection.php";
 
// Define variable and initialize with empty values
$role = "";
$role_err = "";

// Processing form data when form is submitted
if(isset($_GET["id"]) && !empty($_GET["id"])){
    // Get value
    $id = $_GET["id"];

    // Validate role
    $input_role = trim($_GET["role"]);
    if(empty($input_role) || !($input_role ==  "Basic" || $input_role == "Admin" || $input_role == "Worker")){
        $role_err = "Please select a role.";
    } else{
        $role = $input_role;
    }
    
    // Check input errors before inserting in database
    if(empty($role_err)){
        // Prepare an update statement
        $sql = "UPDATE Users SET Role=? WHERE Id=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "si", $param_role, $param_id);
            
            // Set parameters
            $param_role = $role;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to admin page
                header("location: admin.php");
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
