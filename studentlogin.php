<?php
session_start();
include 'connect.php';
if (isset($_POST['login'])) {
    $name = $_POST['student_name'];
    $password = $_POST['password'];
    if (empty($name) || empty($password)) {
        echo "<script>alert('Isilah Semua Datanya!');</script>";
    } else {
        $query = "SELECT id, nama_siswa, password_siswa FROM datasiswa WHERE nama_siswa = ?";
        $stmt = mysqli_prepare($conn, $query);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $name);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if ($row = mysqli_fetch_assoc($result)) {
                if (password_verify($password, $row['password_siswa'])) {
                    $_SESSION['student_id'] = $row['id'];
                    header("Location: studentaccount.php");
                    exit();
                } else {
                    echo "<script>alert('Nama siswa atau password salah!');</script>";
                }
            } else {
                echo "<script>alert('Nama siswa atau password salah!');</script>";
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "Terjadi kesalahan pada database.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>Login Siswa</title>
</head>
<body>
    <div class="auth-container">
        <div class="sidebar">
            <h2>Pesantren Ar-Rummani</h2>
            <p style="text-align: center;">Login Siswa</p>
        </div>
        <div class="auth-right">
            <div class="auth-box">
                <h2 style="text-align:center;">Login</h2>
                <br>
                <form method="POST">
                    <label>Nama Siswa : </label>
                    <input type="text" name="student_name">
                    <label>Password Siswa : </label>
                    <input type="password" name="password">
                    <button type="submit" name="login">Login</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>