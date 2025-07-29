<?php
session_start();

// Redirect to login if not logged in
if (isset($_SESSION['user_email'])) {
    header('Location: dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>
    <title>Login</title>
</head>

<body>
    <div class="container">

        <h4 class="text-center mb-4">Login</h4>
        <div id="error_message"></div>
        <form id="loginForm">
            <div class="form-group">
                <input type="email" name="username" class="form-control" id="username" placeholder="Enter Username"
                    required>
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control" id="password" placeholder="Enter Password"
                    required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <a href="index.php"><button type="button" class="btn btn-warning">Home</button></a>
        </form>

    </div>
    <script>
    var url = "user.php?action=";
    $('#loginForm').on('submit', function(e) {
        e.preventDefault();

        var email = $('#username').val().trim();
        var password = $('#password').val().trim();
        if (email != '' && password != '') {
            $.ajax({
                url: url + 'checkLogin',
                type: "POST",
                data: {
                    email: email,
                    password: password
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == "success") {
                        window.location.href = "dashboard.php";
                    } else {
                        $('#error_message').text(response.message).css("color", "red");
                    }
                }
            });
        } else {
            alert("Enter User Name and Password");
        }
    });
    </script>
</body>

</html>