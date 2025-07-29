<?php
require_once('db.php');
require_once('user_model.php');
$action = $_GET['action'];

switch ($action) {
    case 'show':
        loadData();
        break;
    case 'insert':
        insertData();
        break;
    case 'checkEmail':
        checkEmailExists();
        break;
    case 'checkLogin':
        checkLogin();
        break;
    case 'getUserDetails':
        getUserDetails();
        break;
    case 'update':
        update();
        break;
    case 'delete':
        delete();
        break;
    default:
        echo "Invalid Action";
}

function loadData()
{
    $data = getAllUsers();
    if ($data) {
        $i = 1;
        foreach ($data as $row) {
?>
<tr>
    <td><?= $i; ?></td>
    <td><?= $row["name"]; ?></td>
    <td><?= $row["email"]; ?></td>
    <!-- <td><?= $row["password"]; ?></td> -->
    <td>
        <?php
                    if ($row["image"]) {
                    ?>
        <img src="<?= $row['image']; ?>" alt="Profile Image" height="60" width="60">
        <?php
                    } else {
                        echo "No Image";
                    }
                    ?>
    <td>
        <button class="btn btn-warning edit" data-id="<?= $row["id"]; ?>">Edit</button> ||
        <button class="btn btn-danger delete" data-id="<?= $row["id"]; ?>">Delete</button>
    </td>
</tr>
<?php
            $i++;
        }
    } else {
        ?>
<tr>
    <td colspan="5" style="text-align: center;">No Data Found</td>
</tr>
<?php
    }
}

function insertData()
{
    $data = $_POST;
    $upload_path = "uploads/";
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (isset($_FILES['image'])) {
        if (in_array($_FILES['image']['type'], $allowed_types) && $_FILES['image']['size'] < 2 * 1024 * 1024) {
            $file_name = basename($_FILES['image']['name']);
            $target_path = $upload_path . $file_name;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $insert_data['name'] = $_POST['name'];
                $insert_data['email'] = $_POST['email'];
                $insert_data['password'] = $password_hash;
                $insert_data['image'] = $target_path;
                if (insertRowData($insert_data)) {
                    echo json_encode(['status' => 'success', 'message' => 'Data Inserted Successfully']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Error in Insert']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error in Insert']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'File Size and Type Issue']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Image not Selected!']);
    }
}


function checkEmailExists()
{
    $email = $_POST['email'];
    if (checkEmail($email) == 'exists') {
        echo json_encode(['status' => 'found']);
    } else {
        echo json_encode(['status' => 'not found']);
    }
}

function checkLogin()
{
    session_start();
    $email = $_POST['email'];
    $password = $_POST['password'];
    if (checkUserLogin($email, $password)) {
        $_SESSION['user_email'] = $email;
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid Login Details']);
    }
}

function getUserDetails()
{
    $id = $_POST['id'];
    $user = getUser($id);
    if ($user) {
        echo json_encode(['status' => 'success', 'data' => $user]);
    } else {
        echo json_encode(['status' => 'error']);
    }
}

function update()
{
    $id = $_POST['id'];
    $user = getUser($id);
    $data['name'] = $_POST['name'];
    $data['email'] = $_POST['email'];
    $password = trim($_POST['password']);
    if (!empty($password) && $password != "********") {
        $data['password'] = password_hash($password, PASSWORD_DEFAULT);
    } else {
        $data['password'] = $user['password'];
    }

    if (!empty($_FILES['image']['name'])) {
        $upload_path = 'uploads/';
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if ($_FILES['image']['size'] < 2 * 1024 * 1024 && in_array($_FILES['image']['type'], $allowed_types)) {
            $file_name = basename($_FILES['image']['name']);
            $target_path = $upload_path . $file_name;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                $data['image'] = $target_path;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error in File upload']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalide File type and size']);
        }
    } else {
        $data['image'] = $user['image'];
    }
    if (updateUser($id, $data)) {
        echo json_encode(['status' => 'success', 'message' => 'Updated Successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error in Update']);
    }
}

function delete()
{
    $id = $_POST['id'];
    if (deleteUser($id)) {
        echo json_encode(['status' => 'success', 'message' => 'Deleted Successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error in Delete']);
    }
}