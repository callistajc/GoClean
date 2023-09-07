<?php
// Initialize the session
session_start();

// Include connection file
require_once "connection.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>GoClean - Admin</title>

    <!-- Font Awesome -->
    <link href="css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Custom Font -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom Admin CSS -->
    <link href="css/admin.min.css" rel="stylesheet">

    <!-- DataTable CSS -->
    <link href="css/dataTables.bootstrap4.min.css" rel="stylesheet">

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
                <a class="nav-link" href="admin.php">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Users</span>
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
                <a class="nav-link" href="adminSales.php">
                    <i class="fas fa-fw fa-chart-bar"></i>
                    <span>Sales</span>
                </a>
            </li>

            <!-- Nav Item - Workers Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseWorkers"
                    aria-expanded="true" aria-controls="collapseWorkers">
                    <i class="fas fa-fw fa-boxes"></i>
                    <span>Workers</span>
                </a>
                <div id="collapseWorkers" class="collapse" aria-labelledby="headingWorkers"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Worker Categories:</h6>
                        <a class="collapse-item" href="housework.php">Housework</a>
                        <a class="collapse-item" href="gardener.php">Gardener</a>
                        <a class="collapse-item" href="babysitter.php">Babysitter</a>
                    </div>
                </div>
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
                        <h1 class="h3 mb-0" style="color: #24292e;">Users</h1>
                    </div>

                    <!-- DataTables -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="usersTable" width="100%" cellspacing="0" style="color: #24292e;">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Cart</th>
                                            <th>Edit</th>
                                            <th>Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    // Initialize count for ID
                                    $count = 1;

                                    // Attempt select query execution
                                    $sql = "SELECT * FROM Users";
                                    if($result = mysqli_query($link, $sql)){
                                        if(mysqli_num_rows($result) > 0){
                                            while($user = mysqli_fetch_array($result)){
                                                echo "<tr>";
                                                    echo "<td>$count</td>";
                                                    echo "<td>$user[FirstName] $user[LastName]</td>";
                                                    echo "<td>$user[Email]</td>";
                                                    echo "<td>$user[Role]</td>";
                                                    echo "<td><a data-id=\"$user[Id]\" data-name=\"$user[FirstName]\" href=\"#\" class=\"btn-cart\" data-toggle=\"modal\" data-target=\"#cartModal\"><i class=\"fas fa-shopping-cart\"></i></a></td>";
                                                    echo "<td><a data-id=\"$user[Id]\" data-name=\"$user[FirstName] $user[LastName]\" data-email=\"$user[Email]\" href=\"#\" class=\"btn-edit\" data-toggle=\"modal\" data-target=\"#editModal\"><i class=\"fas fa-edit\"></i></a></td>";
                                                    echo "<td><a data-id=\"$user[Id]\" data-role=\"$user[Role]\" href=\"#\" class=\"btn-delete\" data-toggle=\"modal\" data-target=\"#deleteModal\"><i class=\"fas fa-trash-alt\"></i></a></td>";
                                                echo "</tr>";
                                                $count++;
                                            }
                                            // Free result set
                                            mysqli_free_result($result);
                                        } else{
                                            echo "No records matching your query were found.";
                                        }
                                    } else{
                                        echo "Oops! Something went wrong. Please try again later.";
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
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

    <!-- Logout Modal -->
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

    <!-- Edit Modal -->
    <div id="editModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Record</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" disabled>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" class="form-control" id="email" name="email" disabled>
                        </div>
                        <div class="col-12">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-control" id="role" name="role">
                                <option value="Admin">Admin</option>
                                <option value="Worker">Worker</option>
                                <option value="Basic">Basic</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button data-id="" class="btn btn-primary save">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Record</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Are you sure you want to delete this user record?</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button data-id="" data-role="" class="btn btn-danger confirm-delete">Delete</button>
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

    <!-- DataTable JS -->
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap4.min.js"></script>

    <script>
        // DataTable
        $(document).ready(function () {
            $("#usersTable").DataTable({
                "columnDefs": [{
                    "orderable": false,
                    "targets": [4, 5, 6],
                    "className": "text-center"
                }]
            });
        });

        // Cart Modal
        $('.btn-cart').on('click', function (e) {
            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            location.href="viewCart.php?id="+id+"&name="+name;
        });

        // Edit Modal
        $('.btn-edit').on('click', function (e) {
            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var email = $(this).attr('data-email');
            $('#name').val(name);
            $('#email').val(email);
            $('.save').attr('data-id', id);
        });

        $(".save").on('click', function (e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            var role = $('#role').find(":selected").val();
            location.href="updateUser.php?id="+id+"&role="+role;
        });

        // Delete Modal
        $('.btn-delete').on('click', function (e) {
            var id = $(this).attr('data-id');
            var role = $(this).attr('data-role');
            $('.confirm-delete').attr('data-id', id);
            $('.confirm-delete').attr('data-role', role);
        });

        $(".confirm-delete").on('click', function (e) {
            var id = $(this).attr('data-id');
            var role = $(this).attr('data-role');
            location.href="deleteUser.php?id="+id+"&role="+role;
        });
    </script>

</body>

</html>