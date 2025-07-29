<?php $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : '';
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
    <title>Add User</title>
</head>

<body>
    <div class="container">
        <h2 id="content_title">Add User</h2>
        <form id="addForm" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-3">
                    <label>Name:</label>
                </div>
                <div class="col-md-9">
                    <input type="text" name="name" class="form-control" required>
                </div>
            </div>
            <div style="height: 10px;"></div>

            <div class="row">
                <div class="col-md-3">
                    <label>Email:</label>
                </div>
                <div class="col-md-9">
                    <input type="email" name="email" id="email" onblur="checkEmail(this.value)" class="form-control"
                        required>
                    <div id="email_error_message"></div>
                </div>
            </div>
            <div style="height: 10px;"></div>

            <div class="row">
                <div class="col-md-3">
                    <label>Password:</label>
                </div>
                <div class="col-md-9">
                    <input type="text" name="password" id="password" class="form-control" required>
                </div>
            </div>
            <div style="height: 10px;"></div>

            <div class="row">
                <div class="col-md-3">
                    <label>Confirm Password:</label>
                </div>
                <div class="col-md-9">
                    <input type="text" name="confirm_password" id="confirm_password" class="form-control" required>
                </div>
            </div>
            <div style="height: 10px;"></div>

            <div class="row">
                <div class="col-md-3">
                    <label>Image:</label>
                </div>
                <div class="col-md-9">
                    <input type="file" name="image" class="form-control" <?php echo $id == '' ? 'required' : ''; ?>>
                </div>
            </div>
            <div style="height: 10px;"></div>

            <button class="btn btn-primary" type="submit" name="submit" id="submit_btn">Submit</button>
            <button class="btn btn-secondary" type="reset" name="reset">Reset</button>
            <button class="btn btn-warning" type="button" name="home" id="home">Home</button>
        </form>

    </div>

    <script>
        const id = '<?= $id; ?>';
        var url = "user.php?action=";
        $(document).ready(function() {
            if (id != '') {
                $.ajax({
                    url: url + 'getUserDetails',
                    type: "POST",
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 'success') {
                            let user = response.data;
                            $('input[name="name"]').val(user.name);
                            $('input[name="email"]').val(user.email);
                            $('#password').val("********");
                            $('#confirm_password').val("********");
                            if (!$('input[name="id"]').length) {
                                $('#addForm').append('<input type="hidden" name="id" value="' + user
                                    .id + '">');
                            }
                            if (user.image) {
                                const imgPreview = '<img src="' + user.image +
                                    '" alt="User Image" width="60" height="60">';
                                $('input[name="image"').after(imgPreview);
                            }
                            $('#submit_btn').text("Update");
                            $(document).prop("title", "Edit User");
                            $('#content_title').text("Edit User");
                        } else {
                            alert("Error in getting data.");
                        }
                    }
                });
            }
        });

        var url = "user.php?action=";

        $('#addForm').on('submit', function(e) {
            e.preventDefault();

            var password = $('#password').val().trim();
            var confirm_password = $('#confirm_password').val().trim();
            if (password === confirm_password) {
                var formData = new FormData(this);
                var id = $('input[name="id"]').val();
                if (id) {
                    var action = 'update';
                } else {
                    var action = 'insert';
                }
                $.ajax({
                    url: url + action,
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: "json",
                    success: function(response) {
                        if (response.status == 'success') {
                            alert(response.message);
                            window.location.href = "index.php";
                        } else {
                            alert("Invalid Data");
                        }
                    },
                    error: function(e) {
                        console.log(e);
                        alert("Error in Insert!")
                    }
                });

            } else {
                alert("Password did not match");
            }
        });

        function checkEmail(email) {
            if (email != '') {
                $.ajax({
                    url: url + 'checkEmail',
                    type: 'POST',
                    data: {
                        email: email
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 'found') {
                            $('#email_error_message').text("Email Alread Found!").css('color', 'red');
                            $('#submit_btn').prop("disabled", true);
                        } else {
                            $('#email_error_message').text("");
                            $('#submit_btn').prop("disabled", false);
                        }
                    }
                });
            }
        }




        $('#home').click(function() {
            window.location.href = 'index.php';
        });
    </script>

</body>

</html>