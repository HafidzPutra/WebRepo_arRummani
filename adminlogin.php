<?php
session_start();
include 'connect.php';
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    if (empty($username) || empty($password)) {
        echo "<script>alert('Isilah Semua Datanya!');</script>";
    } else {
        $query = "SELECT * FROM dataadmin WHERE username = ?";
        $stmt = mysqli_prepare($conn, $query);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if ($row = mysqli_fetch_assoc($result)) {
                if (password_verify($password, $row['password'])) {
                    $_SESSION['admin_id'] = $row['id'];
                    $_SESSION['admin_name'] = $row['name'];
                    $_SESSION['admin_username'] = $row['username'];
                    header("Location: studentlist.php");
                    exit();
                } else {
                    echo "<script>alert('Username atau password salah!');</script>";
                }
            } else {
                echo "<script>alert('Username atau password salah!');</script>";
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "<script>alert('Terjadi kesalahan pada sistem.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>Login Staff</title>
</head>
<body>

<div class="container">
    <h2>Login Staff</h2>
    <form method="POST">

    Username : <br>
    <input type="text" name="username"><br><br>

    Password : <br>
    <input type="password" name="password"><br><br>

    <button type="submit" name="login">Login</button>

    </form>
</div>
</body>
</html>