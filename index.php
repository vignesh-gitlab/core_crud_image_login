<?php
session_start();

// Redirect to login if not logged in
if (isset($_SESSION['user_email'])) {
    header('Location: dashboard.php');
    exit();
}
?>
<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scaling=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>
    <title>CRUD Login</title>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-sm-10">
                <h2 style="text-align: center;">CRUD</h2>
                <button class="btn btn-primary" id="add">Add</button>
                <button class="btn btn-warning" id="login">Login</button>
                <table class="table table-bordered table-striped" style="margin-top: 10px;">
                    <thead>
                        <th>S.No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <!-- <th>Password</th> -->
                        <th>Image</th>
                        <th>Action</th>
                    </thead>
                    <tbody id="tbody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        var url = "user.php?action=";
        $(document).ready(function() {
            loadTable();
        });

        function loadTable() {
            $.ajax({
                url: url + "show",
                type: "GET",
                success: function(response) {
                    $('#tbody').html(response);
                }
            });
        }

        $(document).on('click', '.edit', function() {
            var id = $(this).data('id');
            window.location.href = "addoreditUser.php?id=" + id;
        });

        $(document).on('click', '.delete', function() {
            var id = $(this).data('id');
            if (confirm("Are you sure to Delete this User?")) {
                $.ajax({
                    url: url + 'delete',
                    type: "POST",
                    data: {
                        id: id
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status == "success") {
                            alert(response.message);
                            loadTable();
                        } else {
                            alert(response.message);
                        }
                    }
                });
            }
        });

        $('#add').click(function() {
            window.location.href = "addoreditUser.php";
        });
        $('#login').click(function() {
            window.location.href = 'login.php';
        });
    </script>

</body>

</html>