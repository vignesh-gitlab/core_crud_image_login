<?php
require_once('db.php');

function getAllUsers()
{
    global $conn;
    $users = [];
    $stmt = $conn->prepare("select * from users");
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }
    return $users;
}

function insertRowData($data)
{
    global $conn;
    $stmt = $conn->prepare('insert into users(name,email,password,image) values(?,?,?,?)');
    $stmt->bind_param('ssss', $data['name'], $data['email'], $data['password'], $data['image']);
    $result = $stmt->execute();
    if ($result) {
        $stmt1 = $conn->prepare('insert into login(username,password) values(?,?)');
        $stmt1->bind_param('ss', $data['email'], $data['password']);
        $stmt1->execute();
    }
    return true;
}

function checkEmail($email)
{
    global $conn;
    $stmt = $conn->prepare('select id from users where email=?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return 'exists';
    }
}

function checkUserLogin($email, $password)
{
    global $conn;
    $stmt = $conn->prepare('select username,password from login where username=?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        while ($row = $result->fetch_assoc()) {
            if ($email == $row['username'] && password_verify($password, $row['password'])) {
                return true;
            }
        }
    }
    return false;
}

function getUser($id)
{
    global $conn;
    $stmt = $conn->prepare("select * from users where id=?");
    $stmt->bind_param('s', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        while ($row = $result->fetch_assoc()) {
            return $row;
        }
    }
    return false;
}

function updateUser($id, $data)
{
    global $conn;
    $stmt = $conn->prepare("update users set name=?,email=?,password=?,image=? where id=?");
    $stmt->bind_param('ssssi', $data['name'], $data['email'], $data['password'], $data['image'], $id);
    if ($stmt->execute()) {
        $stmt1 = $conn->prepare("update login set username=?,password=?,updated_at=NOW() where userid=?");
        $stmt1->bind_param("ssi", $data['email'], $data['password'], $id);
        return $stmt1->execute();
    }
    return false;
}

function deleteUser($id)
{
    global $conn;
    $stmt = $conn->prepare("delete from users where id=?");
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        $stmt1 = $conn->prepare("delete from login where userid=?");
        $stmt1->bind_param('i', $id);
        return $stmt1->execute();
    }
    return false;
}