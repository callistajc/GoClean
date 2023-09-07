<?php
// Initialize the session
session_start();

// Include connection file
require_once "connection.php";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Validate type
    $input_type = trim($_POST["type"]);
    if(empty($input_type) || !($input_type == "Housework" || $input_type == "Gardener" || $input_type == "Babysitter")){
        $type_err = "Please select a type.";
    } else{
        $type = $input_type;
    }

    // Validate price
    if(empty(trim($_POST["price"]))){
        $price_err = "Please enter your price.";
    } elseif(is_nan($_POST["price"])){
        $price_err = "Please enter a number.";
    } else{
        $price = trim($_POST["price"]);
    }

    // Validate description
    if(empty(trim($_POST["desc"]))){
        $desc_err = "Please enter description.";
    } elseif(strlen(trim($_POST["desc"])) > 250){
        $desc_err = "Description cannot exceed 250 characters.";
    } else{
        $desc = trim($_POST["desc"]);
    }

    // Validate photo
    if(!empty($_FILES["photo"]["name"])){
        // Get file info
        $fileName = basename($_FILES["photo"]["name"]);
        $fileType = pathinfo($fileName, PATHINFO_EXTENSION);

        // Allow certain file formats
        $allowTypes = array('jpg','png','jpeg');
        if(in_array($fileType, $allowTypes)){
            $photo = $_FILES["photo"]["tmp_name"];
            $photo_content = file_get_contents($photo);
        } else{
            $photo_err = "Only JPG, JPEG, & PNG files are allowed.";
        }
    } else{
        $photo_err = "Please select an image file to upload.";
    }

    // Validate IC
    if(!empty($_FILES["IC"]["name"])){
        // Get file info
        $fileName = basename($_FILES["IC"]["name"]);
        $fileType = pathinfo($fileName, PATHINFO_EXTENSION);

        // Allow certain file formats
        $allowTypes = array('jpg','png','jpeg');
        if(in_array($fileType, $allowTypes)){
            $IC = $_FILES["IC"]["tmp_name"];
            $IC_content = file_get_contents($IC);
        } else{
            $IC_err = "Only JPG, JPEG, & PNG files are allowed.";
        }
    } else{
        $IC_err = "Please select an image file to upload.";
    }

    // Check input errors before inserting in database
    if(empty($type_err) && empty($price_err) && empty($desc_err) && empty($photo_err) && empty($IC_err)){

        // Prepare an insert statement
        //$sql = "UPDATE Workers (UserId, Type, Description, Price, Photo, IC, Status) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $sql = "UPDATE Workers SET Type=?, Description=?, Price=?, Photo=?, IC=?, Status=? WHERE UserId=?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssss", $param_type, $param_desc, $param_price, $param_photo, $param_IC, $param_status, $param_userId);
            
            // Set parameters
            $param_type = $type;
            $param_desc = $desc;
            $param_price = $price;
            $param_photo = $photo_content;
            $param_IC = $IC_content;
            $param_status = "Pending"; // Default status
            $param_userId = $_SESSION["id"];

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                header("location: worker.php");
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