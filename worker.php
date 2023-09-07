<?php
// Initialize the session
session_start();

// Include connection file
require_once 'connection.php';

// Define variable and initialize with empty values
$type = $price = $desc = $photo_content = $IC_content= "";
$type_err = $price_err = $photo_err = $IC_err = "";

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
        $sql = "INSERT INTO Workers (UserId, Type, Description, Price, Photo, IC, Status) VALUES (?, ?, ?, ?, ?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssss", $param_userId, $param_type, $param_desc, $param_price, $param_photo, $param_IC, $param_status);
            
            // Set parameters
            $param_userId = $_SESSION["id"];
            $param_type = $type;
            $param_desc = $desc;
            $param_price = $price;
            $param_photo = $photo_content;
            $param_IC = $IC_content;
            $param_status = "Pending"; // Default status

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

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>GoClean - Worker</title>

    <!-- Font Awesome -->
    <link href="css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Custom Font -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom Admin CSS -->
    <link href="css/admin.min.css" rel="stylesheet">

    <!-- Custom Style CSS -->
    <link href="css/style.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-dark sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-text mx-3">Go<span style="color: #198754;">Clean</span></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Users -->
            <li class="nav-item active">
                <a class="nav-link" href="worker.php">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Manage
            </div>

            <!-- Nav Item - Sales -->
            <li class="nav-item">
                <a class="nav-link" href="workerSales.php">
                    <i class="fas fa-fw fa-chart-bar"></i>
                    <span>Sales</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo "$_SESSION[firstName] $_SESSION[lastName]"; ?></span>
                                <img class="img-profile rounded-circle"
                                    src="images/undraw_profile_1.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0" style="color: #24292e;">Registration</h1>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <?php
                            // Get value
                            $id = $_SESSION['id'];

                            // Prepare a select statement
                            $sql = "SELECT * FROM Workers WHERE UserId = $id";

                            if ($result = mysqli_query($link, $sql)) {
                                $worker = mysqli_fetch_array($result);

                                if ($worker > 0) {
                                    $photo = base64_encode($worker['Photo']);
                                    $IC = base64_encode($worker['IC']);

                                    echo "<form class=\"container\" action=\"updateWorkerRegistration.php\" method=\"POST\" enctype=\"multipart/form-data\">";
                                        echo "<div class=\"row g-3\">";
                                            echo "<div class=\"col-md-12 mb-4 row\">";
                                                if($worker['Status'] == "Accepted"){
                                                    echo "<h5 class=\"col-12 black-text font-weight-bold\">Status: <span class=\"green-text\">$worker[Status]</span></h5>";
                                                } elseif($worker['Status'] == "Rejected"){
                                                    echo "<h5 class=\"col-12 black-text font-weight-bold\">Status: <span class=\"red-text\">$worker[Status]</span></h5>";
                                                } elseif($worker['Status'] == "Pending"){
                                                    echo "<h5 class=\"col-12 black-text font-weight-bold\">Status: <span class=\"orange-text\">$worker[Status]</span></h5>";
                                                }
                                            echo "</div>";
                                            echo "<div class=\"col-md-6\">";
                                                echo "<label for=\"type\" class=\"form-label\">Type</label>";
                                                echo "<select class=\"form-control\" id=\"type\" name=\"type\" disabled>";
                                                    if($worker['Type'] == "Housework"){
                                                        echo "<option value=\"Housework\" selected>Housework</option>";
                                                        echo "<option value=\"Gardener\">Gardener</option>";
                                                        echo "<option value=\"Babysitter\">Babysitter</option>";
                                                    } elseif($worker['Type'] == "Gardener"){
                                                        echo "<option value=\"Housework\" selected>Housework</option>";
                                                        echo "<option value=\"Gardener\" selected>Gardener</option>";
                                                        echo "<option value=\"Babysitter\">Babysitter</option>";
                                                    } elseif($worker['Type'] == "Babysitter"){
                                                        echo "<option value=\"Housework\" selected>Housework</option>";
                                                        echo "<option value=\"Gardener\">Gardener</option>";
                                                        echo "<option value=\"Babysitter\" selected>Babysitter</option>";
                                                    }
                                                echo "</select>";
                                            echo "</div>";
                                            echo "<div class=\"col-md-6\">";
                                                echo "<label for=\"price\" class=\"form-label\">Price</label>";
                                                echo "<input type=\"text\" class=\"form-control\" id=\"price\" name=\"price\" value=\"$worker[Price]\" disabled>";
                                            echo "</div>";
                                            echo "<div class=\"col-md-12 mt-3\">";
                                                echo "<label for=\"desc\" class=\"form-label\">Description</label>";
                                                echo "<input type=\"text\" class=\"form-control\" id=\"desc\" name=\"desc\" value=\"$worker[Description]\" disabled>";
                                            echo "</div>";
                                            echo "<div class=\"col-md-6 mt-3 row\">";
                                                echo "<label class=\"col-12\">Upload photo:</label>";
                                                echo "<div class=\"col-12\">";
                                                    echo "<img id=\"photo\" src=\"data:image/jpeg;base64,$photo\" height=\"200px\" width=\"200px\">";
                                                echo "</div>";
                                            echo "</div>";
                                            echo "<div class=\"col-md-6 mt-3\">";
                                                echo "<label>Upload IC:</label>";
                                                echo "<div class=\"col-12\">";
                                                    echo "<img id=\"IC\" src=\"data:image/jpeg;base64,$IC\" height=\"200px\" width=\"200px\">";
                                                echo "</div>";
                                            echo "</div>";
                                            echo "<div class=\"col-md-12 mt-3\">";
                                                echo "<button type=\"button\" id=\"edit\" name=\"edit\" class=\"btn btn-warning mr-2\">Edit</button>";
                                                echo "<button type=\"submit\" id=\"save\" name=\"save\" class=\"btn btn-success\" disabled>Save</button>";
                                            echo "</div>";
                                        echo "</div>";
                                    echo "</form>";
                                } else {
                                    // Define action path
                                    $actionLink = htmlspecialchars($_SERVER["PHP_SELF"]);

                                    echo "<form class=\"container\" action=\"$actionLink\" method=\"POST\" enctype=\"multipart/form-data\">";
                                        echo "<div class=\"row g-3\">";
                                            echo "<div class=\"col-md-6\">";
                                                echo "<label for=\"type\" class=\"form-label\">Type</label>";
                                                echo "<select class=\"form-control\" id=\"type\" name=\"type\">";
                                                    echo "<option value=\"Housework\">Housework</option>";
                                                    echo "<option value=\"Gardener\">Gardener</option>";
                                                    echo "<option value=\"Babysitter\">Babysitter</option>";
                                                echo "</select>";
                                            echo "</div>";
                                            echo "<div class=\"col-md-6\">";
                                                echo "<label for=\"price\" class=\"form-label\">Price</label>";
                                                echo "<input type=\"text\" class=\"form-control\" id=\"price\" name=\"price\" required>";
                                            echo "</div>";
                                            echo "<div class=\"col-md-12 mt-3\">";
                                                echo "<label for=\"desc\" class=\"form-label\">Description</label>";
                                                echo "<input type=\"text\" class=\"form-control\" id=\"desc\" name=\"desc\" required>";
                                            echo "</div>";
                                            echo "<div id=\"photo\" class=\"col-md-6 mt-3\">";
                                                echo "<label>Upload photo:</label>";
                                                echo "<input id=\"photo\" type=\"file\" name=\"photo\">";
                                            echo "</div>";
                                            echo "<div id=\"IC\" class=\"col-md-6 mt-3\">";
                                                echo "<label>Upload IC:</label>";
                                                echo "<input id=\"IC\" type=\"file\" name=\"IC\">";
                                            echo "</div>";
                                            echo "<div class=\"col-md-12 mt-3\">";
                                                echo "<button type=\"submit\" id=\"submit\" name=\"register\" class=\"btn btn-success\">Register</button>";
                                            echo "</div>";
                                        echo "</div>";
                                    echo "</form>";
                                }
                                // $photo = base64_encode($worker['Photo']);
                                // $IC = base64_encode($worker['IC']);

                                // echo "<form class=\"container\" action=\"updateWorkerRegistration.php\" method=\"POST\" enctype=\"multipart/form-data\">";
                                //     echo "<div class=\"row g-3\">";
                                //         echo "<div class=\"col-md-12 mb-4 row\">";
                                //             if($worker['Status'] == "Accepted"){
                                //                 echo "<h5 class=\"col-12 black-text font-weight-bold\">Status: <span class=\"green-text\">$worker[Status]</span></h5>";
                                //             } elseif($worker['Status'] == "Rejected"){
                                //                 echo "<h5 class=\"col-12 black-text font-weight-bold\">Status: <span class=\"red-text\">$worker[Status]</span></h5>";
                                //             } elseif($worker['Status'] == "Pending"){
                                //                 echo "<h5 class=\"col-12 black-text font-weight-bold\">Status: <span class=\"orange-text\">$worker[Status]</span></h5>";
                                //             }
                                //         echo "</div>";
                                //         echo "<div class=\"col-md-6\">";
                                //             echo "<label for=\"type\" class=\"form-label\">Type</label>";
                                //             echo "<select class=\"form-control\" id=\"type\" name=\"type\" disabled>";
                                //                 if($worker['Type'] == "Housework"){
                                //                     echo "<option value=\"Housework\" selected>Housework</option>";
                                //                     echo "<option value=\"Gardener\">Gardener</option>";
                                //                     echo "<option value=\"Babysitter\">Babysitter</option>";
                                //                 } elseif($worker['Type'] == "Gardener"){
                                //                     echo "<option value=\"Housework\" selected>Housework</option>";
                                //                     echo "<option value=\"Gardener\" selected>Gardener</option>";
                                //                     echo "<option value=\"Babysitter\">Babysitter</option>";
                                //                 } elseif($worker['Type'] == "Babysitter"){
                                //                     echo "<option value=\"Housework\" selected>Housework</option>";
                                //                     echo "<option value=\"Gardener\">Gardener</option>";
                                //                     echo "<option value=\"Babysitter\" selected>Babysitter</option>";
                                //                 }
                                //             echo "</select>";
                                //         echo "</div>";
                                //         echo "<div class=\"col-md-6\">";
                                //             echo "<label for=\"price\" class=\"form-label\">Price</label>";
                                //             echo "<input type=\"text\" class=\"form-control\" id=\"price\" name=\"price\" value=\"$worker[Price]\" disabled>";
                                //         echo "</div>";
                                //         echo "<div class=\"col-md-12 mt-3\">";
                                //             echo "<label for=\"desc\" class=\"form-label\">Description</label>";
                                //             echo "<input type=\"text\" class=\"form-control\" id=\"desc\" name=\"desc\" value=\"$worker[Description]\" disabled>";
                                //         echo "</div>";
                                //         echo "<div class=\"col-md-6 mt-3 row\">";
                                //             echo "<label class=\"col-12\">Upload photo:</label>";
                                //             echo "<div class=\"col-12\">";
                                //                 echo "<img id=\"photo\" src=\"data:image/jpeg;base64,$photo\" height=\"200px\" width=\"200px\">";
                                //             echo "</div>";
                                //         echo "</div>";
                                //         echo "<div class=\"col-md-6 mt-3\">";
                                //             echo "<label>Upload IC:</label>";
                                //             echo "<div class=\"col-12\">";
                                //                 echo "<img id=\"IC\" src=\"data:image/jpeg;base64,$IC\" height=\"200px\" width=\"200px\">";
                                //             echo "</div>";
                                //         echo "</div>";
                                //         echo "<div class=\"col-md-12 mt-3\">";
                                //             echo "<button type=\"button\" id=\"edit\" name=\"edit\" class=\"btn btn-warning mr-2\">Edit</button>";
                                //             echo "<button type=\"submit\" id=\"save\" name=\"save\" class=\"btn btn-success\" disabled>Save</button>";
                                //         echo "</div>";
                                //     echo "</div>";
                                // echo "</form>";
                            // } else {
                            //     echo "<form class=\"container\" action=\"htmlspecialchars($_SERVER[PHP_SELF])\" method=\"POST\" enctype=\"multipart/form-data\">";
                            //         echo "<div class=\"row g-3\">";
                            //             echo "<div class=\"col-md-6\">";
                            //                 echo "<label for=\"type\" class=\"form-label\">Type</label>";
                            //                 echo "<select class=\"form-control\" id=\"type\" name=\"type\">";
                            //                     echo "<option value=\"Housework\">Housework</option>";
                            //                     echo "<option value=\"Gardener\">Gardener</option>";
                            //                     echo "<option value=\"Babysitter\">Babysitter</option>";
                            //                 echo "</select>";
                            //             echo "</div>";
                            //             echo "<div class=\"col-md-6\">";
                            //                 echo "<label for=\"price\" class=\"form-label\">Price</label>";
                            //                 echo "<input type=\"text\" class=\"form-control\" id=\"price\" name=\"price\" required>";
                            //             echo "</div>";
                            //             echo "<div class=\"col-md-12 mt-3\">";
                            //                 echo "<label for=\"desc\" class=\"form-label\">Description</label>";
                            //                 echo "<input type=\"text\" class=\"form-control\" id=\"desc\" name=\"desc\" required>";
                            //             echo "</div>";
                            //             echo "<div id=\"photo\" class=\"col-md-6 mt-3\">";
                            //                 echo "<label>Upload photo:</label>";
                            //                 echo "<input id=\"photo\" type=\"file\" name=\"photo\">";
                            //             echo "</div>";
                            //             echo "<div id=\"IC\" class=\"col-md-6 mt-3\">";
                            //                 echo "<label>Upload IC:</label>";
                            //                 echo "<input id=\"IC\" type=\"file\" name=\"IC\">";
                            //             echo "</div>";
                            //             echo "<div class=\"col-md-12 mt-3\">";
                            //                 echo "<button type=\"submit\" id=\"submit\" name=\"register\" class=\"btn btn-success\">Register</button>";
                            //             echo "</div>";
                            //         echo "</div>";
                            //     echo "</form>";
                            }
                            ?>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; GoClean 2022</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- JQuery -->
    <script src="js/jquery.min.js"></script>

    <!-- Bootstrap 4 JS -->
    <script src="js/bootstrap4.bundle.min.js"></script>

    <!-- JQuery Easing -->
    <script src="js/jquery.easing.min.js"></script>

    <!-- Custom Admin JS -->
    <script src="js/admin.min.js"></script>

    <script>
        $("#edit").on('click', function () {
            $("#type").prop('disabled', false);
            $("#price").prop('disabled', false);
            $("#desc").prop('disabled', false);
            $("#edit").prop('disabled', true);
            $("#save").prop('disabled', false);
            $("img#photo").replaceWith("<input id=\"photo\" type=\"file\" name=\"photo\">");
            $("img#IC").replaceWith("<input id=\"IC\" type=\"file\" name=\"IC\">");
        });

        // Shows uploaded photo
        $("input#photo").change(function(e) {
            for (var i = 0; i < e.originalEvent.srcElement.files.length; i++) {

                var file = e.originalEvent.srcElement.files[i];

                var img = document.createElement("img");
                var reader = new FileReader();
                reader.onloadend = function() {
                    img.src = reader.result;
                }
                reader.readAsDataURL(file);
                $("input#photo").after(img);
            }
        });

        // Shows uploaded IC
        $("input#IC").change(function(e) {
            for (var i = 0; i < e.originalEvent.srcElement.files.length; i++) {

                var file = e.originalEvent.srcElement.files[i];

                var img = document.createElement("img");
                var reader = new FileReader();
                reader.onloadend = function() {
                    img.src = reader.result;
                }
                reader.readAsDataURL(file);
                $("input#IC").after(img);
            }
        });
    </script>

</body>

</html>